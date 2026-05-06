<?php
require_once '../core/init.php';
Auth::protect();

$db = getDB();
$action = $_GET['action'] ?? '';

if ($action === 'get_slots') {
    $doctor_id = $_GET['doctor_id'];
    $date = $_GET['date'];
    $day_of_week = date('w', strtotime($date)); // 0 (Sun) to 6 (Sat)
    
    // Get doctor profile for slot duration
    $stmt = $db->prepare("SELECT slot_duration FROM doctor_profiles WHERE user_id = ?");
    $stmt->execute([$doctor_id]);
    $duration = $stmt->fetchColumn() ?: 15;
    
    // Get availability ranges for this day
    $stmt = $db->prepare("SELECT * FROM doctor_availability WHERE doctor_id = ? AND day_of_week = ?");
    $stmt->execute([$doctor_id, $day_of_week]);
    $ranges = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get existing appointments for this day
    $stmt = $db->prepare("SELECT date_time FROM appointments WHERE doctor_id = ? AND DATE(date_time) = ? AND status NOT IN ('cancelled')");
    $stmt->execute([$doctor_id, $date]);
    $booked_slots = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $booked_times = array_map(function($dt) { return date('H:i', strtotime($dt)); }, $booked_slots);
    
    // Check if date is blocked
    $stmt = $db->prepare("SELECT COUNT(*) FROM doctor_blocked_dates WHERE doctor_id = ? AND blocked_date = ?");
    $stmt->execute([$doctor_id, $date]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['slots' => [], 'message' => 'Doctor is unavailable on this date.']);
        exit;
    }

    $slots = [];
    foreach ($ranges as $range) {
        $start = strtotime($range['start_time']);
        $end = strtotime($range['end_time']);
        
        $current = $start;
        while ($current + ($duration * 60) <= $end) {
            $time = date('H:i', $current);
            $is_booked = in_array($time, $booked_times);
            
            // Check if slot is in the past
            $is_past = (strtotime($date . ' ' . $time) < time());
            
            $slots[] = [
                'time' => $time,
                'label' => date('h:i A', $current),
                'available' => !$is_booked && !$is_past
            ];
            $current += ($duration * 60);
        }
    }
    
    echo json_encode(['slots' => $slots]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'book') {
    $data = json_decode(file_get_contents('php://input'), true);
    $patient_id = $_SESSION['user_id'];
    $doctor_id = $data['doctor_id'];
    $date_time = $data['date'] . ' ' . $data['time'];
    
    // Re-verify availability
    $stmt = $db->prepare("SELECT id FROM appointments WHERE doctor_id = ? AND date_time = ? AND status NOT IN ('cancelled')");
    $stmt->execute([$doctor_id, $date_time]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'This slot is no longer available.']);
        exit;
    }
    
    // Create appointment
    $clinic_id = $_SESSION['clinic_id'] ?? 1;
    $stmt = $db->prepare("
        INSERT INTO appointments (clinic_id, patient_id, doctor_id, date_time, status, type, created_at) 
        VALUES (?, ?, ?, ?, 'pending', ?, NOW())
    ");
    $stmt->execute([$clinic_id, $patient_id, $doctor_id, $date_time, $data['type'] ?? 'Video']);
    $apt_id = $db->lastInsertId();
    
    // Notify Doctor
    $patient_name = $_SESSION['user_name'];
    $booking_time = date('h:i A', strtotime($date_time));
    $booking_date = date('d M, Y', strtotime($date_time));
    notify(
        $doctor_id, 
        "New Appointment", 
        "Patient $patient_name booked a " . ($data['type'] ?? 'Video') . " session for $booking_date at $booking_time.",
        "appointment",
        "appointments.php"
    );

    // Notify Patient (Confirmation)
    notify(
        $patient_id,
        "Booking Confirmed",
        "Your appointment with Dr. is confirmed for $booking_date at $booking_time.",
        "appointment",
        "appointments.php"
    );
    
    echo json_encode(['success' => true, 'appointment_id' => $apt_id]);
    exit;
}
