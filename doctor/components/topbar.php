<!-- clinic/components/topbar.php -->
<header class="h-20 bg-white border-b border-slate-100 px-8 flex items-center justify-between sticky top-0 z-40">
    <!-- Search Bar -->
    <div class="flex-1 max-w-md">
        <div class="relative group">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 transition-colors group-focus-within:text-blue-600"></i>
            <input type="text" placeholder="Search for patients, records..." class="w-full bg-slate-50 border border-slate-100 rounded-xl py-2 pl-12 pr-4 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500/20 transition-all">
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-6" x-data="{ 
        notifOpen: false, 
        unreadCount: 0, 
        notifications: [],
        fetchNotifications() {
            fetch('api/notifications.php')
                .then(r => r.json())
                .then(data => {
                    if (data.unread_count > this.unreadCount) {
                        this.playPing();
                    }
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                });
        },
        playPing() {
            const audio = new Audio('https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3');
            audio.play().catch(e => console.log('Blocked'));
        },
        markAllRead() {
            fetch('api/notifications.php?read_all=1')
                .then(() => {
                    this.unreadCount = 0;
                    this.fetchNotifications();
                });
        }
    }" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 5000)">
        <!-- Icons -->
        <div class="flex items-center gap-2 border-r border-slate-100 pr-6">
            <div class="flex items-center gap-2 relative">
                <button @click="notifOpen = !notifOpen; if(notifOpen) fetchNotifications()" class="p-2 text-slate-400 hover:text-teal-600 transition-colors relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <template x-if="unreadCount > 0">
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                    </template>
                </button>

                <!-- Notifications Dropdown -->
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

                    <div class="max-h-80 overflow-y-auto text-left">
                        <template x-if="notifications.length === 0">
                            <div class="p-10 text-center">
                                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
                                    <i data-lucide="bell-off" class="w-6 h-6"></i>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No notifications</p>
                            </div>
                        </template>

                        <template x-for="n in notifications" :key="n.id">
                            <a :href="n.link || '#'" class="block p-5 hover:bg-slate-50 transition-colors border-b border-slate-50/50">
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
                    </div>

                    <div class="p-4 bg-slate-50/50 text-center">
                        <a href="messages.php" class="text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-teal-600 transition-colors">Check Chat Messages</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-3 pl-2 focus:outline-none group">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-black text-slate-900 leading-tight group-hover:text-teal-600 transition-colors"><?php echo e($_SESSION['user_name']); ?></p>
                    <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mt-0.5">Doctor</p>
                </div>
                <div class="w-10 h-10 rounded-xl overflow-hidden border-2 border-slate-50 shadow-sm bg-teal-50 flex items-center justify-center group-hover:border-teal-200 transition-all">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user_name']); ?>&background=random" class="w-full h-full object-cover">
                </div>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute top-full right-0 mt-4 w-56 bg-white border border-slate-100 rounded-3xl shadow-2xl shadow-slate-200/50 overflow-hidden z-50" x-cloak>
                
                <div class="p-5 border-b border-slate-50 bg-slate-50/50 text-left">
                    <p class="text-xs font-black text-slate-900"><?php echo e($_SESSION['user_name']); ?></p>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Provider ID: #<?php echo $_SESSION['user_id']; ?></p>
                </div>

                <div class="p-2 text-left">
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-slate-600 hover:bg-teal-50 hover:text-teal-700 transition-all">
                        <i data-lucide="user" class="w-4 h-4 opacity-50"></i> My Profile
                    </a>
                    <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-slate-600 hover:bg-teal-50 hover:text-teal-700 transition-all">
                        <i data-lucide="settings" class="w-4 h-4 opacity-50"></i> Practice Settings
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
