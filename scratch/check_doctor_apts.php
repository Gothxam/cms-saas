<?php
require_once '../core/init.php';
$db = getDB();
$email = 'doctor@practice.com';
$stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $doctor_id = $user['id'];
    $stmt = $db->prepare("SELECT id, status FROM appointments WHERE doctor_id = ? ORDER BY id DESC LIMIT 5");
    $stmt->execute([$doctor_id]);
    echo "<pre>";
    print_r($stmt->fetchAll());
    echo "</pre>";
} else {
    echo "Doctor not found.";
}
