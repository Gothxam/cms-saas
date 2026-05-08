<?php
require_once '../../core/init.php';
header('Content-Type: application/json');

$db = getDB();
$user_id = $_SESSION['user_id'] ?? 0;
$user_role = $_SESSION['user_role'] ?? '';

if (!$user_id || !in_array($user_role, ['Clinic Admin', 'Doctor', 'Receptionist'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$patient_id = (int)($_GET['patient_id'] ?? 0);
if (!$patient_id) {
    echo json_encode(['error' => 'Patient ID required']);
    exit;
}

$action = $_GET['action'] ?? '';

if ($action === 'basic_info') {
    $stmt = $db->prepare("
        SELECT p.*, u.name, u.email 
        FROM patient_profiles p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.user_id = ?
    ");
    $stmt->execute([$patient_id]);
    $info = $stmt->fetch();
    
    if ($info) {
        // Calculate age
        if ($info['dob']) {
            $dob = new DateTime($info['dob']);
            $now = new DateTime();
            $info['age'] = $now->diff($dob)->y;
        } else {
            $info['age'] = 'N/A';
        }
        
        // Get report count
        $stmt = $db->prepare("SELECT COUNT(*) FROM patient_documents WHERE patient_id = ?");
        $stmt->execute([$patient_id]);
        $info['report_count'] = $stmt->fetchColumn();
    }
    
    echo json_encode($info);
    exit;
}

if ($action === 'last_prescription') {
    $stmt = $db->prepare("
        SELECT v.* 
        FROM visits v
        JOIN prescriptions p ON v.id = p.visit_id
        WHERE v.patient_id = ? AND v.status = 'completed'
        ORDER BY v.completed_at DESC, v.id DESC
        LIMIT 1
    ");
    $stmt->execute([$patient_id]);
    $visit = $stmt->fetch();
    
    if ($visit) {
        $stmt = $db->prepare("SELECT * FROM prescriptions WHERE visit_id = ?");
        $stmt->execute([$visit['id']]);
        $prescriptions = $stmt->fetchAll();
        
        echo json_encode([
            'visit' => $visit,
            'prescriptions' => $prescriptions
        ]);
    } else {
        echo json_encode(['error' => 'No previous prescription found']);
    }
    exit;
}

if ($action === 'history') {
    $stmt = $db->prepare("
        SELECT v.*, d.name as doctor_name 
        FROM visits v 
        JOIN users d ON v.doctor_id = d.id 
        WHERE v.patient_id = ? AND v.status = 'completed' 
        ORDER BY v.completed_at DESC
    ");
    $stmt->execute([$patient_id]);
    echo json_encode($stmt->fetchAll());
    exit;
}

if ($action === 'reports') {
    $stmt = $db->prepare("SELECT * FROM patient_documents WHERE patient_id = ? ORDER BY created_at DESC");
    $stmt->execute([$patient_id]);
    $docs = $stmt->fetchAll();
    
    // Enrich with secure URLs
    foreach ($docs as &$doc) {
        $doc['secure_url'] = secure_file_url($doc['file_url']);
    }
    
    echo json_encode($docs);
    exit;
}
?>
