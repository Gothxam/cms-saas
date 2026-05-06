<?php
require_once 'core/init.php';
$db = getDB();
$stmt = $db->query('DESCRIBE appointments');
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($cols as $col) {
    echo $col['Field'] . " - " . $col['Null'] . "\n";
}
