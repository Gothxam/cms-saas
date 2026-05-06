<?php
require_once '../core/init.php';
$db = getDB();
$patient_id = 8;
$stmt = $db->prepare("
    SELECT p.*, v.completed_at
    FROM prescriptions p
    JOIN visits v ON p.visit_id = v.id
    WHERE v.patient_id = ?
    ORDER BY p.id DESC
");
$stmt->execute([$patient_id]);
echo "<pre>";
print_r($stmt->fetchAll());
echo "</pre>";
