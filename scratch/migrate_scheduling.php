<?php
require_once 'core/init.php';

try {
    $db = getDB();
    
    // Update doctor_profiles
    $db->exec("ALTER TABLE doctor_profiles 
        ADD COLUMN IF NOT EXISTS bio TEXT,
        ADD COLUMN IF NOT EXISTS video_fee DECIMAL(10,2) DEFAULT 500.00,
        ADD COLUMN IF NOT EXISTS visit_fee DECIMAL(10,2) DEFAULT 0.00,
        ADD COLUMN IF NOT EXISTS slot_duration INT DEFAULT 15,
        ADD COLUMN IF NOT EXISTS experience INT DEFAULT 0,
        ADD COLUMN IF NOT EXISTS qualifications VARCHAR(255),
        ADD COLUMN IF NOT EXISTS reg_no VARCHAR(100),
        ADD COLUMN IF NOT EXISTS clinic_name VARCHAR(255)");

    // Create doctor_availability
    $db->exec("CREATE TABLE IF NOT EXISTS doctor_availability (
        id INT AUTO_INCREMENT PRIMARY KEY,
        doctor_id INT NOT NULL,
        day_of_week INT NOT NULL,
        start_time TIME NOT NULL,
        end_time TIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create doctor_blocked_dates
    $db->exec("CREATE TABLE IF NOT EXISTS doctor_blocked_dates (
        id INT AUTO_INCREMENT PRIMARY KEY,
        doctor_id INT NOT NULL,
        blocked_date DATE NOT NULL,
        reason VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Update patient_profiles
    $db->exec("ALTER TABLE patient_profiles 
        ADD COLUMN IF NOT EXISTS blood_group VARCHAR(5),
        ADD COLUMN IF NOT EXISTS allergies TEXT,
        ADD COLUMN IF NOT EXISTS chronic_conditions TEXT,
        ADD COLUMN IF NOT EXISTS medications TEXT,
        ADD COLUMN IF NOT EXISTS smoking TINYINT(1) DEFAULT 0,
        ADD COLUMN IF NOT EXISTS alcohol TINYINT(1) DEFAULT 0,
        ADD COLUMN IF NOT EXISTS emergency_name VARCHAR(255),
        ADD COLUMN IF NOT EXISTS emergency_phone VARCHAR(20)");

    echo "SUCCESS: Schema updated.";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
