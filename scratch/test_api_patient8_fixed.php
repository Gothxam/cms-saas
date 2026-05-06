<?php
// Move to the API directory to test it properly
chdir('../doctor/api');
$_GET['action'] = 'last_prescription';
$_GET['patient_id'] = 8;
$_SESSION['user_id'] = 1; // doctor elbort
$_SESSION['user_role'] = 'Doctor';
$_SESSION['clinic_id'] = 1;

require_once 'patient_info.php';
