<?php
// scratch/migrate_roles.php — Role system migration
require_once __DIR__ . '/../core/init.php';
$db = getDB();

echo "<h2>MedOS Role System Migration</h2>";
echo "<hr>";

// Step 1: Add Clinic Admin role
echo "<h3>Step 1: Adding 'Clinic Admin' role...</h3>";
try {
    $check = $db->prepare("SELECT id FROM roles WHERE name = 'Clinic Admin'");
    $check->execute();
    if ($check->fetch()) {
        echo "<p style='color:orange;'>SKIPPED — 'Clinic Admin' role already exists.</p>";
    } else {
        $db->exec("INSERT INTO roles (id, name) VALUES (4, 'Clinic Admin')");
        echo "<p style='color:green;'>SUCCESS — Added 'Clinic Admin' role (id=4).</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>ERROR: " . $e->getMessage() . "</p>";
}

// Step 2: Promote first doctor per clinic to Clinic Admin
echo "<h3>Step 2: Promoting first doctor per clinic to Clinic Admin...</h3>";
try {
    // Find the first (lowest ID) doctor per clinic
    $first_docs = $db->query("
        SELECT MIN(id) as first_doc_id, clinic_id 
        FROM users 
        WHERE role_id = 1 AND deleted_at IS NULL 
        GROUP BY clinic_id
    ")->fetchAll();
    
    if (empty($first_docs)) {
        echo "<p style='color:orange;'>No doctors found to promote.</p>";
    } else {
        $admin_role_id = $db->query("SELECT id FROM roles WHERE name = 'Clinic Admin'")->fetchColumn();
        
        foreach ($first_docs as $doc) {
            $stmt = $db->prepare("UPDATE users SET role_id = ? WHERE id = ?");
            $stmt->execute([$admin_role_id, $doc['first_doc_id']]);
            
            // Get the user's name for display
            $name = $db->prepare("SELECT name FROM users WHERE id = ?");
            $name->execute([$doc['first_doc_id']]);
            $user_name = $name->fetchColumn();
            
            echo "<p style='color:green;'>SUCCESS — Promoted <strong>{$user_name}</strong> (id={$doc['first_doc_id']}) to Clinic Admin for clinic_id={$doc['clinic_id']}.</p>";
        }
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>ERROR: " . $e->getMessage() . "</p>";
}

// Step 3: Verify final state
echo "<h3>Step 3: Verification — Current Users & Roles</h3>";
echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse; font-family:sans-serif;'>";
echo "<tr style='background:#f0f0f0;'><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Clinic</th></tr>";

$users = $db->query("
    SELECT u.id, u.name, u.email, r.name as role_name, u.clinic_id 
    FROM users u 
    JOIN roles r ON u.role_id = r.id 
    WHERE u.deleted_at IS NULL 
    ORDER BY u.clinic_id, r.id, u.id
")->fetchAll();

foreach ($users as $u) {
    $color = match($u['role_name']) {
        'Clinic Admin' => '#059669',
        'Doctor' => '#0d9488',
        'Receptionist' => '#d97706',
        'Patient' => '#6366f1',
        default => '#333',
    };
    echo "<tr>";
    echo "<td>{$u['id']}</td>";
    echo "<td>{$u['name']}</td>";
    echo "<td>{$u['email']}</td>";
    echo "<td style='color:{$color}; font-weight:bold;'>{$u['role_name']}</td>";
    echo "<td>{$u['clinic_id']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<h3>Roles Table</h3>";
$roles = $db->query("SELECT * FROM roles ORDER BY id")->fetchAll();
foreach ($roles as $r) {
    echo "<p>{$r['id']}: <strong>{$r['name']}</strong></p>";
}

echo "<hr><p><a href='../login.php'>← Go to Login</a></p>";
?>
