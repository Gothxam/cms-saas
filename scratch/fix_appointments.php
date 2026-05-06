<?php
require_once 'core/init.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE appointments ADD COLUMN type ENUM('Video', 'Clinic') DEFAULT 'Video' AFTER status");
    echo "Column 'type' added successfully.\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
