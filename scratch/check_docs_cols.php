<?php
require_once 'core/init.php';
$db = getDB();
$stmt = $db->query('DESCRIBE patient_documents');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
