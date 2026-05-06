<?php
require_once 'core/init.php';
$db = getDB();
$stmt = $db->query("SELECT id, name FROM roles");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
