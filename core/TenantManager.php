<?php
// core/TenantManager.php

class TenantManager {
    private static $currentClinic = null;

    public static function identify() {
        $db = getDB();
        
        // 1. If logged in, prioritize session clinic_id
        if (isset($_SESSION['clinic_id'])) {
            $stmt = $db->prepare("SELECT * FROM clinics WHERE id = ? AND deleted_at IS NULL");
            $stmt->execute([$_SESSION['clinic_id']]);
            $clinic = $stmt->fetch();
            if ($clinic) {
                self::$currentClinic = $clinic;
                return ['type' => 'tenant', 'data' => $clinic];
            }
        }
        
        // 2. Try subdomain detection
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $parts = explode('.', $host);
        if (count($parts) >= 3) {
            $subdomain = $parts[0];
            if ($subdomain !== 'www') {
                $stmt = $db->prepare("SELECT * FROM clinics WHERE subdomain = ? AND deleted_at IS NULL");
                $stmt->execute([$subdomain]);
                $clinic = $stmt->fetch();
                if ($clinic) {
                    self::$currentClinic = $clinic;
                    return ['type' => 'tenant', 'data' => $clinic];
                }
            }
        }
        
        // 3. Fallback: first clinic (development/single-tenant mode)
        $stmt = $db->prepare("SELECT * FROM clinics WHERE deleted_at IS NULL LIMIT 1");
        $stmt->execute();
        $clinic = $stmt->fetch();
        if ($clinic) {
            self::$currentClinic = $clinic;
            return ['type' => 'doctor', 'data' => $clinic];
        }

        return ['type' => 'platform'];
    }

    public static function getClinic() {
        return self::$currentClinic;
    }
}
