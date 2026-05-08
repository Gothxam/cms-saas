<?php
require_once '../core/init.php';
header('Content-Type: application/json');

$db = getDB();
$user_id = $_SESSION['user_id'] ?? 0;
$user_role = $_SESSION['user_role'] ?? '';

if (!$user_id || !in_array($user_role, ['Clinic Admin', 'Doctor', 'Patient'])) {
    echo json_encode(['error' => 'Unauthorized', 'debug_role' => $user_role]);
    exit;
}

// Global CSRF Check for all POST actions in this API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Middleware::checkCSRF();
}

$action = $_GET['action'] ?? '';

// --- START CONSULTATION ---
if ($action === 'start' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $apt_id = (int)$_POST['appointment_id'];
    
    // Verify appointment belongs to this doctor
    $stmt = $db->prepare("SELECT patient_id FROM appointments WHERE id = ? AND doctor_id = ?");
    $stmt->execute([$apt_id, $user_id]);
    $apt = $stmt->fetch();
    
    if (!$apt) {
        echo json_encode(['error' => 'Appointment not found']);
        exit;
    }

    // Check if visit already exists
    $v_stmt = $db->prepare("SELECT id FROM visits WHERE appointment_id = ?");
    $v_stmt->execute([$apt_id]);
    $existing_visit = $v_stmt->fetch();

    if (!$existing_visit) {
        // Create visit
        $insert = $db->prepare("INSERT INTO visits (appointment_id, doctor_id, patient_id, status) VALUES (?, ?, ?, 'ongoing')");
        $insert->execute([$apt_id, $user_id, $apt['patient_id']]);
        $visit_id = $db->lastInsertId();
    } else {
        $visit_id = $existing_visit['id'];
    }

    // Update appointment status
    $update = $db->prepare("UPDATE appointments SET status = 'in_progress' WHERE id = ?");
    $update->execute([$apt_id]);

    // Notify Patient (Live Call)
    $doc_name = $_SESSION['user_name'];
    notify(
        $apt['patient_id'],
        "Incoming Medical Call",
        "Dr. $doc_name is ready for your consultation. Join now.",
        "live_session",
        "session.php?id=" . $apt_id
    );

    Middleware::audit('consultation.start', "Doctor started consultation for appointment #$apt_id", $visit_id);

    echo json_encode(['success' => true, 'visit_id' => $visit_id]);
    exit;
}

// --- UPDATE VISIT DATA (Auto-save) ---
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_id = (int)$_POST['visit_id'];
    
    // SOAP Basic Fields
    $chief_complaint = $_POST['chief_complaint'] ?? '';
    $symptoms_data = $_POST['symptoms_data'] ?? '[]';
    $history_illness = $_POST['history_illness'] ?? '';
    $vitals_data = $_POST['vitals_data'] ?? '{}';
    $physical_exam = $_POST['physical_exam'] ?? '';
    $diagnosis = $_POST['diagnosis'] ?? '';
    $differential_diagnosis = $_POST['differential_diagnosis'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $advice = $_POST['advice'] ?? '';
    $follow_up_date = $_POST['follow_up_date'] ?: null;
    $severity = $_POST['severity'] ?? 'mild';
    $admission_needed = (int)($_POST['admission_needed'] ?? 0);

    // Verify visit belongs to this doctor and is ongoing
    $stmt = $db->prepare("
        UPDATE visits SET 
            chief_complaint = ?, 
            symptoms_data = ?, 
            history_illness = ?, 
            vitals_data = ?, 
            physical_exam = ?, 
            diagnosis = ?, 
            differential_diagnosis = ?, 
            notes = ?, 
            advice = ?, 
            follow_up_date = ?, 
            severity = ?, 
            admission_needed = ? 
        WHERE id = ? AND doctor_id = ? AND status = 'ongoing'
    ");
    $stmt->execute([
        $chief_complaint, $symptoms_data, $history_illness, $vitals_data, 
        $physical_exam, $diagnosis, $differential_diagnosis, $notes, 
        $advice, $follow_up_date, $severity, $admission_needed,
        $visit_id, $user_id
    ]);

    // Handle Prescriptions Sync
    if (isset($_POST['prescriptions'])) {
        $prescriptions = json_decode($_POST['prescriptions'], true) ?: [];
        $db->prepare("DELETE FROM prescriptions WHERE visit_id = ?")->execute([$visit_id]);
        foreach ($prescriptions as $p) {
            if (empty($p['medicine_name'])) continue;
            $ins = $db->prepare("INSERT INTO prescriptions (visit_id, medicine_name, dosage, frequency, duration, instructions) VALUES (?, ?, ?, ?, ?, ?)");
            $ins->execute([$visit_id, $p['medicine_name'], $p['dosage'] ?? '', $p['frequency'] ?? '', $p['duration'] ?? '', $p['instructions'] ?? '']);
        }
    }

    // Handle Investigations Sync
    if (isset($_POST['investigations'])) {
        $tests = json_decode($_POST['investigations'], true) ?: [];
        $db->prepare("DELETE FROM investigations WHERE visit_id = ?")->execute([$visit_id]);
        foreach ($tests as $t) {
            if (empty($t['test_name'])) continue;
            $ins = $db->prepare("INSERT INTO investigations (visit_id, test_name, notes) VALUES (?, ?, ?)");
            $ins->execute([$visit_id, $t['test_name'], $t['notes'] ?? '']);
        }
    }

    echo json_encode(['success' => true]);
    exit;
}

// --- END CONSULTATION ---
if ($action === 'end' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $visit_id = (int)$_POST['visit_id'];
    
    // Get appointment_id
    $v_stmt = $db->prepare("SELECT appointment_id FROM visits WHERE id = ? AND doctor_id = ?");
    $v_stmt->execute([$visit_id, $user_id]);
    $visit = $v_stmt->fetch();
    
    if (!$visit) {
        echo json_encode(['error' => 'Visit not found']);
        exit;
    }

    // Mark visit as completed
    $db->prepare("UPDATE visits SET status = 'completed', completed_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$visit_id]);
    
    // Mark appointment as completed
    $db->prepare("UPDATE appointments SET status = 'completed' WHERE id = ?")->execute([$visit['appointment_id']]);

    // Notify Patient
    $v_data = $db->prepare("SELECT patient_id, doctor_id FROM visits WHERE id = ?");
    $v_data->execute([$visit_id]);
    $res = $v_data->fetch();
    
    $doc_name = $_SESSION['user_name'];
    notify(
        $res['patient_id'],
        "Consultation Ready",
        "Your summary and prescriptions from Dr. $doc_name are now available.",
        "visit",
        "view_summary.php?id=" . $visit['appointment_id']
    );

    echo json_encode(['success' => true]);
    exit;
}

// --- FETCH CURRENT VISIT ---
if ($action === 'fetch') {
    $apt_id = (int)$_GET['appointment_id'];
    $stmt = $db->prepare("SELECT * FROM visits WHERE appointment_id = ?");
    $stmt->execute([$apt_id]);
    $visit = $stmt->fetch();

    if ($visit) {
        // Fetch Prescriptions
        $p_stmt = $db->prepare("SELECT * FROM prescriptions WHERE visit_id = ?");
        $p_stmt->execute([$visit['id']]);
        $visit['prescriptions'] = $p_stmt->fetchAll();

        // Fetch Investigations
        $i_stmt = $db->prepare("SELECT * FROM investigations WHERE visit_id = ?");
        $i_stmt->execute([$visit['id']]);
        $visit['investigations'] = $i_stmt->fetchAll();

        echo json_encode($visit);
    } else {
        echo json_encode((object)[]);
    }
    exit;
}
?>
