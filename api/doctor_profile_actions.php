<?php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$doctor_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'save_profile') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $db->prepare("
            UPDATE doctor_profiles SET 
                specialization = ?, experience = ?, qualifications = ?, 
                reg_no = ?, clinic_name = ?, bio = ?, 
                video_fee = ?, visit_fee = ?, slot_duration = ?
            WHERE user_id = ?
        ");
        
        $stmt->execute([
            $data['specialization'], $data['experience'], $data['qualifications'],
            $data['reg_no'], $data['clinic_name'], $data['bio'],
            $data['video_fee'], $data['visit_fee'], $data['slot_duration'],
            $doctor_id
        ]);
        
        // Also update user name and phone
        $stmt = $db->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['phone'], $doctor_id]);
        $_SESSION['user_name'] = $data['name'];

        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'save_availability') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $db->beginTransaction();
        try {
            // Clear existing
            $db->prepare("DELETE FROM doctor_availability WHERE doctor_id = ?")->execute([$doctor_id]);
            
            // Insert new ranges
            $stmt = $db->prepare("INSERT INTO doctor_availability (doctor_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
            foreach ($data['availability'] as $day) {
                if ($day['enabled']) {
                    foreach ($day['ranges'] as $range) {
                        if ($range['start'] && $range['end']) {
                            $stmt->execute([$doctor_id, $day['id'], $range['start'], $range['end']]);
                        }
                    }
                }
            }
            $db->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}

if ($action === 'get_profile') {
    $stmt = $db->prepare("
        SELECT u.name, u.email, u.phone, dp.* 
        FROM users u 
        LEFT JOIN doctor_profiles dp ON u.id = dp.user_id 
        WHERE u.id = ?
    ");
    $stmt->execute([$doctor_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    // If profile row doesn't exist, create it
    if ($profile && is_null($profile['user_id'])) {
        $stmt = $db->prepare("INSERT INTO doctor_profiles (user_id, clinic_id, specialization, experience, video_fee, visit_fee, slot_duration) VALUES (?, ?, 'General Physician', 0, 500, 500, 15)");
        $stmt->execute([$doctor_id, $_SESSION['clinic_id'] ?? 1]);
        
        // Fetch again
        $stmt = $db->prepare("
            SELECT u.name, u.email, u.phone, dp.* 
            FROM users u 
            JOIN doctor_profiles dp ON u.id = dp.user_id 
            WHERE u.id = ?
        ");
        $stmt->execute([$doctor_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Get availability
    $stmt = $db->prepare("SELECT * FROM doctor_availability WHERE doctor_id = ? ORDER BY day_of_week, start_time");
    $stmt->execute([$doctor_id]);
    $availability_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $days = [
        ['id' => 1, 'name' => 'Monday', 'enabled' => false, 'ranges' => []],
        ['id' => 2, 'name' => 'Tuesday', 'enabled' => false, 'ranges' => []],
        ['id' => 3, 'name' => 'Wednesday', 'enabled' => false, 'ranges' => []],
        ['id' => 4, 'name' => 'Thursday', 'enabled' => false, 'ranges' => []],
        ['id' => 5, 'name' => 'Friday', 'enabled' => false, 'ranges' => []],
        ['id' => 6, 'name' => 'Saturday', 'enabled' => false, 'ranges' => []],
        ['id' => 0, 'name' => 'Sunday', 'enabled' => false, 'ranges' => []]
    ];
    
    foreach ($availability_rows as $row) {
        foreach ($days as &$day) {
            if ($day['id'] == $row['day_of_week']) {
                $day['enabled'] = true;
                $day['ranges'][] = ['start' => $row['start_time'], 'end' => $row['end_time']];
            }
        }
    }
    
    // Ensure at least one empty range for enabled days if none exist
    foreach ($days as &$day) {
        if ($day['enabled'] && empty($day['ranges'])) {
            $day['ranges'][] = ['start' => '09:00', 'end' => '17:00'];
        }
        if (empty($day['ranges'])) {
             $day['ranges'][] = ['start' => '09:00', 'end' => '17:00'];
        }
    }

    echo json_encode(['profile' => $profile, 'availability' => $days]);
}
