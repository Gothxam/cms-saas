<?php
// patient/notifications.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

// Mock notifications (since we don't have a table yet, but we'll simulate for UI)
$notifications = [
    [
        'id' => 1,
        'title' => 'Appointment Reminder',
        'message' => 'Your consultation with Dr. mohit is scheduled for today at 09:00 AM.',
        'type' => 'reminder',
        'time' => '2 hours ago',
        'is_read' => false
    ],
    [
        'id' => 2,
        'title' => 'New Prescription',
        'message' => 'Dr. mohit has uploaded a new prescription for your recent visit.',
        'type' => 'update',
        'time' => '1 day ago',
        'is_read' => true
    ]
];

$page_title = "My Notifications";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-4xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Notifications</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Stay updated with your clinical activity.</p>
        </div>
        <button class="text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-teal-600 transition-colors">
            Clear All
        </button>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        <?php foreach ($notifications as $n): ?>
            <div class="p-6 rounded-[2.5rem] border transition-all flex items-start gap-6 <?php echo $n['is_read'] ? 'bg-white border-slate-100 opacity-60' : 'bg-white border-teal-500/20 shadow-xl shadow-teal-500/5'; ?>">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 <?php echo $n['type'] === 'reminder' ? 'bg-amber-50 text-amber-600' : 'bg-teal-50 text-teal-600'; ?>">
                    <i data-lucide="<?php echo $n['type'] === 'reminder' ? 'calendar' : 'bell'; ?>" class="w-6 h-6"></i>
                </div>
                
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-sm font-black text-slate-900"><?php echo e($n['title']); ?></h4>
                        <span class="text-[10px] font-bold text-slate-400 uppercase"><?php echo $n['time']; ?></span>
                    </div>
                    <p class="text-xs font-medium text-slate-500 leading-relaxed"><?php echo e($n['message']); ?></p>
                </div>

                <?php if (!$n['is_read']): ?>
                    <button class="w-2 h-2 bg-teal-500 rounded-full mt-2 shrink-0"></button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (empty($notifications)): ?>
            <div class="py-24 text-center bg-white border border-dashed border-slate-200 rounded-[3rem]">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="bell-off" class="w-10 h-10 text-slate-200"></i>
                </div>
                <h3 class="text-lg font-black text-slate-900 uppercase">All Caught Up</h3>
                <p class="text-slate-400 text-sm font-medium mt-2">No new notifications for you.</p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php require_once 'components/footer.php'; ?>
