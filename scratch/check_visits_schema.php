<?php
require_once '../core/init.php';
$db = getDB();
$stmt = $db->query("DESCRIBE visits");
echo "<pre>";
print_r($stmt->fetchAll());
echo "</pre>";
