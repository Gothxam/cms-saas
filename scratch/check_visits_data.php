<?php
require_once '../core/init.php';
$db = getDB();
$stmt = $db->query("SELECT * FROM visits WHERE status = 'completed' LIMIT 5");
echo "<pre>";
print_r($stmt->fetchAll());
echo "</pre>";
