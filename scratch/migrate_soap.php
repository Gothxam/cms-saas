<?php
require_once 'core/init.php';

$db = getDB();

try {
    // 1. Update visits table
    $db->exec("ALTER TABLE visits 
        ADD COLUMN chief_complaint TEXT AFTER patient_id,
        ADD COLUMN symptoms_data JSON AFTER chief_complaint,
        ADD COLUMN history_illness TEXT AFTER symptoms_data,
        ADD COLUMN vitals_data JSON AFTER history_illness,
        ADD COLUMN physical_exam TEXT AFTER vitals_data,
        ADD COLUMN differential_diagnosis TEXT AFTER diagnosis,
        ADD COLUMN advice TEXT AFTER differential_diagnosis,
        ADD COLUMN follow_up_date DATE AFTER advice,
        ADD COLUMN severity VARCHAR(50) DEFAULT 'mild' AFTER follow_up_date,
        ADD COLUMN admission_needed TINYINT(1) DEFAULT 0 AFTER severity
    ");
    echo "Visits table updated successfully.\n";

    // 2. Create prescriptions table
    $db->exec("CREATE TABLE IF NOT EXISTS prescriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        visit_id INT NOT NULL,
        medicine_name VARCHAR(255) NOT NULL,
        dosage VARCHAR(100),
        frequency VARCHAR(100),
        duration VARCHAR(100),
        instructions TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (visit_id) REFERENCES visits(id) ON DELETE CASCADE
    )");
    echo "Prescriptions table created successfully.\n";

    // 3. Create investigations table
    $db->exec("CREATE TABLE IF NOT EXISTS investigations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        visit_id INT NOT NULL,
        test_name VARCHAR(255) NOT NULL,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (visit_id) REFERENCES visits(id) ON DELETE CASCADE
    )");
    echo "Investigations table created successfully.\n";

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
