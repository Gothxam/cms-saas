<?php
require_once 'core/init.php';
$db = getDB();
echo "<h3>Patient Sample:</h3><pre>";
$stmt = $db->query("SELECT id, clinic_id, role_id, name FROM users WHERE role_id = 3 LIMIT 5");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";

echo "<h3>Your Current Session Info:</h3><pre>";
session_start();
print_r([
    'user_id' => $_SESSION['user_id'] ?? 'none',
    'clinic_id' => $_SESSION['clinic_id'] ?? 'none',
    'role' => $_SESSION['user_role'] ?? 'none'
]);
echo "</pre>";
?>
