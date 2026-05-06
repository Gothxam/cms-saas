<?php
require_once '../core/init.php';
$db = getDB();
// Find a patient who HAS prescriptions
$stmt = $db->query("SELECT DISTINCT v.patient_id FROM visits v JOIN prescriptions p ON v.id = p.visit_id LIMIT 1");
$patient = $stmt->fetch();

if ($patient) {
    $patient_id = $patient['patient_id'];
    echo "Testing for Patient ID: $patient_id\n";
    
    // Mimic the API logic
    $stmt = $db->prepare("
        SELECT v.id 
        FROM visits v
        JOIN prescriptions p ON v.id = p.visit_id
        WHERE v.patient_id = ? AND v.status = 'completed'
        ORDER BY v.completed_at DESC, v.id DESC
        LIMIT 1
    ");
    $stmt->execute([$patient_id]);
    $visit = $stmt->fetch();
    
    if ($visit) {
        echo "Found Visit ID: " . $visit['id'] . "\n";
        $stmt = $db->prepare("SELECT * FROM prescriptions WHERE visit_id = ?");
        $stmt->execute([$visit['id']]);
        $data = $stmt->fetchAll();
        echo "Prescriptions Found: " . count($data) . "\n";
        print_r($data);
    } else {
        echo "No completed visit with prescriptions found for this patient.\n";
    }
} else {
    echo "No patients with prescriptions found in the database.\n";
}
