<?php
require_once 'core/init.php';
$db = getDB();
echo "--- doctor_profiles ---\n";
$stmt = $db->query("DESCRIBE doctor_profiles");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "\n--- patient_profiles ---\n";
$stmt = $db->query("DESCRIBE patient_profiles");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
