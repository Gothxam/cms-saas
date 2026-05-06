<?php
require_once '../core/init.php';
// Mimic the API call from session.php
// doctor/api/patient_info.php?action=last_prescription&patient_id=8
$_GET['action'] = 'last_prescription';
$_GET['patient_id'] = 8;
$_SESSION['user_id'] = 1; // doctor elbort
$_SESSION['user_role'] = 'Doctor';

require_once '../doctor/api/patient_info.php';
