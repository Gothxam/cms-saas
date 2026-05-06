<?php
require_once '../core/init.php';
Auth::protect('Patient');

$db = getDB();
$page_title = "My Health Profile";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-5xl mx-auto py-8 px-6 animate-in fade-in duration-500" x-data="patientProfileApp()">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-blue-600 rounded-[2rem] flex items-center justify-center text-white text-2xl font-black shadow-xl shadow-blue-600/20">
                <span x-text="profile.name ? profile.name.charAt(0) : 'P'"></span>
            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight" x-text="profile.name"></h2>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-1">Personal Health Record</p>
            </div>
        </div>
        <button @click="saveProfile()" class="bg-slate-900 text-white px-8 py-4 rounded-[1.5rem] font-black text-xs uppercase tracking-widest shadow-2xl shadow-slate-900/20 hover:bg-slate-800 transition-all flex items-center gap-3">
            <template x-if="isSaving">
                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            </template>
            <span x-text="isSaving ? 'Saving...' : 'Save Profile'"></span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Basic Info -->
        <div class="lg:col-span-7 space-y-8">
            
            <!-- Basic Information Card -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Basic Information</h3>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2 col-span-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                        <input type="text" x-model="profile.name" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none focus:ring-2 focus:ring-blue-500/10">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Age</label>
                        <input type="number" x-model="profile.age" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Gender</label>
                        <select x-model="profile.gender" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none appearance-none">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone</label>
                        <input type="text" x-model="profile.phone" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Blood Group</label>
                        <select x-model="profile.blood_group" class="w-full p-5 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none appearance-none">
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Medical Information Card -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="heart-pulse" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Medical Information</h3>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-red-500 uppercase tracking-widest ml-1">Allergies (Critical)</label>
                        <textarea x-model="profile.allergies" placeholder="e.g. Penicillin, Peanuts, Pollen..." class="w-full h-24 p-6 bg-red-50/30 border-none rounded-3xl text-sm font-bold text-slate-800 outline-none placeholder:text-red-300"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Chronic Conditions</label>
                        <textarea x-model="profile.chronic_conditions" placeholder="e.g. Diabetes, Hypertension, Asthma..." class="w-full h-24 p-6 bg-slate-50 border-none rounded-3xl text-sm font-medium text-slate-600 outline-none"></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Medications</label>
                        <textarea x-model="profile.medications" placeholder="List any medicines you are currently taking..." class="w-full h-24 p-6 bg-slate-50 border-none rounded-3xl text-sm font-medium text-slate-600 outline-none"></textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Lifestyle & Emergency -->
        <div class="lg:col-span-5 space-y-8">
            
            <!-- Lifestyle Card -->
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm space-y-8">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-900">Lifestyle</h3>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl">
                        <div class="flex items-center gap-4">
                            <i data-lucide="wind" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-sm font-bold text-slate-700">Smoking</span>
                        </div>
                        <button @click="profile.smoking = !profile.smoking" class="w-12 h-6 rounded-full relative transition-all" :class="profile.smoking ? 'bg-orange-500' : 'bg-slate-300'">
                            <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all" :class="profile.smoking ? 'right-1' : 'left-1'"></div>
                        </button>
                    </div>
                    <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl">
                        <div class="flex items-center gap-4">
                            <i data-lucide="glass-water" class="w-5 h-5 text-slate-400"></i>
                            <span class="text-sm font-bold text-slate-700">Alcohol</span>
                        </div>
                        <button @click="profile.alcohol = !profile.alcohol" class="w-12 h-6 rounded-full relative transition-all" :class="profile.alcohol ? 'bg-orange-500' : 'bg-slate-300'">
                            <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all" :class="profile.alcohol ? 'right-1' : 'left-1'"></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="bg-slate-900 p-10 rounded-[3rem] text-white space-y-8 shadow-xl shadow-slate-900/20">
                <div class="flex items-center gap-4">
                    <div class="w-8 h-8 bg-white/10 text-red-400 rounded-xl flex items-center justify-center">
                        <i data-lucide="phone-call" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-lg font-black">Emergency Contact</h3>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Contact Name</label>
                        <input type="text" x-model="profile.emergency_name" class="w-full p-5 bg-white/5 border-none rounded-2xl text-sm font-bold text-white outline-none focus:ring-1 focus:ring-white/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-white/40 uppercase tracking-widest ml-1">Contact Phone</label>
                        <input type="text" x-model="profile.emergency_phone" class="w-full p-5 bg-white/5 border-none rounded-2xl text-sm font-bold text-white outline-none focus:ring-1 focus:ring-white/20">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function patientProfileApp() {
    return {
        profile: {},
        isSaving: false,

        init() {
            this.fetchProfile();
        },

        fetchProfile() {
            fetch('../api/patient_profile_actions.php?action=get_profile')
                .then(r => r.json())
                .then(data => {
                    this.profile = data.profile;
                    this.$nextTick(() => lucide.createIcons());
                });
        },

        saveProfile() {
            this.isSaving = true;
            fetch('../api/patient_profile_actions.php?action=save_profile', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(this.profile)
            })
            .then(r => r.json())
            .then(data => {
                setTimeout(() => { this.isSaving = false; }, 800);
            });
        }
    }
}
</script>

<?php require_once 'components/footer.php'; ?>
