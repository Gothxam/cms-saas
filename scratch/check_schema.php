<?php
require_once 'core/init.php';
$db = getDB();
$table = 'patient_documents';
echo "<h3>Schema for $table:</h3><pre>";
try {
    $stmt = $db->query("DESCRIBE $table");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
echo "</pre>";
?>
