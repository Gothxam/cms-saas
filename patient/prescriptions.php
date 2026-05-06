<?php
// patient/prescriptions.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];

// Get all prescriptions with doctor and clinic details
$stmt = $db->prepare("
    SELECT p.*, d.name as doctor_name, dp.clinic_name, dp.specialization, v.completed_at
    FROM prescriptions p
    JOIN visits v ON p.visit_id = v.id
    JOIN users d ON v.doctor_id = d.id
    LEFT JOIN doctor_profiles dp ON d.id = dp.user_id
    WHERE v.patient_id = ?
    ORDER BY v.completed_at DESC
");
$stmt->execute([$patient_id]);
$all_prescriptions = $stmt->fetchAll();

// Group by visit for cleaner UI
$grouped = [];
foreach ($all_prescriptions as $p) {
    $date = date('Y-m-d', strtotime($p['completed_at']));
    $key = $p['visit_id'] . '_' . $date;
    if (!isset($grouped[$key])) {
        $grouped[$key] = [
            'id' => $p['visit_id'],
            'doctor' => $p['doctor_name'],
            'clinic' => $p['clinic_name'] ?: 'Clinical Medical Center',
            'specialization' => $p['specialization'] ?: 'General Physician',
            'date' => $p['completed_at'],
            'medicines' => []
        ];
    }
    $grouped[$key]['medicines'][] = $p;
}

$page_title = "My Prescriptions";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- html2pdf dependency -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="max-w-6xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Prescriptions</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">View and download your official clinical prescriptions.</p>
        </div>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="py-24 text-center bg-white border border-dashed border-slate-200 rounded-[3rem]">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="pill" class="w-10 h-10 text-slate-200"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900 uppercase tracking-widest">No Prescriptions Found</h3>
            <p class="text-slate-400 text-sm font-medium mt-2">Your prescriptions will appear here after your consultations.</p>
        </div>
    <?php else: ?>
        <div class="space-y-8">
            <?php foreach ($grouped as $key => $visit): ?>
                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center">
                                    <i data-lucide="clipboard-list" class="w-7 h-7"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-slate-900">Dr. <?php echo e($visit['doctor']); ?></h4>
                                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1"><?php echo e($visit['clinic']); ?> • <?php echo date('F j, Y', strtotime($visit['date'])); ?></p>
                                </div>
                            </div>
                            <button onclick="downloadPrescription('presc-<?php echo $visit['id']; ?>', 'Dr-<?php echo e($visit['doctor']); ?>-Prescription')" class="bg-slate-900 text-white px-6 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-teal-600 hover:shadow-teal-600/20 transition-all flex items-center gap-2">
                                <i data-lucide="download" class="w-4 h-4"></i> Download PDF
                            </button>
                        </div>

                        <!-- Card View (Table) -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                        <tr class="text-left border-b border-slate-100">
                                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Medicine</th>
                                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dosage</th>
                                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Timing</th>
                                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Duration</th>
                                            <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Instructions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        <?php foreach ($visit['medicines'] as $med): ?>
                                            <tr>
                                                <td class="py-6">
                                                    <p class="text-sm font-black text-slate-900"><?php echo e($med['medicine_name']); ?></p>
                                                </td>
                                                <td class="py-6">
                                                    <p class="text-xs font-bold text-slate-600"><?php echo e($med['dosage']); ?></p>
                                                </td>
                                                <td class="py-6">
                                                    <p class="text-xs font-bold text-slate-600"><?php echo e($med['frequency']); ?></p>
                                                </td>
                                                <td class="py-6">
                                                    <p class="text-xs font-black text-blue-600"><?php echo e($med['duration'] ?: '--'); ?></p>
                                                </td>
                                                <td class="py-6">
                                                    <p class="text-xs font-medium text-slate-400 italic"><?php echo e($med['instructions'] ?: 'Follow as prescribed.'); ?></p>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Hidden Professional Print Template -->
                        <div id="presc-<?php echo $visit['id']; ?>" class="hidden">
                            <div style="padding: 60px; font-family: sans-serif; color: #1e293b;">
                                <!-- Header -->
                                <div style="display: flex; justify-content: space-between; border-bottom: 4px solid #0d9488; padding-bottom: 30px; margin-bottom: 40px;">
                                    <div>
                                        <h1 style="margin: 0; font-size: 28px; font-weight: 900; text-transform: uppercase; color: #0d9488;"><?php echo e($visit['clinic']); ?></h1>
                                        <p style="margin: 5px 0 0; font-size: 14px; font-weight: 700; color: #64748b;"><?php echo e($visit['specialization']); ?></p>
                                    </div>
                                    <div style="text-align: right;">
                                        <h2 style="margin: 0; font-size: 18px; font-weight: 900;">Dr. <?php echo e($visit['doctor']); ?></h2>
                                        <p style="margin: 5px 0 0; font-size: 12px; font-weight: 700; color: #94a3b8;">Ref ID: #<?php echo $visit['id']; ?></p>
                                    </div>
                                </div>

                                <!-- Patient Info -->
                                <div style="display: flex; gap: 40px; margin-bottom: 50px; background: #f8fafc; padding: 25px; border-radius: 20px;">
                                    <div>
                                        <p style="margin: 0; font-size: 10px; font-weight: 900; text-transform: uppercase; color: #94a3b8; letter-spacing: 1px;">Patient Name</p>
                                        <p style="margin: 5px 0 0; font-size: 14px; font-weight: 800;"><?php echo e($patient_name); ?></p>
                                    </div>
                                    <div>
                                        <p style="margin: 0; font-size: 10px; font-weight: 900; text-transform: uppercase; color: #94a3b8; letter-spacing: 1px;">Date</p>
                                        <p style="margin: 5px 0 0; font-size: 14px; font-weight: 800;"><?php echo date('d M, Y', strtotime($visit['date'])); ?></p>
                                    </div>
                                </div>



                                <!-- Medicines Table -->
                                <table style="width: 100%; border-collapse: collapse; margin-bottom: 60px;">
                                    <thead>
                                        <tr style="border-bottom: 1px solid #e2e8f0; text-align: left;">
                                            <th style="padding: 15px 0; font-size: 11px; font-weight: 900; text-transform: uppercase; color: #94a3b8;">Medicine Name</th>
                                            <th style="padding: 15px 0; font-size: 11px; font-weight: 900; text-transform: uppercase; color: #94a3b8;">Dosage</th>
                                            <th style="padding: 15px 0; font-size: 11px; font-weight: 900; text-transform: uppercase; color: #94a3b8;">Timing</th>
                                            <th style="padding: 15px 0; font-size: 11px; font-weight: 900; text-transform: uppercase; color: #94a3b8;">Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($visit['medicines'] as $med): ?>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td style="padding: 20px 0;">
                                                    <p style="margin: 0; font-size: 14px; font-weight: 800;"><?php echo e($med['medicine_name']); ?></p>
                                                    <p style="margin: 4px 0 0; font-size: 11px; color: #64748b; font-style: italic;"><?php echo e($med['instructions']); ?></p>
                                                </td>
                                                <td style="padding: 20px 0; font-size: 13px; font-weight: 700;"><?php echo e($med['dosage']); ?></td>
                                                <td style="padding: 20px 0; font-size: 13px; font-weight: 700;"><?php echo e($med['frequency']); ?></td>
                                                <td style="padding: 20px 0; font-size: 13px; font-weight: 900; color: #0d9488;"><?php echo e($med['duration'] ?: '--'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <!-- Footer -->
                                <div style="margin-top: 100px; border-top: 1px solid #e2e8f0; padding-top: 30px; display: flex; justify-content: space-between;">
                                    <div>
                                        <p style="margin: 0; font-size: 10px; color: #94a3b8;">This is a digitally signed prescription generated via MedOS Portal.</p>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="width: 150px; border-bottom: 1px dashed #cbd5e1; margin-bottom: 10px;"></div>
                                        <p style="margin: 0; font-size: 12px; font-weight: 900;">Authorized Signature</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
function downloadPrescription(elementId, filename) {
    const element = document.getElementById(elementId);
    element.classList.remove('hidden'); // Temporarily show to capture
    
    const opt = {
        margin: 0,
        filename: filename + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save().then(() => {
        element.classList.add('hidden'); // Hide again after capture
    });
}
</script>

<?php require_once 'components/footer.php'; ?>
