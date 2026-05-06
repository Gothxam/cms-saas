<?php
// patient/view_summary.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];
$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    header("Location: appointments.php");
    exit;
}

// Get visit details for this specific appointment
$stmt = $db->prepare("
    SELECT v.*, d.name as doctor_name, COALESCE(dp.specialization, 'General Physician') as specialization
    FROM visits v
    JOIN users d ON v.doctor_id = d.id
    LEFT JOIN doctor_profiles dp ON d.id = dp.user_id
    WHERE v.appointment_id = ? AND v.patient_id = ?
");
$stmt->execute([$appointment_id, $patient_id]);
$visit = $stmt->fetch();

if (!$visit) {
    header("Location: appointments.php?error=no_summary");
    exit;
}

$p_stmt = $db->prepare("SELECT * FROM prescriptions WHERE visit_id = ?");
$p_stmt->execute([$visit['id']]);
$prescriptions = $p_stmt->fetchAll();

$page_title = "Consultation Summary";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-4xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <a href="appointments.php?tab=completed" class="flex items-center gap-2 text-slate-400 hover:text-slate-900 transition-colors text-[10px] font-black uppercase tracking-widest">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to History
        </a>
        <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-teal-600 transition-all flex items-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i> Save PDF
        </button>
    </div>

    <!-- Summary Content -->
    <div id="printable-area" class="bg-white p-12 rounded-[3.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
        <!-- Watermark -->
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-slate-50 rounded-full"></div>
        
        <div class="relative z-10">
            <!-- Doctor & Date -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12 border-b border-slate-50 pb-12">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-teal-600 text-white rounded-3xl flex items-center justify-center text-2xl font-black shadow-xl shadow-teal-600/20">
                        <?php echo strtoupper(substr($visit['doctor_name'], 0, 1)); ?>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Dr. <?php echo e($visit['doctor_name']); ?></h3>
                        <p class="text-teal-600 text-[10px] font-black uppercase tracking-widest mt-1"><?php echo e($visit['specialization']); ?></p>
                    </div>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Visit Date</p>
                    <p class="text-lg font-black text-slate-900 mt-1"><?php echo date('F j, Y', strtotime($visit['completed_at'])); ?></p>
                </div>
            </div>

            <!-- Vitals Grid -->
            <?php if ($visit['vitals_data']): ?>
                <?php $vitals = json_decode($visit['vitals_data'], true); ?>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-12">
                    <div class="p-5 bg-orange-50/30 rounded-3xl border border-orange-100/50 text-center">
                        <p class="text-[9px] font-black text-orange-600 uppercase tracking-widest mb-1">Temp</p>
                        <p class="text-sm font-black text-slate-900"><?php echo e($vitals['temperature'] ?? '--'); ?>°F</p>
                    </div>
                    <div class="p-5 bg-blue-50/30 rounded-3xl border border-blue-100/50 text-center">
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">BP</p>
                        <p class="text-sm font-black text-slate-900"><?php echo e($vitals['bp'] ?? '--'); ?></p>
                    </div>
                    <div class="p-5 bg-red-50/30 rounded-3xl border border-red-100/50 text-center">
                        <p class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-1">Pulse</p>
                        <p class="text-sm font-black text-slate-900"><?php echo e($vitals['pulse'] ?? '--'); ?> <span class="text-[8px]">bpm</span></p>
                    </div>
                    <div class="p-5 bg-teal-50/30 rounded-3xl border border-teal-100/50 text-center">
                        <p class="text-[9px] font-black text-teal-600 uppercase tracking-widest mb-1">SpO2</p>
                        <p class="text-sm font-black text-slate-900"><?php echo e($vitals['spo2'] ?? '--'); ?>%</p>
                    </div>
                    <div class="p-5 bg-slate-50/50 rounded-3xl border border-slate-100 text-center">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Weight</p>
                        <p class="text-sm font-black text-slate-900"><?php echo e($vitals['weight'] ?? '--'); ?> <span class="text-[8px]">kg</span></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
                <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Patient Concern</p>
                    <p class="text-sm font-bold text-slate-800 leading-relaxed mb-4"><?php echo e($visit['chief_complaint'] ?: 'General health consultation.'); ?></p>
                    
                    <?php if ($visit['symptoms_data']): ?>
                        <?php $symptoms = json_decode($visit['symptoms_data'], true); ?>
                        <div class="flex flex-wrap gap-2 pt-4 border-t border-slate-100">
                            <?php foreach ($symptoms as $s): ?>
                                <span class="px-3 py-1 bg-white text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-100"><?php echo e($s); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-8 bg-teal-50/50 rounded-[2.5rem] border border-teal-100/50">
                    <p class="text-[10px] font-black text-teal-600 uppercase tracking-widest mb-4">Doctor Advice</p>
                    <p class="text-sm font-bold text-slate-800 leading-relaxed"><?php echo e($visit['advice'] ?: 'Follow general health guidelines.'); ?></p>
                </div>
            </div>

            <!-- Full Details -->
            <div class="space-y-12">
            <!-- Diagnosis & Assessment -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="space-y-10">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="activity" class="w-4 h-4"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Clinical Diagnosis</h4>
                        </div>
                        <p class="text-xs font-medium text-slate-600 leading-relaxed ml-11">
                            <?php echo e($visit['diagnosis'] ?: 'Observation completed. Condition stable.'); ?>
                        </p>
                    </div>

                    <?php if ($visit['differential_diagnosis']): ?>
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="zoom-in" class="w-4 h-4"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Differential Diagnosis</h4>
                        </div>
                        <p class="text-xs font-medium text-slate-600 leading-relaxed ml-11">
                            <?php echo e($visit['differential_diagnosis']); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-10">
                    <?php if ($visit['history_illness']): ?>
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-slate-100 text-slate-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="history" class="w-4 h-4"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">History of Illness</h4>
                        </div>
                        <p class="text-xs font-medium text-slate-600 leading-relaxed ml-11">
                            <?php echo e($visit['history_illness']); ?>
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php if ($visit['physical_exam']): ?>
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="stethoscope" class="w-4 h-4"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Physical Notes</h4>
                        </div>
                        <p class="text-xs font-medium text-slate-600 leading-relaxed ml-11">
                            <?php echo e($visit['physical_exam']); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Follow-up & Plan -->
            <div class="mt-12 pt-12 border-t border-slate-50 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Severity Level</p>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full <?php echo $visit['severity'] === 'severe' ? 'bg-red-500' : ($visit['severity'] === 'moderate' ? 'bg-amber-500' : 'bg-teal-500'); ?>"></div>
                        <p class="text-xs font-black text-slate-900 uppercase tracking-widest"><?php echo e($visit['severity'] ?: 'Mild'); ?></p>
                    </div>
                </div>

                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Follow-up Date</p>
                    <p class="text-xs font-black text-slate-900"><?php echo $visit['follow_up_date'] ? date('F j, Y', strtotime($visit['follow_up_date'])) : 'As needed'; ?></p>
                </div>

                <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Admission Needed</p>
                    <p class="text-xs font-black text-slate-900 uppercase tracking-widest"><?php echo $visit['admission_needed'] ? 'Yes, Recommended' : 'No'; ?></p>
                </div>
            </div>

                <!-- Prescription -->
                <?php if (!empty($prescriptions)): ?>
                    <div>
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="pill" class="w-4 h-4"></i>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 uppercase tracking-widest">Prescribed Medicines</h4>
                        </div>
                        
                        <div class="ml-11 bg-slate-50/50 rounded-3xl border border-slate-100 overflow-hidden">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="border-b border-slate-100">
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Medicine</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Dosage</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Timing</th>
                                        <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Duration</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php foreach ($prescriptions as $p): ?>
                                        <tr>
                                            <td class="px-6 py-4 text-xs font-black text-slate-800"><?php echo e($p['medicine_name']); ?></td>
                                            <td class="px-6 py-4 text-xs font-bold text-slate-600"><?php echo e($p['dosage']); ?></td>
                                            <td class="px-6 py-4 text-xs font-medium text-slate-400"><?php echo e($p['frequency']); ?></td>
                                            <td class="px-6 py-4 text-xs font-black text-blue-600"><?php echo e($p['duration'] ?: '--'); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?php require_once 'components/footer.php'; ?>
