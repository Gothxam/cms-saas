<?php
// patient/doctors.php — Multi-Step Booking Wizard
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$patient_id = $_SESSION['user_id'];

$success = '';
$error = '';

// Handle Booking Submission (Step 3 confirm)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_doctor_id'])) {
    $doctor_id = (int) $_POST['book_doctor_id'];
    $date      = $_POST['date'] ?? '';
    $time      = $_POST['time'] ?? '';
    $reason    = trim($_POST['reason'] ?? '');
    $date_time = "$date $time";

    if (empty($date) || empty($time)) {
        $error = 'Please select both a date and a time slot.';
    } elseif (strtotime($date_time) < time()) {
        $error = 'You cannot book an appointment in the past.';
    } else {
        // Double-booking check
        $check = $db->prepare("
            SELECT COUNT(*) FROM appointments 
            WHERE doctor_id = ? AND date_time = ? AND status NOT IN ('cancelled')
        ");
        $check->execute([$doctor_id, $date_time]);

        if ($check->fetchColumn() > 0) {
            $error = 'That time slot is already booked. Please choose a different time.';
        } else {
            $ins = $db->prepare("
                INSERT INTO appointments (clinic_id, patient_id, doctor_id, date_time, reason, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'pending', NOW())
            ");
            $ins->execute([$clinic_id, $patient_id, $doctor_id, $date_time, $reason]);
            $success = 'Your appointment has been submitted! The doctor will confirm shortly.';
        }
    }
}

// Fetch all doctors
$stmt = $db->prepare("
    SELECT u.id, u.name, u.email, dp.specialization, dp.biography 
    FROM users u 
    LEFT JOIN doctor_profiles dp ON u.id = dp.user_id 
    WHERE u.clinic_id = ? AND u.role_id = (SELECT id FROM roles WHERE name = 'Doctor')
    ORDER BY u.name ASC
");
$stmt->execute([$clinic_id]);
$doctors = $stmt->fetchAll();

$page_title = "Book an Appointment";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<style>
    .wizard-step { display: none; }
    .wizard-step.active { display: block; animation: fadeUp 0.4s ease; }
    @keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .doctor-card { cursor: pointer; transition: all 0.3s ease; }
    .doctor-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
    .doctor-card.selected { border-color: #0d9488 !important; box-shadow: 0 0 0 3px rgba(13,148,136,0.15), 0 20px 40px rgba(13,148,136,0.1) !important; }
    .doctor-card.selected .doc-avatar { background: #0d9488 !important; color: #fff !important; }
    .doctor-card.selected .doc-check { display: flex !important; }
    .step-dot.done { background: #0d9488 !important; color: #fff !important; }
    .step-dot.active-step { background: #0d9488 !important; color: #fff !important; box-shadow: 0 0 0 4px rgba(13,148,136,0.2); }
    .step-line.done { background: #0d9488 !important; }
    .time-slot { cursor: pointer; transition: all 0.2s ease; }
    .time-slot:hover { border-color: #0d9488; background: #f0fdfa; }
    .time-slot.selected { background: #0d9488 !important; color: #fff !important; border-color: #0d9488 !important; }
</style>

<div class="max-w-6xl mx-auto py-8 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Our Specialists</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Select a specialist to view their profile and book a session.</p>
        </div>
        <a href="appointments.php" class="text-slate-500 hover:text-slate-900 text-xs font-black uppercase tracking-widest flex items-center gap-2 transition-all border border-slate-200 px-5 py-3 rounded-xl hover:bg-slate-50">
            <i data-lucide="clock" class="w-4 h-4"></i> My Bookings
        </a>
    </div>

    <!-- Doctors Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($doctors as $doc): ?>
            <div onclick="window.location.href='book_doctor.php?id=<?php echo $doc['id']; ?>'" class="group bg-white rounded-[3rem] border border-slate-100 p-8 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 hover:-translate-y-2 relative overflow-hidden cursor-pointer">
                <!-- Decorative element -->
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-slate-50 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-8">
                        <div class="w-20 h-20 bg-teal-600 rounded-[2rem] flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-teal-600/20 group-hover:rotate-6 transition-transform">
                            <?php echo strtoupper(substr($doc['name'], 0, 1)); ?>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="px-3 py-1 bg-teal-50 text-teal-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-teal-100">Active</span>
                        </div>
                    </div>

                    <h4 class="text-xl font-black text-slate-900 mb-1">Dr. <?php echo e($doc['name']); ?></h4>
                    <p class="text-teal-600 text-[10px] font-black uppercase tracking-[0.2em] mb-6"><?php echo e($doc['specialization'] ?: 'General Physician'); ?></p>
                    
                    <p class="text-slate-400 text-xs font-medium leading-relaxed mb-8 line-clamp-3">
                        <?php echo e($doc['biography'] ?: 'Expert medical professional committed to delivering high-quality healthcare and personalized treatment plans.'); ?>
                    </p>

                    <div class="w-full bg-slate-900 text-white p-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-slate-900/10 hover:bg-teal-600 hover:shadow-teal-600/20 transition-all flex items-center justify-center gap-3 group/btn">
                        Book Now
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if(empty($doctors)): ?>
            <div class="col-span-full py-20 text-center bg-white border border-dashed border-slate-200 rounded-[3rem]">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="users" class="w-10 h-10 text-slate-200"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900">No doctors available</h3>
                <p class="text-slate-400 text-sm font-medium mt-2">There are no doctors registered in this clinic at the moment.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>
<?php require_once 'components/footer.php'; ?>
