<?php
// core/Middleware.php

class Middleware {
    /**
     * CSRF Token Management
     */
    static function generateToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    static function validateToken($token) {
        if (empty($_SESSION['csrf_token']) || empty($token)) return false;
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    static function csrfField() {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    static function csrfMeta() {
        $token = self::generateToken();
        return '<meta name="csrf-token" content="' . $token . '">';
    }

    /**
     * Check CSRF for POST requests
     */
    static function checkCSRF() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check header (for AJAX) or POST field
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
            if (!self::validateToken($token)) {
                header('HTTP/1.1 403 Forbidden');
                die('CSRF validation failed.');
            }
        }
    }

    /**
     * Simple Session-based Rate Limiting
     */
    static function rateLimit($key, $max = 60, $window = 60) {
        $now = time();
        $session_key = 'rate_limit_' . $key;
        
        if (!isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = ['count' => 1, 'start' => $now];
            return true;
        }

        $data = &$_SESSION[$session_key];
        
        if ($now - $data['start'] > $window) {
            $data = ['count' => 1, 'start' => $now];
            return true;
        }

        if ($data['count'] >= $max) {
            header('HTTP/1.1 429 Too Many Requests');
            die('Too many requests. Please slow down.');
        }

        $data['count']++;
        return true;
    }

    /**
     * Audit Logging
     */
    static function audit($action, $details = '', $target_id = null) {
        try {
            $db = getDB();
            $stmt = $db->prepare("
                INSERT INTO audit_logs (user_id, clinic_id, action, details, target_id, ip_address, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $_SESSION['user_id'] ?? null,
                $_SESSION['clinic_id'] ?? null,
                $action,
                $details,
                $target_id,
                $_SERVER['REMOTE_ADDR'] ?? ''
            ]);
        } catch (Exception $e) {
            // Silently fail to not block the main operation if audit fails
            error_log("Audit log failed: " . $e->getMessage());
        }
    }

    /**
     * Secure File Upload Validation
     */
    static function validateUpload($file, $allowed_exts = ['pdf', 'jpg', 'jpeg', 'png'], $max_size_mb = 10) {
        if (!isset($file) || $file['error'] !== 0) {
            return "File upload error.";
        }

        if ($file['size'] > $max_size_mb * 1024 * 1024) {
            return "File too large (max {$max_size_mb}MB).";
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_exts)) {
            return "Invalid file extension.";
        }

        // MIME validation
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed_mimes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
        ];

        if (!isset($allowed_mimes[$ext]) || $allowed_mimes[$ext] !== $mime) {
            return "Security violation: File content does not match extension.";
        }

        return true;
    }
}
