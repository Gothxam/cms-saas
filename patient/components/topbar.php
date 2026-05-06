<?php
// patient/components/topbar.php

// Fetch the patient's profile picture if not already fetched in the main page
if (!isset($user_data['picture_url'])) {
    $db = getDB();
    $stmt = $db->prepare("SELECT picture_url FROM patient_profiles WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $p_data = $stmt->fetch();
    $top_picture_url = $p_data['picture_url'] ?? '';
} else {
    $top_picture_url = $user_data['picture_url'];
}
?>
<header class="h-20 bg-white border-b border-slate-100 px-8 flex items-center justify-between sticky top-0 z-40">
    <!-- Search Bar -->
    <div class="flex-1 max-w-md">
        <div class="relative group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 transition-colors group-focus-within:text-teal-600"></i>
            <input type="text" placeholder="Search for patients, records..." class="w-full bg-slate-50 border border-slate-100 rounded-xl py-2 pl-12 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/10 focus:border-teal-500/20 transition-all font-medium">
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-6" x-data="{ 
        notifOpen: false, 
        callOpen: false,
        unreadCount: 0, 
        notifications: [],
        incomingCall: null,
        audioContext: null,
        ringInterval: null,

        fetchNotifications() {
            fetch('api/notifications.php')
                .then(r => r.json())
                .then(data => {
                    if (data.unread_count > this.unreadCount) {
                        const hasLive = data.notifications.some(n => n.type === 'live_session' && n.is_read == 0);
                        if (!hasLive) this.playPing();
                    }

                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                    
                    const liveCall = this.notifications.find(n => n.type === 'live_session' && n.is_read == 0);
                    if (liveCall && !this.incomingCall) {
                        this.incomingCall = liveCall;
                        this.playRingtone();
                    } else if (!liveCall && this.incomingCall) {
                        this.stopRingtone();
                        this.incomingCall = null;
                    }
                });
        },
        initAudio() {
            if (!this.audioContext) {
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
            }
        },
        playPing() {
            this.initAudio();
            const ctx = this.audioContext;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, ctx.currentTime);
            osc.connect(gain); gain.connect(ctx.destination);
            gain.gain.setValueAtTime(0, ctx.currentTime);
            gain.gain.linearRampToValueAtTime(0.1, ctx.currentTime + 0.05);
            gain.gain.linearRampToValueAtTime(0, ctx.currentTime + 0.3);
            osc.start(); osc.stop(ctx.currentTime + 0.3);
        },
        playRingtone() {
            this.initAudio();
            if (this.ringInterval) return;
            this.ringInterval = setInterval(() => {
                if (!this.incomingCall) { this.stopRingtone(); return; }
                this.generateRingTone();
            }, 2000);
            this.generateRingTone();
        },
        generateRingTone() {
            this.initAudio();
            const ctx = this.audioContext;
            const osc1 = ctx.createOscillator();
            const osc2 = ctx.createOscillator();
            const gain = ctx.createGain();
            osc1.type = 'sine'; osc1.frequency.setValueAtTime(440, ctx.currentTime);
            osc2.type = 'sine'; osc2.frequency.setValueAtTime(480, ctx.currentTime);
            osc1.connect(gain); osc2.connect(gain); gain.connect(ctx.destination);
            gain.gain.setValueAtTime(0, ctx.currentTime);
            gain.gain.linearRampToValueAtTime(0.1, ctx.currentTime + 0.1);
            gain.gain.linearRampToValueAtTime(0.1, ctx.currentTime + 1.0);
            gain.gain.linearRampToValueAtTime(0, ctx.currentTime + 1.1);
            osc1.start(); osc2.start(); osc1.stop(ctx.currentTime + 1.2); osc2.stop(ctx.currentTime + 1.2);
        },
        stopRingtone() {
            if (this.ringInterval) { clearInterval(this.ringInterval); this.ringInterval = null; }
        },
        markAllRead() {
            fetch('api/notifications.php?read_all=1')
                .then(() => {
                    this.unreadCount = 0;
                    this.stopRingtone();
                    this.incomingCall = null;
                    this.fetchNotifications();
                });
        },
        acceptCall() {
            if (!this.incomingCall) return;
            const link = this.incomingCall.link;
            const id = this.incomingCall.id;
            
            // Mark read immediately to stop ringtone
            fetch(`api/notifications.php?read_id=${id}`);
            this.stopRingtone();
            this.incomingCall = null;
            
            window.location.href = link;
        },
        markRead(id, link) {
            fetch(`api/notifications.php?read_id=${id}`);
            if (link) window.location.href = link;
        }
    }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 5000)">
        
        <!-- Icons -->
        <div class="flex items-center gap-2 border-r border-slate-100 pr-6">
            
            <!-- Call Hub Dropdown -->
            <div class="relative">
                <button @click="callOpen = !callOpen; notifOpen = false" 
                        class="p-2.5 rounded-xl transition-all relative"
                        :class="incomingCall ? 'bg-emerald-50 text-emerald-600 animate-bounce' : 'text-slate-400 hover:bg-slate-50 hover:text-teal-600'">
                    <i data-lucide="video" class="w-5 h-5"></i>
                    <template x-if="incomingCall">
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-ping"></span>
                    </template>
                </button>

                <!-- Call Dropdown -->
                <div x-show="callOpen" 
                     @click.away="callOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full right-0 mt-4 w-80 bg-white border border-slate-100 rounded-3xl shadow-2xl shadow-slate-200/50 overflow-hidden z-50" x-cloak>
                    
                    <div class="p-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i data-lucide="phone" class="w-3.5 h-3.5"></i> Call Center
                        </h4>
                        <template x-if="incomingCall">
                            <span class="px-2 py-0.5 bg-red-500 text-white text-[8px] font-black uppercase rounded-full animate-pulse">Live</span>
                        </template>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        <!-- Active Call Section -->
                        <template x-if="incomingCall">
                            <div class="p-6 bg-emerald-600 text-white relative group">
                                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500 to-teal-700 -z-10"></div>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-70 mb-1">Incoming Consultation</p>
                                <p class="text-sm font-black mb-6 leading-tight" x-text="incomingCall.message"></p>
                                <div class="flex flex-col gap-2">
                                    <button @click="acceptCall()" class="w-full bg-white text-emerald-600 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-50 transition-all shadow-lg flex items-center justify-center gap-2">
                                        <i data-lucide="phone" class="w-3.5 h-3.5"></i> Join Session
                                    </button>
                                    <button @click="markAllRead()" class="w-full py-2.5 text-[9px] font-black uppercase tracking-widest opacity-60 hover:opacity-100 transition-all">Dismiss</button>
                                </div>
                            </div>
                        </template>

                        <!-- Call Records -->
                        <div class="p-4 bg-slate-50/50 border-b border-slate-100">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Call Records</p>
                        </div>
                        
                        <template x-if="notifications.filter(n => n.type === 'live_session').length === 0">
                            <div class="p-10 text-center">
                                <i data-lucide="video-off" class="w-8 h-8 text-slate-200 mx-auto mb-4"></i>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No call history</p>
                            </div>
                        </template>

                        <template x-for="n in notifications.filter(n => n.type === 'live_session')" :key="n.id">
                            <div class="p-5 hover:bg-slate-50 transition-colors border-b border-slate-50/50 flex items-center justify-between group">
                                <div class="flex gap-4">
                                    <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center group-hover:bg-teal-50 group-hover:text-teal-600 transition-all">
                                        <i data-lucide="video" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-900">Consultation Session</p>
                                        <p class="text-[10px] font-medium text-slate-500 mt-0.5" x-text="n.created_at"></p>
                                    </div>
                                </div>
                                <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-teal-600 transition-all"></i>
                            </div>
                        </template>
                    </div>

                    <div class="p-4 text-center">
                        <p class="text-[9px] font-bold text-slate-300 uppercase tracking-[0.2em]">End-to-End Encrypted</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 relative">
                <button @click="notifOpen = !notifOpen; callOpen = false; if(notifOpen) fetchNotifications()" class="p-2 text-slate-400 hover:text-teal-600 transition-colors relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                    </template>
                </button>

                <!-- Notifications Dropdown (Standard notifications only) -->
                <div x-show="notifOpen" 
                     @click.away="notifOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full right-0 mt-4 w-80 bg-white border border-slate-100 rounded-3xl shadow-2xl shadow-slate-200/50 overflow-hidden z-50" x-cloak>
                    
                    <div class="p-5 border-b border-slate-50 flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Notifications</h4>
                        <button @click="markAllRead()" class="text-[10px] font-bold text-teal-600 hover:underline">Mark all as read</button>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        <template x-if="notifications.filter(n => n.type !== 'live_session').length === 0">
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                                    <i data-lucide="bell-off" class="w-6 h-6"></i>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No notifications</p>
                            </div>
                        </template>

                        <template x-for="n in notifications" :key="n.id">
                            <template x-if="n.type !== 'live_session'">
                                <a href="javascript:void(0)" @click="markRead(n.id, n.link)" class="block p-5 hover:bg-slate-50 transition-colors border-b border-slate-50/50">
                                    <div class="flex gap-4">
                                        <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" :class="n.is_read == 1 ? 'bg-slate-200' : 'bg-teal-500'"></div>
                                        <div>
                                            <p class="text-xs font-black text-slate-900" x-text="n.title"></p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-0.5" x-text="n.message"></p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-2" x-text="n.created_at"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </template>
                    </div>

                    <div class="p-4 bg-slate-50/50 text-center">
                        <a href="#" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-teal-600 transition-colors">View All Notifications</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-3 pl-2 focus:outline-none group">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-slate-900 leading-tight group-hover:text-teal-600 transition-colors"><?php echo e($_SESSION['user_name']); ?></p>
                    <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mt-0.5">Patient</p>
                </div>
                <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-slate-50 shadow-sm bg-teal-50 flex items-center justify-center group-hover:border-teal-200 transition-all">
                    <?php if ($top_picture_url): ?>
                        <img src="<?php echo base_url($top_picture_url); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span class="text-teal-600 font-black text-xs"><?php echo substr($_SESSION['user_name'], 0, 1) . (strpos($_SESSION['user_name'], ' ') !== false ? substr(strrchr($_SESSION['user_name'], ' '), 1, 1) : ''); ?></span>
                    <?php endif; ?>
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute top-full right-0 mt-4 w-56 bg-white border border-slate-100 rounded-3xl shadow-2xl shadow-slate-200/50 overflow-hidden z-50" x-cloak>
                
                <div class="p-5 border-b border-slate-50 bg-slate-50/50">
                    <p class="text-xs font-black text-slate-900"><?php echo e($_SESSION['user_name']); ?></p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Patient ID: #<?php echo $_SESSION['user_id']; ?></p>
                </div>

                <div class="p-2">
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-slate-600 hover:bg-teal-50 hover:text-teal-700 transition-all">
                        <i data-lucide="user" class="w-4 h-4 opacity-50"></i> My Profile
                    </a>
                    <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-slate-600 hover:bg-teal-50 hover:text-teal-700 transition-all">
                        <i data-lucide="settings" class="w-4 h-4 opacity-50"></i> Account Settings
                    </a>
                    <div class="h-px bg-slate-50 my-2"></div>
                    <a href="<?php echo base_url('logout.php'); ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-red-500 hover:bg-red-50 transition-all">
                        <i data-lucide="log-out" class="w-4 h-4 opacity-50"></i> Sign Out
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
