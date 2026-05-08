<?php
// api/notifications_stream.php
require_once '../core/init.php';

// Auth Check (Quickly, for stream)
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$db = getDB();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no'); // Disable buffering for Nginx/Apache proxy

// Set infinite timeout
set_time_limit(0);

$last_check_time = date('Y-m-d H:i:s');

while (true) {
    if (connection_aborted()) break;

    // Check for NEW notifications
    $stmt = $db->prepare("
        SELECT id, title, message, type, link, created_at, is_read 
        FROM notifications 
        WHERE user_id = ? AND (created_at > ? OR is_read = 0)
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute([$user_id, $last_check_time]);
    $notifications = $stmt->fetchAll();

    // Check for NEW chat messages
    $chat_stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM chat_messages 
        WHERE appointment_id IN (SELECT id FROM appointments WHERE doctor_id = ? OR patient_id = ?)
        AND sender_type != ? AND is_read = 0
    ");
    $role_type = in_array($_SESSION['user_role'], ['Doctor', 'Clinic Admin']) ? 'doctor' : 'patient';
    $chat_stmt->execute([$user_id, $user_id, $role_type]);
    $chat_unread = (int)$chat_stmt->fetchColumn();

    // Total unread count (System + Chat)
    $count_stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $count_stmt->execute([$user_id]);
    $sys_unread = (int)$count_stmt->fetchColumn();

    $data = [
        'notifications' => $notifications,
        'unread_count' => $sys_unread + $chat_unread,
        'chat_unread' => $chat_unread,
        'timestamp' => date('H:i:s')
    ];

    echo "data: " . json_encode($data) . "\n\n";
    ob_flush();
    flush();

    $last_check_time = date('Y-m-d H:i:s');
    sleep(3); // Heartbeat/Poll delay
}
