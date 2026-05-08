<?php
// doctor/appointments.php — Clean rebuild
require_once '../core/init.php';
Auth::protect(['Clinic Admin', 'Doctor', 'Receptionist']);

$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$view = $_GET['view'] ?? 'all';

// ── Role-Based Scoping ──────────────────────────────────
// Doctors see ONLY their own appointments
// Clinic Admin & Receptionist see ALL, with optional doctor filter
$is_doctor_only = ($user_role === 'Doctor');
$filter_doctor_id = $_GET['doctor'] ?? '';

if ($is_doctor_only) {
    // Doctors always see only their own — ignore filter param
    $scope_sql = " AND a.doctor_id = ?";
    $scope_params = [$user_id];
} elseif ($filter_doctor_id) {
    // Admin/Receptionist filtering by a specific doctor
    $scope_sql = " AND a.doctor_id = ?";
    $scope_params = [(int)$filter_doctor_id];
} else {
    // Admin/Receptionist: see all
    $scope_sql = "";
    $scope_params = [];
}

// Fetch doctor list for filter dropdown (Admin/Receptionist only)
$doctor_list = [];
if (!$is_doctor_only) {
    $doc_stmt = $db->prepare("SELECT id, name FROM users WHERE clinic_id = ? AND role_id IN (SELECT id FROM roles WHERE name IN ('Doctor', 'Clinic Admin')) AND deleted_at IS NULL ORDER BY name ASC");
    $doc_stmt->execute([$clinic_id]);
    $doctor_list = $doc_stmt->fetchAll();
}

if (isset($_GET['approve'])) {
    $apt_id = $_GET['approve'];
    $db->prepare("UPDATE appointments SET status = 'confirmed' WHERE id = ? AND clinic_id = ?")->execute([$apt_id, $clinic_id]);
    
    // Notify Patient
    $apt = $db->prepare("SELECT patient_id, date_time FROM appointments WHERE id = ?");
    $apt->execute([$apt_id]);
    $res = $apt->fetch();
    $dt = date('d M, Y \a\t h:i A', strtotime($res['date_time']));
    $doc_name = $_SESSION['user_name'];
    
    notify(
        $res['patient_id'],
        "Appointment Confirmed",
        "Your appointment with Dr. $doc_name for $dt has been confirmed.",
        "appointment",
        "appointments.php"
    );

    header('Location: appointments.php?view=' . $view . ($filter_doctor_id ? '&doctor=' . $filter_doctor_id : ''));
    exit;
}
if (isset($_GET['reject'])) {
    $apt_id = $_GET['reject'];
    $db->prepare("UPDATE appointments SET status = 'cancelled' WHERE id = ? AND clinic_id = ?")->execute([$apt_id, $clinic_id]);
    
    // Notify Patient
    $apt = $db->prepare("SELECT patient_id, date_time FROM appointments WHERE id = ?");
    $apt->execute([$apt_id]);
    $res = $apt->fetch();
    $dt = date('d M, Y \a\t h:i A', strtotime($res['date_time']));
    $doc_name = $_SESSION['user_name'];
    
    notify(
        $res['patient_id'],
        "Appointment Cancelled",
        "Your appointment with Dr. $doc_name for $dt has been cancelled/rejected.",
        "appointment",
        "appointments.php"
    );

    header('Location: appointments.php?view=' . $view . ($filter_doctor_id ? '&doctor=' . $filter_doctor_id : ''));
    exit;
}

// ── Stats for Today (scoped) ──
$stats_query = "
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_requests,
        COUNT(CASE WHEN status IN ('confirmed', 'in_progress') THEN 1 END) as confirmed
    FROM appointments a
    WHERE a.clinic_id = ? AND DATE(a.date_time) = CURDATE()
    $scope_sql
";
$stats_stmt = $db->prepare($stats_query);
$stats_stmt->execute(array_merge([$clinic_id], $scope_params));
$stats = $stats_stmt->fetch();

// ── Fetch Appointments with Search (scoped) ──
$search = $_GET['search'] ?? '';
$query = "
    SELECT a.*, p.name as patient_name, d.name as doctor_name, pp.id_no as patient_id_no
    FROM appointments a
    JOIN users p ON a.patient_id = p.id
    JOIN users d ON a.doctor_id = d.id
    LEFT JOIN patient_profiles pp ON p.id = pp.user_id
    WHERE a.clinic_id = ?
    $scope_sql
";
$params = array_merge([$clinic_id], $scope_params);

if ($search) {
    $query .= " AND (p.name LIKE ? OR pp.id_no LIKE ? OR p.id LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($view === 'today') {
    $query .= " AND DATE(a.date_time) = CURDATE()";
} elseif ($view === 'pending') {
    $query .= " AND a.status = 'pending'";
} elseif ($view === 'upcoming') {
    $query .= " AND DATE(a.date_time) > CURDATE() AND a.status NOT IN ('cancelled', 'completed')";
}

$query .= " ORDER BY a.date_time ASC";
$stmt = $db->prepare($query);
$stmt->execute($params);
$appointments = $stmt->fetchAll();

// ── Overview Stats (scoped) ──
$all_stats_query = "
    SELECT 
        COUNT(*) as total,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled
    FROM appointments a
    WHERE a.clinic_id = ?
    $scope_sql
";
$all_stats = $db->prepare($all_stats_query);
$all_stats->execute(array_merge([$clinic_id], $scope_params));
$as = $all_stats->fetch();

require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="space-y-10 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <?php if ($is_doctor_only): ?>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">My Schedule</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Your patient bookings and consultations.</p>
            <?php else: ?>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Clinical Schedule</h2>
                <p class="text-slate-400 text-sm font-medium mt-1">Manage patient bookings and consultations across all doctors.</p>
            <?php endif; ?>
        </div>
        <?php if (!$is_doctor_only && !empty($doctor_list)): ?>
        <!-- Doctor Filter Dropdown -->
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="view" value="<?php echo e($view); ?>">
                <div class="relative">
                    <select name="doctor" onchange="this.form.submit()" 
                        class="appearance-none bg-white border border-slate-200 pl-10 pr-10 py-3 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all shadow-sm cursor-pointer">
                        <option value="">All Doctors</option>
                        <?php foreach ($doctor_list as $dl): ?>
                            <option value="<?php echo $dl['id']; ?>" <?php echo $filter_doctor_id == $dl['id'] ? 'selected' : ''; ?>>
                                Dr. <?php echo e($dl['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <i data-lucide="stethoscope" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                </div>
                <?php if ($filter_doctor_id): ?>
                    <a href="?view=<?php echo e($view); ?>" class="w-9 h-9 bg-red-50 text-red-400 hover:text-red-600 rounded-xl flex items-center justify-center transition-all border border-red-100" title="Clear filter">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <?php
    // Build the doctor param string for tab links
    $doc_param = $filter_doctor_id ? '&doctor=' . urlencode($filter_doctor_id) : '';
    ?>

    <!-- Navigation & Search -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6" x-data="{ search: '<?php echo e($search); ?>' }">
        <div class="flex flex-wrap items-center gap-2 p-2 bg-slate-100/50 w-fit rounded-[1.5rem] border border-slate-100">
            <a href="?view=all<?php echo $doc_param; ?>" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'all' ? 'bg-white text-emerald-600 shadow-sm border border-emerald-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
                All
            </a>
            <a href="?view=pending<?php echo $doc_param; ?>" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'pending' ? 'bg-white text-amber-600 shadow-sm border border-amber-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
                Requests (<?php echo $stats['pending_requests'] ?? 0; ?>)
            </a>
            <a href="?view=today<?php echo $doc_param; ?>" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'today' ? 'bg-white text-emerald-600 shadow-sm border border-emerald-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
                Today's List
            </a>
            <a href="?view=upcoming<?php echo $doc_param; ?>" class="px-8 py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all <?php echo $view === 'upcoming' ? 'bg-white text-blue-600 shadow-sm border border-blue-100/50' : 'text-slate-500 hover:text-slate-700'; ?>">
                Upcoming
            </a>
        </div>

        <div class="relative max-w-md w-full">
            <form method="GET" class="relative group">
                <input type="hidden" name="view" value="<?php echo e($view); ?>">
                <i data-lucide="search" class="absolute left-6 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-emerald-500 transition-colors"></i>
                <input type="text" name="search" x-model="search" placeholder="Search by name or Patient ID..." 
                    class="w-full bg-white border border-slate-100 pl-14 pr-6 py-4 rounded-[1.5rem] text-sm font-medium focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 outline-none transition-all shadow-sm">
                <template x-if="search">
                    <button type="button" @click="search = ''; $el.closest('form').submit()" class="absolute right-4 top-1/2 -translate-y-1/2 w-8 h-8 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:text-red-500 transition-all">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </template>
            </form>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Active</p>
            <h4 class="text-3xl font-black text-slate-900 mt-2"><?php echo count($appointments); ?></h4>
        </div>
        <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Today's Completed</p>
            <h4 class="text-3xl font-black text-emerald-700 mt-2"><?php echo $stats['completed'] ?? 0; ?></h4>
        </div>
        <div class="bg-amber-500 p-8 rounded-[2rem] shadow-xl shadow-amber-500/20">
            <p class="text-white/60 text-[10px] font-black uppercase tracking-widest">Pending Requests</p>
            <h4 class="text-3xl font-black text-white mt-2"><?php echo $stats['pending_requests'] ?? 0; ?></h4>
        </div>
        <div class="bg-slate-900 p-8 rounded-[2rem] shadow-xl shadow-slate-900/20">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Success Rate</p>
            <h4 class="text-3xl font-black text-white mt-2"><?php echo $as['total'] > 0 ? round(($as['completed'] / $as['total']) * 100) : 100; ?>%</h4>
        </div>
    </div>

    <!-- Appointment Table -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden" x-data="{ 
        showSummary: false, 
        visitData: null,
        fetchSummary(aptId) {
            this.showSummary = true;
            this.visitData = null;
            fetch(`../api/consultation.php?action=fetch&appointment_id=${aptId}`)
                .then(r => r.json())
                .then(data => {
                    this.visitData = data;
                    setTimeout(() => lucide.createIcons(), 50);
                });
        }
    }">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-8 py-8">Patient</th>
                        <th class="px-6 py-8">Schedule</th>
                        <th class="px-6 py-8">Consultant</th>
                        <th class="px-6 py-8">Status</th>
                        <th class="px-6 py-8">Consultation</th>
                        <th class="px-8 py-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (empty($appointments)): ?>
                        <tr>
                            <td colspan="5" class="px-10 py-16 text-center text-slate-400 font-bold text-sm">No appointments found for this view.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($appointments as $app): ?>
                        <?php
                        $status = strtolower($app['status'] ?? 'pending');
                        $badge = [
                            'pending'     => 'bg-amber-50 text-amber-600 border-amber-100',
                            'confirmed'   => 'bg-blue-50 text-blue-600 border-blue-100',
                            'in_progress' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'completed'   => 'bg-teal-50 text-teal-700 border-teal-100',
                            'cancelled'   => 'bg-red-50 text-red-500 border-red-100',
                        ][$status] ?? 'bg-slate-100 text-slate-500 border-slate-200';
                        $label = [
                            'pending' => 'Pending', 'confirmed' => 'Confirmed',
                            'in_progress' => 'In Progress', 'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ][$status] ?? ($status ? ucfirst($status) : 'Missing Status');
                        ?>
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center font-black text-sm border border-slate-100 group-hover:bg-emerald-50 group-hover:text-emerald-600 group-hover:border-emerald-100 transition-all">
                                        <?php echo strtoupper(substr($app['patient_name'], 0, 1)); ?>
                                    </div>
                                    <div class="whitespace-nowrap">
                                        <p class="text-sm font-black text-slate-900"><?php echo e($app['patient_name']); ?></p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">ID: #<?php echo e($app['patient_id_no'] ?? 'CMS-'.str_pad($app['patient_id'], 5, '0', STR_PAD_LEFT)); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-8 whitespace-nowrap">
                                <p class="text-sm font-black text-slate-900"><?php echo date('h:i A', strtotime($app['date_time'])); ?></p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"><?php echo date('D, M d', strtotime($app['date_time'])); ?></p>
                            </td>
                            <td class="px-6 py-8 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                                    <p class="text-xs font-black text-slate-700"><?php echo e($app['doctor_name']); ?></p>
                                </div>
                            </td>
                            <td class="px-6 py-8">
                                <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border whitespace-nowrap <?php echo $badge; ?>">
                                    <?php echo $label; ?>
                                </span>
                            </td>
                            <td class="px-6 py-8">
                                <?php if ($status === 'completed'): ?>
                                    <button @click="fetchSummary(<?php echo $app['id']; ?>)" class="flex items-center gap-2 px-4 py-2 bg-slate-50 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 rounded-xl border border-slate-100 hover:border-emerald-100 transition-all group/btn whitespace-nowrap">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                        <span class="text-[10px] font-black uppercase tracking-widest">View Summary</span>
                                    </button>
                                <?php else: ?>
                                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest italic whitespace-nowrap">Not Available</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-8 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <?php if ($status === 'pending'): ?>
                                        <a href="?approve=<?php echo $app['id']; ?>&view=<?php echo $view; ?>" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/10 whitespace-nowrap">Approve</a>
                                        <a href="?reject=<?php echo $app['id']; ?>&view=<?php echo $view; ?>" class="bg-red-50 text-red-600 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-all whitespace-nowrap" onclick="return confirm('Reject this appointment?')">Reject</a>
 
                                    <?php elseif ($status === 'confirmed'): ?>
                                        <a href="session.php?id=<?php echo $app['id']; ?>" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/10 inline-flex items-center gap-2 whitespace-nowrap">
                                            <i data-lucide="play" class="w-3.5 h-3.5"></i> Start Session
                                        </a>
 
                                    <?php elseif ($status === 'in_progress'): ?>
                                        <a href="session.php?id=<?php echo $app['id']; ?>" class="bg-teal-600 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/10 inline-flex items-center gap-2 whitespace-nowrap">
                                            <i data-lucide="video" class="w-3.5 h-3.5"></i> Resume
                                        </a>
 
                                    <?php elseif ($status === 'completed'): ?>
                                        <span class="text-slate-400 text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5 whitespace-nowrap">
                                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Done
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal: Consultation Summary -->
        <div x-show="showSummary" class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-slate-900/60 backdrop-blur-md" x-cloak>
            <div class="bg-white w-full max-w-3xl rounded-[3rem] shadow-2xl overflow-hidden border-[8px] border-white" @click.away="showSummary = false">
                <div class="p-8 bg-slate-50/80 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-600/20">
                            <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">Consultation Summary</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Secure Clinical Record</p>
                        </div>
                    </div>
                    <button @click="showSummary = false" class="w-10 h-10 bg-white hover:bg-red-50 text-slate-400 hover:text-red-500 rounded-xl flex items-center justify-center transition-all border border-slate-100 shadow-sm">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <div class="p-10 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <template x-if="!visitData">
                        <div class="flex flex-col items-center justify-center py-20">
                            <div class="w-12 h-12 border-4 border-emerald-500/20 border-t-emerald-600 rounded-full animate-spin"></div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-4">Retrieving Record...</p>
                        </div>
                    </template>

                    <template x-if="visitData && !visitData.id">
                        <div class="text-center py-20">
                            <i data-lucide="alert-circle" class="w-12 h-12 text-slate-200 mx-auto mb-4"></i>
                            <p class="text-slate-400 font-bold">No clinical data found for this visit.</p>
                        </div>
                    </template>

                    <template x-if="visitData && visitData.id">
                        <div class="space-y-10" id="printable-summary">
                            <!-- Section: S & O -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">1. The Problem</h4>
                                        <div class="p-6 bg-amber-50 rounded-[1.5rem] border border-amber-100/50">
                                            <p class="text-[8px] font-black text-amber-500 uppercase mb-1">Main Issue</p>
                                            <p class="text-sm font-black text-slate-800" x-text="visitData.chief_complaint || 'No complaint recorded.'"></p>
                                            <div class="flex flex-wrap gap-1.5 mt-3">
                                                <template x-for="s in JSON.parse(visitData.symptoms_data || '[]')">
                                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-lg text-[9px] font-black uppercase" x-text="s"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Extra Details</h4>
                                        <p class="text-xs text-slate-600 leading-relaxed italic" x-text="visitData.history_illness || 'No extra details provided.'"></p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">2. Checkup (Vitals)</h4>
                                        <div class="grid grid-cols-2 gap-3">
                                            <template x-for="(val, key) in JSON.parse(visitData.vitals_data || '{}')">
                                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1" x-text="key"></p>
                                                    <p class="text-xs font-black text-slate-800" x-text="val || '-'"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Physical Notes</h4>
                                        <p class="text-xs text-slate-600 leading-relaxed italic" x-text="visitData.physical_exam || 'No observation notes.'"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Diagnosis -->
                            <div class="p-8 bg-emerald-50 rounded-[2rem] border border-emerald-100/50">
                                <h4 class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-4">3. Diagnosis</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <p class="text-[8px] font-black text-emerald-500 uppercase mb-1">Main Diagnosis</p>
                                        <p class="text-base font-black text-slate-900" x-text="visitData.diagnosis || 'Pending'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[8px] font-black text-emerald-500 uppercase mb-1">Possible Causes</p>
                                        <p class="text-xs font-bold text-slate-600" x-text="visitData.differential_diagnosis || 'None'"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Treatment -->
                            <div class="space-y-6">
                                <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest">4. Treatment</h4>
                                
                                <div x-show="visitData.prescriptions && visitData.prescriptions.length" class="overflow-hidden border border-slate-100 rounded-[2rem]">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50">
                                            <tr>
                                                <th class="px-6 py-4 font-black text-slate-400 uppercase text-[9px]">Medicine</th>
                                                <th class="px-6 py-4 font-black text-slate-400 uppercase text-[9px]">Dosage</th>
                                                <th class="px-6 py-4 font-black text-slate-400 uppercase text-[9px]">Freq</th>
                                                <th class="px-6 py-4 font-black text-slate-400 uppercase text-[9px]">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            <template x-for="p in visitData.prescriptions">
                                                <tr>
                                                    <td class="px-6 py-4">
                                                        <p class="font-black text-slate-800" x-text="p.medicine_name"></p>
                                                        <p class="text-[10px] text-slate-400 italic" x-text="p.instructions"></p>
                                                    </td>
                                                    <td class="px-6 py-4 font-bold text-slate-600" x-text="p.dosage"></td>
                                                    <td class="px-6 py-4 font-bold text-slate-600" x-text="p.frequency"></td>
                                                    <td class="px-6 py-4 font-bold text-slate-600" x-text="p.duration"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="p-6 bg-slate-50 rounded-[1.5rem]">
                                        <p class="text-[8px] font-black text-slate-400 uppercase mb-3">Advice & Lifestyle</p>
                                        <p class="text-xs font-medium text-slate-600 leading-relaxed" x-text="visitData.advice || 'General care advised.'"></p>
                                    </div>
                                    <div class="p-6 bg-slate-900 rounded-[1.5rem] text-white">
                                        <p class="text-[8px] font-black text-white/40 uppercase mb-3">Follow-up Info</p>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-[10px] font-black uppercase" x-text="'Case: ' + visitData.severity"></p>
                                                <p class="text-[9px] text-white/60" x-text="visitData.admission_needed ? 'Admission Advised' : 'Home Recovery'"></p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[8px] font-black text-white/40 uppercase mb-1">Date</p>
                                                <p class="text-xs font-black text-emerald-400" x-text="visitData.follow_up_date || 'TBD'"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-8 border-t border-slate-100">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-300"></i>
                                    <span class="text-[10px] font-bold text-slate-400" x-text="'Generated: ' + new Date(visitData.completed_at).toLocaleString()"></span>
                                </div>
                                <div class="flex gap-3">
                                    <button class="px-6 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-slate-200 transition-all">
                                        <i data-lucide="share-2" class="w-3.5 h-3.5"></i> Share
                                    </button>
                                    <button @click="
                                        const element = document.getElementById('printable-summary');
                                        const opt = {
                                            margin: 10,
                                            filename: 'Consultation-Summary-' + visitData.id + '.pdf',
                                            image: { type: 'jpeg', quality: 0.98 },
                                            html2canvas: { scale: 2, useCORS: true },
                                            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                                        };
                                        html2pdf().set(opt).from(element).save();
                                    " class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-slate-900/10 flex items-center gap-2 hover:bg-slate-800 transition-all">
                                        <i data-lucide="download" class="w-3.5 h-3.5"></i> Download PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() { lucide.createIcons(); });
</script>

<?php require_once 'components/footer.php'; ?>
