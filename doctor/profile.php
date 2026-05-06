<?php
require_once '../core/init.php';
Auth::protect('Doctor');

$db = getDB();
$page_title = "Doctor Profile & Availability";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-8 px-6 animate-in fade-in duration-500" x-data="profileApp()">
    
    <!-- Profile Header -->
    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center gap-6">
            <div class="relative group">
                <div class="w-24 h-24 bg-teal-600 rounded-[2.5rem] flex items-center justify-center text-white text-3xl font-black shadow-xl shadow-teal-600/20 group-hover:scale-105 transition-all">
                    <span x-text="profile.name ? profile.name.charAt(0) : 'D'"></span>
                </div>
                <button class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-2xl shadow-lg border border-slate-100 flex items-center justify-center text-slate-400 hover:text-teal-600 transition-all">
                    <i data-lucide="camera" class="w-5 h-5"></i>
                </button>
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight" x-text="'Dr. ' + profile.name"></h2>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-1" x-text="profile.specialization || 'Professional Profile'"></p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div x-show="isSaving" class="flex items-center gap-2 text-teal-600 font-black text-[10px] uppercase tracking-widest animate-pulse">
                <div class="w-2 h-2 bg-teal-600 rounded-full"></div>
                Saving Changes...
            </div>
            <button @click="saveAll()" class="bg-slate-900 text-white px-8 py-4 rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-slate-900/20 hover:bg-slate-800 transition-all">
                Save Profile
            </button>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Basic & Professional -->
        <div class="lg:col-span-7 space-y-10">
            
            <!-- Basic Information -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-8 h-8 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Basic Information</h3>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" x-model="profile.name" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-teal-500/10 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                        <input type="text" x-model="profile.phone" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                    </div>
                    <div class="space-y-2 col-span-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                        <input type="email" x-model="profile.email" disabled class="w-full p-5 bg-slate-100/50 border-none rounded-2xl text-sm font-bold text-slate-400 outline-none cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- Professional Details -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="award" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Professional Details</h3>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2 col-span-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Specialization</label>
                        <input type="text" x-model="profile.specialization" placeholder="e.g. Cardiologist" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Experience (Years)</label>
                        <input type="number" x-model="profile.experience" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Registration No.</label>
                        <input type="text" x-model="profile.reg_no" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                    </div>
                    <div class="space-y-2 col-span-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Qualifications</label>
                        <input type="text" x-model="profile.qualifications" placeholder="e.g. MBBS, MD" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                    </div>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">About Bio</label>
                    <textarea x-model="profile.bio" class="w-full h-32 p-6 bg-slate-50 border-none rounded-3xl text-sm font-medium text-slate-600 outline-none leading-relaxed" placeholder="Write a short introduction about your practice..."></textarea>
                </div>
            </div>

        </div>

        <!-- Right: Settings & Availability -->
        <div class="lg:col-span-5 space-y-10">
            
            <!-- Consultation Settings -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center gap-4 mb-2">
                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Consultation Settings</h3>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Clinic / Hospital Name</label>
                            <input type="text" x-model="profile.clinic_name" placeholder="e.g. City General Hospital" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                        </div>
                        <div class="p-6 bg-emerald-50 rounded-[2rem] border border-emerald-100/50">
                            <label class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-2 block">Video Fee</label>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-black text-slate-400">₹</span>
                                <input type="number" x-model="profile.video_fee" class="w-full bg-transparent border-none p-0 text-xl font-black text-slate-800 outline-none">
                            </div>
                        </div>
                        <div class="p-6 bg-blue-50 rounded-[2rem] border border-blue-100/50">
                            <label class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-2 block">Clinic Fee</label>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-black text-slate-400">₹</span>
                                <input type="number" x-model="profile.visit_fee" class="w-full bg-transparent border-none p-0 text-xl font-black text-slate-800 outline-none">
                            </div>
                        </div>
                        <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 md:col-span-2">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Slot Duration</label>
                            <select x-model="profile.slot_duration" class="w-full bg-transparent border-none p-0 text-xl font-black text-slate-800 outline-none appearance-none">
                                <option value="10">10 Minutes</option>
                                <option value="15">15 Minutes</option>
                                <option value="20">20 Minutes</option>
                                <option value="30">30 Minutes</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Availability Management -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900">Weekly Availability</h3>
                    </div>
                </div>

                <div class="space-y-4">
                    <template x-for="(day, index) in availability" :key="day.id">
                        <div class="p-6 rounded-[2rem] border transition-all" :class="day.enabled ? 'bg-white border-teal-500/20 shadow-xl shadow-teal-500/5' : 'bg-slate-50/50 border-slate-100 opacity-60'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <button @click="day.enabled = !day.enabled" class="w-12 h-6 rounded-full relative transition-all" :class="day.enabled ? 'bg-teal-500' : 'bg-slate-300'">
                                        <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all" :class="day.enabled ? 'right-1' : 'left-1'"></div>
                                    </button>
                                    <span class="text-sm font-black text-slate-800" x-text="day.name"></span>
                                </div>
                                <button x-show="day.enabled" @click="addRange(index)" class="w-8 h-8 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center hover:bg-teal-500 hover:text-white transition-all">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>

                            <div x-show="day.enabled" class="mt-4 space-y-3">
                                <template x-for="(range, rIndex) in day.ranges" :key="rIndex">
                                    <div class="flex items-center gap-3">
                                        <input type="time" x-model="range.start" class="flex-1 p-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 outline-none">
                                        <span class="text-slate-300 font-black">→</span>
                                        <input type="time" x-model="range.end" class="flex-1 p-3 bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-800 outline-none">
                                        <button @click="removeRange(index, rIndex)" class="text-slate-300 hover:text-red-500 transition-all">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function profileApp() {
    return {
        profile: {},
        availability: [],
        isLoading: true,
        isSaving: false,

        init() {
            this.fetchProfile();
        },

        fetchProfile() {
            fetch('../api/doctor_profile_actions.php?action=get_profile')
                .then(r => r.json())
                .then(data => {
                    this.profile = data.profile;
                    this.availability = data.availability;
                    this.isLoading = false;
                    this.$nextTick(() => lucide.createIcons());
                });
        },

        addRange(dayIndex) {
            this.availability[dayIndex].ranges.push({ start: '09:00', end: '17:00' });
            this.$nextTick(() => lucide.createIcons());
        },

        removeRange(dayIndex, rangeIndex) {
            this.availability[dayIndex].ranges.splice(rangeIndex, 1);
            if (this.availability[dayIndex].ranges.length === 0) {
                this.availability[dayIndex].enabled = false;
                this.availability[dayIndex].ranges.push({ start: '09:00', end: '17:00' });
            }
        },

        saveAll() {
            this.isSaving = true;
            
            // Save Profile
            const p1 = fetch('../api/doctor_profile_actions.php?action=save_profile', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.profile)
            });

            // Save Availability
            const p2 = fetch('../api/doctor_profile_actions.php?action=save_availability', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ availability: this.availability })
            });

            Promise.all([p1, p2]).then(() => {
                setTimeout(() => { this.isSaving = false; }, 1000);
            });
        }
    }
}
</script>

<?php require_once 'components/footer.php'; ?>
