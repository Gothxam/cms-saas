<?php
// patient/payments.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

// Get payment history
$stmt = $db->prepare("
    SELECT a.*, d.name as doctor_name, COALESCE(dp.video_fee, 500) as fee
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    LEFT JOIN doctor_profiles dp ON d.id = dp.user_id
    WHERE a.patient_id = ?
    ORDER BY a.date_time DESC
");
$stmt->execute([$patient_id]);
$payments = $stmt->fetchAll();

$page_title = "My Payments";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Payments</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Track your consultation charges and download invoices.</p>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Description</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Date</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-6 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($payments as $p): ?>
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900">Consultation with Dr. <?php echo e($p['doctor_name']); ?></p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"><?php echo e($p['type'] ?? 'Video'); ?> Session</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900"><?php echo date('M j, Y', strtotime($p['date_time'])); ?></p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-900">₹<?php echo number_format($p['fee'], 2); ?></p>
                            </td>
                            <td class="px-8 py-6">
                                <?php 
                                    $p_status = $p['payment_status'] ?? 'unpaid';
                                    $status_class = match($p_status) {
                                        'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'pending', 'unpaid' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100'
                                    };
                                ?>
                                <span class="px-3 py-1 <?php echo $status_class; ?> border rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    <?php echo $p_status; ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <?php if ($p_status === 'paid'): ?>
                                    <button class="p-3 bg-slate-50 text-slate-400 rounded-xl hover:bg-teal-50 hover:text-teal-600 transition-all">
                                        <i data-lucide="download" class="w-4 h-4"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="text-[10px] font-black text-teal-600 uppercase tracking-widest hover:underline">Pay Now</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($payments)): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i data-lucide="receipt" class="w-8 h-8 text-slate-200"></i>
                                </div>
                                <p class="text-slate-400 font-bold text-sm">No payment history found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once 'components/footer.php'; ?>
