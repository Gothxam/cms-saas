<?php
require_once 'core/init.php';
$db = getDB();
$stmt = $db->query("SHOW TABLES LIKE 'notifications'");
if ($stmt->rowCount() > 0) {
    echo "TABLE_EXISTS\n";
    $stmt = $db->query("DESCRIBE notifications");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} else {
    echo "NOT_FOUND";
}
