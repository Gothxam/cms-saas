<?php
require_once 'core/init.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE patient_profiles ADD COLUMN age INT DEFAULT 0");
    echo "Added age to patient_profiles\n";
} catch(Exception $e) { echo "Age col might exist\n"; }

try {
    $db->exec("ALTER TABLE patient_profiles ADD COLUMN gender VARCHAR(20)");
    echo "Added gender to patient_profiles\n";
} catch(Exception $e) { echo "Gender col might exist\n"; }
