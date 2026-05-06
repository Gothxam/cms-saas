<?php
// patient/settings.php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$patient_id = $_SESSION['user_id'];

$page_title = "Account Settings";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-4xl mx-auto py-6 space-y-10 animate-in fade-in duration-500">

    <!-- Header -->
    <div>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Settings</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Manage your account preferences and security.</p>
    </div>

    <!-- Security Section -->
    <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-10">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900">Security & Password</h3>
        </div>

        <form class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Password</label>
                <input type="password" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-blue-500/10 transition-all">
            </div>
            <div class="space-y-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
                <input type="password" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-blue-500/10 transition-all">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Notification Preferences -->
    <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-10">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <i data-lucide="bell-ring" class="w-5 h-5"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900">Notifications</h3>
        </div>

        <div class="space-y-6">
            <div class="flex items-center justify-between p-6 bg-slate-50 rounded-[2rem]">
                <div>
                    <h4 class="text-sm font-black text-slate-900">Email Notifications</h4>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">Receive appointment reminders via email.</p>
                </div>
                <button class="w-12 h-6 bg-teal-500 rounded-full relative shadow-inner">
                    <div class="absolute right-1 top-1 w-4 h-4 bg-white rounded-full"></div>
                </button>
            </div>
            <div class="flex items-center justify-between p-6 bg-slate-50 rounded-[2rem]">
                <div>
                    <h4 class="text-sm font-black text-slate-900">SMS Alerts</h4>
                    <p class="text-xs font-medium text-slate-400 mt-0.5">Receive urgent updates on your phone.</p>
                </div>
                <button class="w-12 h-6 bg-slate-300 rounded-full relative shadow-inner">
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full"></div>
                </button>
            </div>
        </div>
    </div>

</div>

<?php require_once 'components/footer.php'; ?>
