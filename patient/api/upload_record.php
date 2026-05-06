<?php
require_once '../../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['report'])) {
    $title = trim($_POST['title'] ?? 'Untitled Document');
    $category = $_POST['category'] ?? 'Other';
    $record_date = $_POST['record_date'] ?? date('Y-m-d');
    $file = $_FILES['report'];

    // Security Checks
    $allowed_exts = ['pdf', 'jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_exts)) {
        die("Error: Invalid file type. Only PDF, JPG, and PNG are allowed.");
    }

    if ($file['size'] > 10 * 1024 * 1024) { // 10MB
        die("Error: File too large. Maximum size is 10MB.");
    }

    // Prepare Upload Directory
    $upload_dir = '../../uploads/documents/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Generate Unique Filename
    $new_filename = 'doc_' . bin2hex(random_bytes(8)) . '.' . $file_ext;
    $target_path = $upload_dir . $new_filename;
    $db_path = 'uploads/documents/' . $new_filename;

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        // Save to Database
        $stmt = $db->prepare("
            INSERT INTO patient_documents (patient_id, clinic_id, title, category, file_url, created_at) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        // We'll use clinic_id 1 as default if not available, or fetch from patient profile
        $clinic_id = 1; 
        
        $stmt->execute([
            $patient_id,
            $clinic_id,
            $title,
            $category,
            $db_path,
            $record_date
        ]);

        header("Location: ../records.php?upload=success");
        exit;
    } else {
        die("Error: Failed to save the file. Please check permissions.");
    }
}
?>
