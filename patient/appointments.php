<?php
// patient/appointments.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];
$current_tab = $_GET['tab'] ?? 'upcoming';

// Handle Cancellation
if (isset($_POST['cancel_id'])) {
    $apt_id = $_POST['cancel_id'];
    
    // Fetch info before cancelling
    $apt = $db->prepare("SELECT doctor_id, date_time FROM appointments WHERE id = ?");
    $apt->execute([$apt_id]);
    $res = $apt->fetch();
    
    $stmt = $db->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND patient_id = ?");
    $stmt->execute([$apt_id, $patient_id]);

    // Notify Doctor
    $patient_name = $_SESSION['user_name'];
    $dt = date('d M, Y \a\t h:i A', strtotime($res['date_time']));
    notify(
        $res['doctor_id'],
        "Appointment Cancelled",
        "Patient $patient_name has cancelled their appointment for $dt.",
        "appointment",
        "appointments.php"
    );

    header("Location: appointments.php?tab=cancelled&success=cancelled");
    exit;
}

$status_filter = match($current_tab) {
    'completed' => "'completed'",
    'cancelled' => "'cancelled'",
    default => "'pending', 'confirmed', 'in_progress'"
};

$stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name, COALESCE(dp.specialization, 'General Physician') as specialization
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    LEFT JOIN doctor_profiles dp ON d.id = dp.user_id
    WHERE a.patient_id = ? AND a.status IN ($status_filter)
    ORDER BY a.date_time DESC
");
$stmt->execute([$patient_id]);
$appointments = $stmt->fetchAll();

$page_title = "My Appointments";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Appointments</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Manage your consultations and visit history.</p>
        </div>
        <a href="book_doctor.php" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/20 hover:bg-slate-800 transition-all flex items-center gap-2 self-start">
            <i data-lucide="plus" class="w-4 h-4"></i> New Appointment
        </a>
    </div>

    <!-- Tabs -->
    <div class="flex items-center gap-2 p-1.5 bg-slate-100 rounded-3xl w-fit">
        <?php foreach (['upcoming' => 'Upcoming', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label): ?>
            <a href="?tab=<?php echo $key; ?>" 
               class="px-8 py-3.5 rounded-[1.25rem] text-[10px] font-black uppercase tracking-widest transition-all <?php echo $current_tab === $key ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-400 hover:text-slate-600'; ?>">
                <?php echo $label; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Appointments Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php foreach ($appointments as $apt): ?>
            <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm relative group overflow-hidden">
                <!-- Status Badge -->
                <div class="absolute top-8 right-8">
                    <?php 
                        $status_colors = match($apt['status']) {
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'confirmed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'completed' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                            default => 'bg-slate-50 text-slate-600 border-slate-100'
                        };
                    ?>
                    <span class="px-3 py-1 <?php echo $status_colors; ?> border rounded-lg text-[9px] font-black uppercase tracking-widest">
                        <?php echo $apt['status']; ?>
                    </span>
                </div>

                <div class="mb-8">
                    <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6">
                        <?php echo strtoupper(substr($apt['doctor_name'], 0, 1)); ?>
                    </div>
                    <h4 class="text-xl font-black text-slate-900">Dr. <?php echo e($apt['doctor_name']); ?></h4>
                    <p class="text-teal-600 text-[10px] font-black uppercase tracking-widest mt-1"><?php echo e($apt['specialization']); ?></p>
                </div>

                <div class="grid grid-cols-2 gap-4 py-6 border-y border-slate-50 mb-8">
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Date</p>
                        <p class="text-xs font-black text-slate-900"><?php echo date('M d, Y', strtotime($apt['date_time'])); ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Time</p>
                        <p class="text-xs font-black text-slate-900"><?php echo date('h:i A', strtotime($apt['date_time'])); ?></p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <?php if ($apt['status'] === 'confirmed' || $apt['status'] === 'in_progress'): ?>
                        <a href="session.php?id=<?php echo $apt['id']; ?>" class="flex-1 bg-teal-600 text-white py-4 rounded-2xl text-center font-black text-[10px] uppercase tracking-widest hover:bg-teal-700 transition-all">
                            Join Session
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($apt['status'] === 'completed'): ?>
                        <a href="view_summary.php?id=<?php echo $apt['id']; ?>" class="flex-1 bg-blue-50 text-blue-600 py-4 rounded-2xl text-center font-black text-[10px] uppercase tracking-widest hover:bg-blue-100 transition-all">
                            View Summary
                        </a>
                    <?php endif; ?>

                    <?php if (in_array($apt['status'], ['pending', 'confirmed'])): ?>
                        <form method="POST" onsubmit="return confirm('Cancel this appointment?')" class="flex-1">
                            <input type="hidden" name="cancel_id" value="<?php echo $apt['id']; ?>">
                            <button class="w-full bg-red-50 text-red-600 py-4 rounded-2xl text-center font-black text-[10px] uppercase tracking-widest hover:bg-red-100 transition-all">
                                Cancel
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($appointments)): ?>
            <div class="col-span-full py-24 text-center bg-white border border-dashed border-slate-200 rounded-[3rem]">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="calendar-x" class="w-10 h-10 text-slate-200"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 uppercase">No Appointments Found</h3>
                <p class="text-slate-400 text-sm font-medium mt-2">Try switching tabs or book a new session.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once 'components/footer.php'; ?>
