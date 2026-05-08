<?php
// serve_file.php
require_once 'core/init.php';

if (!Auth::check()) {
    header("HTTP/1.1 401 Unauthorized");
    exit;
}

$file_path = $_GET['path'] ?? '';
if (empty($file_path)) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}

// Security: Prevent Directory Traversal
$real_storage_dir = realpath(ROOT_PATH . '/storage/');
$full_path = realpath(ROOT_PATH . '/' . $file_path);

if (!$full_path || strpos($full_path, $real_storage_dir) !== 0) {
    // Fallback to uploads if not in storage (for transition)
    $real_uploads_dir = realpath(ROOT_PATH . '/uploads/');
    if (!$full_path || strpos($full_path, $real_uploads_dir) !== 0) {
        header("HTTP/1.1 403 Forbidden");
        die("Invalid path.");
    }
}

// Logic for Ownership Check
$db = getDB();
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

// If it's a patient document, check if user is the patient or the doctor
if (strpos($file_path, 'storage/documents/') !== false || strpos($file_path, 'uploads/documents/') !== false) {
    $stmt = $db->prepare("SELECT patient_id, clinic_id FROM patient_documents WHERE file_url = ?");
    $stmt->execute([$file_path]);
    $doc = $stmt->fetch();
    
    if ($doc) {
        if ($user_role === 'Patient' && $doc['patient_id'] != $user_id) {
            die("Unauthorized access to this document.");
        }
        if ($user_role === 'Doctor' && $doc['clinic_id'] != $_SESSION['clinic_id']) {
            die("Unauthorized access to this document.");
        }
    }
}

// Serve the file
if (file_exists($full_path)) {
    $mime_type = mime_content_type($full_path);
    header("Content-Type: $mime_type");
    header("Content-Length: " . filesize($full_path));
    
    // Cache for 24 hours
    header("Cache-Control: max-age=86400");
    
    readfile($full_path);
    exit;
} else {
    header("HTTP/1.1 404 Not Found");
    exit;
}
