<?php
require_once 'core/init.php';
$db = getDB();

echo "VISITS TABLE STRUCTURE:\n";
$stmt = $db->query("DESCRIBE visits");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
