<?php
require_once '../core/init.php';
$db = getDB();
try {
    $db->exec("ALTER TABLE patient_documents ADD COLUMN title VARCHAR(255) AFTER clinic_id");
    $db->exec("ALTER TABLE patient_documents ADD COLUMN category VARCHAR(100) AFTER title");
    echo "Migration Success: Added title and category to patient_documents";
} catch (Exception $e) {
    echo "Migration Note: " . $e->getMessage();
}
?>
