<?php
require_once '../core/init.php';
$db = getDB();
$patient_id = 8;
$stmt = $db->prepare("
    SELECT v.id, v.completed_at, (SELECT COUNT(*) FROM prescriptions WHERE visit_id = v.id) as p_count
    FROM visits v
    WHERE v.patient_id = ? AND v.status = 'completed'
    ORDER BY v.completed_at DESC
");
$stmt->execute([$patient_id]);
echo "<pre>";
print_r($stmt->fetchAll());
echo "</pre>";
