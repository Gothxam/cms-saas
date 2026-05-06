<?php
require_once 'core/init.php';
$db = getDB();
$stmt = $db->query('DESCRIBE appointments');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
