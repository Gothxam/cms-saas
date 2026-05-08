<?php
require_once __DIR__ . '/../core/init.php';
$db = getDB();
echo "=== ROLES ===\n";
$rows = $db->query("SELECT * FROM roles ORDER BY id")->fetchAll();
foreach ($rows as $r) echo $r['id'] . ': ' . $r['name'] . "\n";

echo "\n=== USERS BY ROLE ===\n";
$rows = $db->query("SELECT u.id, u.name, u.email, r.name as role_name, u.clinic_id FROM users u JOIN roles r ON u.role_id = r.id WHERE u.deleted_at IS NULL ORDER BY r.id, u.id")->fetchAll();
foreach ($rows as $r) echo $r['role_name'] . ' | ' . $r['name'] . ' | ' . $r['email'] . ' | clinic_id=' . $r['clinic_id'] . "\n";

echo "\n=== COLUMNS IN USERS ===\n";
$cols = $db->query("DESCRIBE users")->fetchAll();
foreach ($cols as $c) echo $c['Field'] . ' (' . $c['Type'] . ') ' . ($c['Null'] === 'YES' ? 'NULL' : 'NOT NULL') . "\n";
