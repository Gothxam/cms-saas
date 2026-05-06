<?php
require_once '../core/init.php';
$db = getDB();
$apt_id = 47;
$stmt = $db->prepare("SELECT patient_id FROM appointments WHERE id = ?");
$stmt->execute([$apt_id]);
$apt = $stmt->fetch();
echo "Patient ID for Appointment 47: " . ($apt['patient_id'] ?? 'Not found');
