<?php
require_once '../core/init.php';
$db = getDB();
$email = 'doctor@practice.com';
$stmt = $db->prepare("SELECT id, name FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    echo "Doctor Found: " . $user['name'] . " (ID: " . $user['id'] . ")\n";
    $stmt = $db->prepare("SELECT * FROM appointments WHERE doctor_id = ? ORDER BY id DESC LIMIT 10");
    $stmt->execute([$user['id']]);
    $apts = $stmt->fetchAll();
    echo "<pre>";
    print_r($apts);
    echo "</pre>";
} else {
    echo "Doctor not found.";
}
