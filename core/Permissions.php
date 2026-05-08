<?php
// core/Permissions.php — Role-based page access control

class Permissions {

    // ── Page Group Constants ──────────────────────────────────────
    // Admin-only pages (settings, team management)
    const ADMIN_PAGES = ['settings', 'doctor-add', 'staff-add'];

    // Clinical pages (consultations, prescriptions, medical records)
    const CLINICAL_PAGES = ['session', 'patient-history', 'patient-document-add'];

    // Clinical read-only pages (viewable by Receptionist for coordination)
    const CLINICAL_READONLY = ['patient-profile', 'records'];

    // Shared pages (accessible by all clinic-side roles)
    const SHARED_PAGES = [
        'index', 'patients', 'patient-add', 'patient-edit',
        'appointments', 'appointment-add', 'messages',
        'doctors', 'profile', 'patient-search',
    ];

    // ── Role → Allowed Pages Map ─────────────────────────────────
    // Clinic Admin: everything
    // Doctor: clinical + shared (no admin)
    // Receptionist: shared + clinical read-only (no telehealth, no admin)

    /**
     * Get the list of allowed page basenames for a role
     */
    public static function forRole($role) {
        return match ($role) {
            'Clinic Admin' => array_merge(
                self::ADMIN_PAGES,
                self::CLINICAL_PAGES,
                self::CLINICAL_READONLY,
                self::SHARED_PAGES
            ),
            'Doctor' => array_merge(
                self::CLINICAL_PAGES,
                self::CLINICAL_READONLY,
                self::SHARED_PAGES
            ),
            'Receptionist' => array_merge(
                self::CLINICAL_READONLY,
                self::SHARED_PAGES
            ),
            default => [],
        };
    }

    /**
     * Check if the current user can access a given page basename
     */
    public static function canAccess($page) {
        $role = $_SESSION['user_role'] ?? '';
        $allowed = self::forRole($role);
        return in_array($page, $allowed);
    }

    /**
     * Get the allowed pages for the current session user
     */
    public static function allowedPages() {
        return self::forRole($_SESSION['user_role'] ?? '');
    }

    /**
     * Check if a role is a clinic-side role (not Patient)
     */
    public static function isClinicRole($role = null) {
        $role = $role ?? ($_SESSION['user_role'] ?? '');
        return in_array($role, ['Clinic Admin', 'Doctor', 'Receptionist']);
    }

    /**
     * Check if the current user is a Clinic Admin
     */
    public static function isAdmin() {
        return ($_SESSION['user_role'] ?? '') === 'Clinic Admin';
    }

    /**
     * Check if current user has clinical (write) access
     * Clinic Admin and Doctor can write clinical data.
     * Receptionist can only read.
     */
    public static function canWriteClinical() {
        return in_array($_SESSION['user_role'] ?? '', ['Clinic Admin', 'Doctor']);
    }

    /**
     * Filter a nav_items array to only include items the user can access.
     * Each item must have a 'page' key with the basename (without .php).
     * Sub-items are also filtered.
     */
    public static function filterNav($items) {
        $allowed = self::allowedPages();
        $filtered = [];

        foreach ($items as $label => $data) {
            $page = $data['page'] ?? pathinfo($data['url'] ?? '', PATHINFO_FILENAME);

            // Check if main item is allowed
            if (!in_array($page, $allowed)) {
                continue;
            }

            // Filter sub_items if present
            if (isset($data['sub_items'])) {
                $filtered_subs = [];
                foreach ($data['sub_items'] as $key => $sub) {
                    $sub_page = $sub['page'] ?? pathinfo($sub['url'] ?? '', PATHINFO_FILENAME);
                    if (in_array($sub_page, $allowed)) {
                        $filtered_subs[$key] = $sub;
                    }
                }
                $data['sub_items'] = $filtered_subs;
                // If no sub-items remain, still show the parent
                if (empty($filtered_subs)) {
                    unset($data['sub_items']);
                }
            }

            $filtered[$label] = $data;
        }

        return $filtered;
    }
}
