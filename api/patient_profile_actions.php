<?php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'save_profile') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $stmt = $db->prepare("
            UPDATE patient_profiles SET 
                age = ?, gender = ?, blood_group = ?, 
                allergies = ?, chronic_conditions = ?, medications = ?, 
                smoking = ?, alcohol = ?, 
                emergency_name = ?, emergency_phone = ?
            WHERE user_id = ?
        ");
        
        $stmt->execute([
            $data['age'], $data['gender'], $data['blood_group'],
            $data['allergies'], $data['chronic_conditions'], $data['medications'],
            $data['smoking'] ? 1 : 0, $data['alcohol'] ? 1 : 0,
            $data['emergency_name'], $data['emergency_phone'],
            $patient_id
        ]);
        
        // Also update user name and phone
        $stmt = $db->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        $stmt->execute([$data['name'], $data['phone'], $patient_id]);
        $_SESSION['user_name'] = $data['name'];

        echo json_encode(['success' => true]);
        exit;
    }
}

if ($action === 'get_profile') {
    $stmt = $db->prepare("
        SELECT u.name, u.email, u.phone, pp.* 
        FROM users u 
        LEFT JOIN patient_profiles pp ON u.id = pp.user_id 
        WHERE u.id = ?
    ");
    $stmt->execute([$patient_id]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If profile row doesn't exist, create it
    if ($profile && is_null($profile['user_id'])) {
        $stmt = $db->prepare("INSERT INTO patient_profiles (user_id, clinic_id, age, gender, blood_group) VALUES (?, ?, 0, 'Male', '')");
        $stmt->execute([$patient_id, $_SESSION['clinic_id'] ?? 1]);
        
        // Fetch again
        $stmt = $db->prepare("
            SELECT u.name, u.email, u.phone, pp.* 
            FROM users u 
            JOIN patient_profiles pp ON u.id = pp.user_id 
            WHERE u.id = ?
        ");
        $stmt->execute([$patient_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    echo json_encode(['profile' => $profile]);
}
