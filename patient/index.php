<?php
// patient/index.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

// Get patient health profile
$profile_stmt = $db->prepare("SELECT * FROM patient_profiles WHERE user_id = ?");
$profile_stmt->execute([$patient_id]);
$profile = $profile_stmt->fetch();

// Get next upcoming appointment
$upcoming_stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name, COALESCE(dp.specialization, 'General Physician') as specialization
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    LEFT JOIN doctor_profiles dp ON d.id = dp.user_id
    WHERE a.patient_id = ? AND a.date_time > NOW() AND a.status IN ('pending', 'confirmed', 'in_progress')
    ORDER BY a.date_time ASC
    LIMIT 1
");
$upcoming_stmt->execute([$patient_id]);
$upcoming = $upcoming_stmt->fetch();

// Get latest completed visit summary
$visit_stmt = $db->prepare("
    SELECT v.*, d.name as doctor_name 
    FROM visits v 
    JOIN users d ON v.doctor_id = d.id 
    WHERE v.patient_id = ? AND v.status = 'completed' 
    ORDER BY v.completed_at DESC 
    LIMIT 1
");
$visit_stmt->execute([$patient_id]);
$latest_visit = $visit_stmt->fetch();

$latest_prescriptions = [];
if ($latest_visit) {
    $p_stmt = $db->prepare("SELECT * FROM prescriptions WHERE visit_id = ? LIMIT 5");
    $p_stmt->execute([$latest_visit['id']]);
    $latest_prescriptions = $p_stmt->fetchAll();
}

// Get latest documents
$doc_stmt = $db->prepare("SELECT * FROM patient_documents WHERE patient_id = ? ORDER BY created_at DESC LIMIT 3");
$doc_stmt->execute([$patient_id]);
$recent_docs = $doc_stmt->fetchAll();

$page_title = "My Health Dashboard";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Hello, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?>! 👋</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Here is a quick look at your health status and upcoming visits.</p>
        </div>
        <div class="flex gap-3">
            <a href="book_doctor.php" class="bg-teal-600 text-white px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Book Appointment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Main Content (Left) -->
        <div class="lg:col-span-8 space-y-10">
            
            <!-- Health Summary Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Allergies -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Allergies</h3>
                    </div>
                    <?php if ($profile && $profile['allergies']): ?>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach(explode(',', $profile['allergies']) as $allergy): ?>
                                <span class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-widest"><?php echo trim($allergy); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-slate-400 text-xs font-medium italic">No known allergies recorded.</p>
                    <?php endif; ?>
                </div>

                <!-- Chronic Conditions -->
                <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="activity" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Conditions</h3>
                    </div>
                    <?php if ($profile && $profile['chronic_conditions']): ?>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach(explode(',', $profile['chronic_conditions']) as $condition): ?>
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest"><?php echo trim($condition); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-slate-400 text-xs font-medium italic">No chronic conditions recorded.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Activity: Latest Consultation -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-slate-50 rounded-full"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-black text-slate-900">Latest Consultation</h3>
                        <a href="appointments.php" class="text-teal-600 text-[10px] font-black uppercase tracking-widest hover:underline">View All</a>
                    </div>

                    <?php if ($latest_visit): ?>
                        <div class="flex items-start gap-6 mb-8">
                            <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center shrink-0">
                                <i data-lucide="clipboard-list" class="w-7 h-7"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Consultation with</p>
                                <h4 class="text-lg font-black text-slate-900 mt-1">Dr. <?php echo e($latest_visit['doctor_name']); ?></h4>
                                <p class="text-slate-400 text-xs font-medium mt-1">Completed on <?php echo date('F j, Y', strtotime($latest_visit['completed_at'])); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-50">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Main Issue & Advice</p>
                                <p class="text-xs font-bold text-slate-800 leading-relaxed"><?php echo e($latest_visit['chief_complaint'] ?: 'General checkup'); ?></p>
                                <p class="text-xs text-slate-500 mt-2 italic"><?php echo e($latest_visit['advice'] ?: 'No specific advice recorded.'); ?></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Prescribed Medicines</p>
                                <?php if (!empty($latest_prescriptions)): ?>
                                    <div class="space-y-2">
                                        <?php foreach ($latest_prescriptions as $p): ?>
                                            <div class="flex items-center gap-3">
                                                <div class="w-1.5 h-1.5 bg-teal-500 rounded-full"></div>
                                                <p class="text-xs font-bold text-slate-800"><?php echo e($p['medicine_name']); ?> <span class="text-slate-400 font-medium">(<?php echo e($p['dosage']); ?>)</span></p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-slate-400 text-xs font-medium italic">No medicines prescribed.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="py-12 text-center bg-slate-50/50 rounded-[2rem] border border-dashed border-slate-200">
                            <i data-lucide="history" class="w-10 h-10 text-slate-200 mx-auto mb-4"></i>
                            <p class="text-slate-400 text-sm font-bold">No consultation history available yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Sidebar (Right) -->
        <div class="lg:col-span-4 space-y-10">
            
            <!-- Next Appointment -->
            <div class="bg-slate-900 p-8 rounded-[3rem] text-white shadow-2xl shadow-slate-900/20 relative overflow-hidden group">
                <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-teal-500/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                            <i data-lucide="calendar" class="w-5 h-5 text-teal-400"></i>
                        </div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-white/50">Next Appointment</h3>
                    </div>

                    <?php if ($upcoming): ?>
                        <h4 class="text-xl font-black mb-1">Dr. <?php echo e($upcoming['doctor_name']); ?></h4>
                        <p class="text-teal-400 text-[10px] font-black uppercase tracking-widest mb-8"><?php echo e($upcoming['specialization']); ?></p>
                        
                        <div class="flex items-center gap-6 mb-8">
                            <div>
                                <p class="text-[9px] font-black text-white/30 uppercase tracking-widest mb-1">Date</p>
                                <p class="text-sm font-black text-white"><?php echo date('M j, Y', strtotime($upcoming['date_time'])); ?></p>
                            </div>
                            <div class="w-px h-8 bg-white/10"></div>
                            <div>
                                <p class="text-[9px] font-black text-white/30 uppercase tracking-widest mb-1">Time</p>
                                <p class="text-sm font-black text-white"><?php echo date('h:i A', strtotime($upcoming['date_time'])); ?></p>
                            </div>
                        </div>

                        <a href="appointments.php" class="w-full bg-white text-slate-900 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-teal-400 transition-all flex items-center justify-center gap-2">
                            View Details <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    <?php else: ?>
                        <p class="text-white/50 text-sm font-medium mb-8">No upcoming appointments scheduled.</p>
                        <a href="book_doctor.php" class="w-full bg-white/10 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-white/20 transition-all flex items-center justify-center gap-2">
                            Book Session <i data-lucide="plus" class="w-4 h-4"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-4">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2 mb-4">Quick Actions</p>
                <a href="prescriptions.php" class="flex items-center justify-between p-5 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-x-1 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition-all">
                            <i data-lucide="pill" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-black text-slate-800 tracking-tight">Medicines</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                </a>
                <a href="reports.php" class="flex items-center justify-between p-5 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-x-1 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-black text-slate-800 tracking-tight">Reports</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                </a>
                <a href="payments.php" class="flex items-center justify-between p-5 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-x-1 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                        </div>
                        <span class="text-sm font-black text-slate-800 tracking-tight">Payments</span>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                </a>
            </div>

            <!-- Latest Documents -->
            <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest">Latest Records</h3>
                    <a href="reports.php" class="text-teal-600 text-[9px] font-black uppercase tracking-widest hover:underline">All Records</a>
                </div>

                <div class="space-y-4">
                    <?php foreach ($recent_docs as $doc): ?>
                        <div class="group flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 hover:bg-white hover:border-teal-500/30 transition-all cursor-pointer">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 group-hover:text-teal-600 transition-all">
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-900 tracking-tight"><?php echo e($doc['title']); ?></p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5"><?php echo date('d M, Y', strtotime($doc['created_at'])); ?></p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-200 group-hover:text-teal-600 transition-all"></i>
                        </div>
                    <?php endforeach; ?>
                    <?php if (empty($recent_docs)): ?>
                        <p class="text-center py-6 text-slate-400 text-xs italic">No documents uploaded.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>

</div>

<?php require_once 'components/footer.php'; ?>
