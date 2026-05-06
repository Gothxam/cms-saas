<?php
require_once '../core/init.php';
$db = getDB();
$patient_id = 8;
$stmt = $db->prepare("SELECT id, status, completed_at FROM visits WHERE patient_id = ?");
$stmt->execute([$patient_id]);
echo "<pre>";
print_r($stmt->fetchAll());
echo "</pre>";
