<?php
// doctor/session.php — Consultation Room (Agora Powered)
require_once '../core/init.php';
Auth::protect('Doctor');

$agora_config = require_once '../config/agora.php';
$db = getDB();
$clinic_id = $_SESSION['clinic_id'];
$doctor_id = $_SESSION['user_id'];
$apt_id = (int)($_GET['id'] ?? 0);

if (!$apt_id) {
    header('Location: appointments.php');
    exit;
}

// Fetch Appointment
$stmt = $db->prepare("
    SELECT a.*, p.name as patient_name, p.email as patient_email
    FROM appointments a
    JOIN users p ON a.patient_id = p.id
    WHERE a.id = ? AND a.clinic_id = ?
");
$stmt->execute([$apt_id, $clinic_id]);
$apt = $stmt->fetch();

if (!$apt) {
    header('Location: appointments.php');
    exit;
}

$page_title = "Consultation Room";
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<!-- Agora SDK -->
<script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.18.2.js"></script>
<script src="../assets/js/agora-handler.js"></script>

<div class="space-y-0 relative overflow-hidden" x-data="consultationApp()">
    
    <script>
    function consultationApp() {
        return {
            aptId: <?php echo $apt_id; ?>,
            patientId: <?php echo $apt['patient_id']; ?>,
            agoraAppId: '<?php echo $agora_config['app_id']; ?>',
            agoraToken: '<?php echo $agora_config['token'] ?? ''; ?>',
            visitId: 0,
            status: '<?php echo $apt['status']; ?>',
            activeTab: 'problem',
            
            // Simplified Data
            chief_complaint: '',
            since_when: '',
            symptoms_data: [],
            vitals: { temp: '', bp: '', pulse: '', spo2: '', weight: '' },
            history_illness: '',
            doctor_notes: '',
            diagnosis: '',
            other_causes: '',
            advice: '',
            follow_up_date: '',
            severity: 'mild',
            prescriptions: [],
            investigations: [],

            // Meta
            patientInfo: { name: '...', age: '...', sex: '...', allergies: '', chronic_conditions: '' },
            isLoading: true,
            isSaving: false,
            showHistory: false,
            showReports: false,
            historyData: [],
            reportsData: [],
            reportCount: 0,
            agora: null,
            callJoined: false,
            isMuted: false,
            isVideoOff: false,
            isReady: false,

            init() {
                this.fetchVisit();
                this.fetchPatientInfo();
                setInterval(() => {
                    if(this.visitId && this.status === 'in_progress') {
                        this.autoSave();
                    }
                }, 8000);
                
                if(this.status === 'in_progress') {
                    this.initAgora();
                }
            },

            fetchPatientInfo() {
                fetch(`api/patient_info.php?action=basic_info&patient_id=${this.patientId}`)
                    .then(r => r.json())
                    .then(data => { 
                        this.patientInfo = data; 
                        this.reportCount = data.report_count || 0;
                    });
            },

            startConsultation() {
                const fd = new FormData();
                fd.append('appointment_id', this.aptId);
                fetch('../api/consultation.php?action=start', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(data => {
                        if(data.success) {
                            this.visitId = data.visit_id;
                            this.status = 'in_progress';
                            this.initAgora();
                        } else {
                            alert(data.error || 'Failed to start consultation');
                        }
                    });
            },

            applyTemplate(type) {
                const templates = {
                    'Fever': { 
                        complaint: 'Fever since 2 days', 
                        symptoms: ['Fever', 'Weakness'],
                        diagnosis: 'Viral Fever',
                        prescriptions: [{ medicine_name: 'Paracetamol 500mg', dosage: '500mg', frequency: '1-0-1', duration: '3 days', instructions: 'After Food' }]
                    },
                    'Cold': { 
                        complaint: 'Running nose and cough', 
                        symptoms: ['Cough', 'Cold'],
                        diagnosis: 'Common Cold',
                        prescriptions: [{ medicine_name: 'Cough Syrup', dosage: '10ml', frequency: '1-1-1', duration: '5 days', instructions: 'After Food' }]
                    }
                };
                if(templates[type]) {
                    const t = templates[type];
                    this.chief_complaint = t.complaint;
                    this.symptoms_data = t.symptoms;
                    this.diagnosis = t.diagnosis;
                    this.prescriptions = [...t.prescriptions];
                }
            },

            repeatLast() {
                fetch(`api/patient_info.php?action=last_prescription&patient_id=${this.patientId}`)
                    .then(r => r.json())
                    .then(data => {
                        if(data.error) {
                            alert(data.error);
                            return;
                        }
                        
                        if(data.visit) {
                            this.diagnosis = data.visit.diagnosis || '';
                            this.advice = data.visit.advice || '';
                            if(data.visit.symptoms) {
                                try {
                                    this.symptoms_data = JSON.parse(data.visit.symptoms);
                                } catch(e) {
                                    this.symptoms_data = data.visit.symptoms.split(',').map(s => s.trim());
                                }
                            }
                        }

                        if(data.prescriptions && data.prescriptions.length) {
                            this.prescriptions = data.prescriptions.map(p => ({
                                medicine_name: p.medicine_name,
                                dosage: p.dosage,
                                frequency: p.frequency,
                                duration: p.duration,
                                instructions: p.instructions
                            }));
                        } else {
                            alert('No previous prescription data found.');
                        }
                    });
            },

            // ... (rest of core methods updated to match simplified fields)
            initAgora() {
                if(!this.agoraAppId) return;
                const testingChannel = '<?php echo $agora_config['testing_channel'] ?? ''; ?>';
                const channel = (testingChannel && testingChannel !== '') ? testingChannel : 'medos-session-' + this.aptId;
                const self = this;
                this.agora = new AgoraHandler(this.agoraAppId, channel, this.agoraToken, 1, () => { self.callJoined = true; }, () => { self.isReady = true; });
            },
            async toggleMic() { if(!this.agora || !this.isReady) return; this.isMuted = await this.agora.toggleAudio(); },
            async toggleVideo() { if(!this.agora || !this.isReady) return; this.isVideoOff = await this.agora.toggleVideo(); },
            
            fetchVisit() {
                fetch(`../api/consultation.php?action=fetch&appointment_id=${this.aptId}`)
                    .then(r => r.json())
                    .then(data => {
                        this.isLoading = false;
                        if(data.id) {
                            this.visitId = data.id;
                            this.chief_complaint = data.chief_complaint || '';
                            this.symptoms_data = JSON.parse(data.symptoms_data || '[]');
                            this.vitals = JSON.parse(data.vitals_data || '{"temp":"","bp":"","pulse":"","spo2":"","weight":""}');
                            this.history_illness = data.history_illness || '';
                            this.doctor_notes = data.physical_exam || '';
                            this.diagnosis = data.diagnosis || '';
                            this.other_causes = data.differential_diagnosis || '';
                            this.advice = data.advice || '';
                            this.follow_up_date = data.follow_up_date || '';
                            this.severity = data.severity || 'mild';
                            this.prescriptions = data.prescriptions || [];
                            this.investigations = data.investigations || [];
                            this.status = 'in_progress';
                            if(!this.agora) this.initAgora();
                        }
                    });
            },

            autoSave() {
                this.isSaving = true;
                const fd = new FormData();
                fd.append('visit_id', this.visitId);
                fd.append('chief_complaint', this.chief_complaint);
                fd.append('symptoms_data', JSON.stringify(this.symptoms_data));
                fd.append('vitals_data', JSON.stringify(this.vitals));
                fd.append('history_illness', this.history_illness);
                fd.append('physical_exam', this.doctor_notes);
                fd.append('diagnosis', this.diagnosis);
                fd.append('differential_diagnosis', this.other_causes);
                fd.append('advice', this.advice);
                fd.append('follow_up_date', this.follow_up_date);
                fd.append('severity', this.severity);
                fd.append('prescriptions', JSON.stringify(this.prescriptions));
                fd.append('investigations', JSON.stringify(this.investigations));
                fetch('../api/consultation.php?action=update', { method: 'POST', body: fd })
                    .then(() => { setTimeout(() => { this.isSaving = false; }, 500); });
            },

            addMedicine() { this.prescriptions.push({ medicine_name: '', dosage: '', frequency: '1-0-1', duration: '', instructions: 'After Food' }); },
            removeMedicine(index) { this.prescriptions.splice(index, 1); },
            addTest() { this.investigations.push({ test_name: '', notes: '' }); },
            removeTest(index) { this.investigations.splice(index, 1); },
            toggleSymptom(s) {
                if(this.symptoms_data.includes(s)) this.symptoms_data = this.symptoms_data.filter(x => x !== s);
                else this.symptoms_data.push(s);
            },
            endConsultation() {
                if(!confirm('End this consultation?')) return;
                if(this.agora) this.agora.leave();
                const fd = new FormData();
                fd.append('visit_id', this.visitId);
                fetch('../api/consultation.php?action=end', { method: 'POST', body: fd }).then(() => { window.location.href = 'appointments.php?success=completed'; });
            },
            fetchHistory() { this.showHistory = true; fetch(`api/patient_info.php?action=history&patient_id=${this.patientId}`).then(r => r.json()).then(data => { this.historyData = data; setTimeout(() => lucide.createIcons(), 100); }); },
            fetchReports() { this.showReports = true; fetch(`api/patient_info.php?action=reports&patient_id=${this.patientId}`).then(r => r.json()).then(data => { this.reportsData = data; setTimeout(() => lucide.createIcons(), 100); }); }
        }
    }
    </script>

    <!-- Premium Header Card -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative z-[10]">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-teal-50 rounded-[1.5rem] flex items-center justify-center text-teal-600 shadow-inner">
                <i data-lucide="video" class="w-8 h-8"></i>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-2xl font-black text-slate-800">Consultation Hub</h2>
                    <template x-if="status === 'in_progress'">
                        <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full flex items-center gap-1.5 shadow-lg shadow-emerald-500/20">
                            <span class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></span>
                            Live Session
                        </span>
                    </template>
                </div>
                <p class="text-slate-400 text-sm font-medium">Room ID: <span class="text-slate-600 font-bold">#CMS-<?php echo str_pad($apt_id, 5, '0', STR_PAD_LEFT); ?></span></p>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <template x-if="isSaving">
                <span class="text-[10px] font-black text-teal-400 uppercase tracking-widest animate-pulse">Auto-saving...</span>
            </template>
            <!-- Patient Info Badge -->
            <div class="flex items-center gap-4 p-2 pr-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                <div class="w-12 h-12 bg-white rounded-full p-1 shadow-sm overflow-hidden flex items-center justify-center font-black text-teal-600 uppercase">
                    <?php echo substr($apt['patient_name'], 0, 1); ?>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Patient</p>
                    <p class="text-sm font-bold text-slate-800"><?php echo e($apt['patient_name']); ?></p>
                </div>
            </div>

            <button @click="endConsultation()" class="h-14 px-8 bg-red-500 hover:bg-red-600 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all shadow-xl shadow-red-500/20 flex items-center gap-3">
                <i data-lucide="phone-off" class="w-4 h-4"></i>
                End Session
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left: Video & Clinical Observations -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Cinematic Video Area -->
            <div class="bg-slate-950 rounded-[3.5rem] aspect-video relative overflow-hidden shadow-2xl border-[6px] border-white group">
                <div id="remote-video" class="absolute inset-0 w-full h-full bg-slate-950 z-0">
                    <!-- Secure Shield Overlay -->
                    <div x-show="!callJoined" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/95 z-20 transition-all duration-1000 backdrop-blur-xl">
                        <div class="relative mb-10 group/icon">
                            <div class="w-40 h-40 bg-teal-500/5 rounded-full flex items-center justify-center animate-pulse border border-teal-500/10">
                                <i data-lucide="shield-check" class="w-20 h-20 text-teal-500/50"></i>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-20 h-20 bg-white rounded-[2rem] shadow-2xl flex items-center justify-center text-teal-600 transform group-hover/icon:scale-110 transition-all duration-500">
                                    <i data-lucide="user" class="w-10 h-10"></i>
                                </div>
                            </div>
                        </div>
                        
                        <template x-if="status !== 'in_progress'">
                            <button @click="startConsultation()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-14 py-5 rounded-[2rem] font-black text-xs uppercase tracking-widest transition-all shadow-2xl shadow-emerald-500/40 flex items-center gap-4">
                                <i data-lucide="play" class="w-5 h-5 fill-current"></i>
                                Start Consultation
                            </button>
                        </template>
                        <template x-if="status === 'in_progress'">
                            <div class="text-center">
                                <h3 class="text-3xl font-black text-white mb-3 tracking-tight">Awaiting Patient</h3>
                                <p class="text-slate-400 text-center max-w-sm px-6 text-sm font-medium leading-relaxed mb-8">
                                    Room is secure. Waiting for the patient to connect...
                                </p>
                                <button @click="agora.join()" class="bg-teal-600 hover:bg-teal-500 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-teal-600/30">
                                    Initialize Camera
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Local Mirror -->
                <div id="local-video" class="absolute bottom-10 right-10 w-56 aspect-video bg-slate-900 rounded-[2rem] border-4 border-white/10 shadow-2xl overflow-hidden z-30 transition-all duration-500 hover:scale-105 group-hover:bottom-12"></div>
                
                <!-- Engine Info HUD -->
                <div class="absolute top-10 left-10 pointer-events-none z-40">
                    <div id="agora-debug" class="bg-black/90 text-[10px] font-black text-teal-400 px-5 py-2.5 rounded-full border border-teal-500/30 uppercase tracking-widest shadow-2xl">
                        System: Active
                    </div>
                </div>

                <!-- Floating Control Bar -->
                <div x-show="callJoined" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10" x-transition:enter-end="opacity-100 translate-y-0" class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 px-6 py-3 bg-slate-900/90 backdrop-blur-2xl rounded-[2rem] border border-white/10 shadow-2xl z-50 pointer-events-auto">
                    <button @click="toggleMic()" 
                        :disabled="!isReady"
                        :class="isMuted ? 'bg-red-500 text-white' : (isReady ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-white/5 text-white/30 cursor-not-allowed')" 
                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-all">
                        <i :data-lucide="isMuted ? 'mic-off' : 'mic'" class="w-5 h-5"></i>
                    </button>
                    <button @click="toggleVideo()" 
                        :disabled="!isReady"
                        :class="isVideoOff ? 'bg-red-500 text-white' : (isReady ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-white/5 text-white/30 cursor-not-allowed')" 
                        class="w-12 h-12 rounded-xl flex items-center justify-center transition-all">
                        <i :data-lucide="isVideoOff ? 'video-off' : 'video'" class="w-5 h-5"></i>
                    </button>
                    <div class="w-px h-6 bg-white/10 mx-2"></div>
                    <button @click="endConsultation()" class="h-12 px-6 bg-red-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest flex items-center gap-3 hover:bg-red-600 transition-all">
                        <i data-lucide="phone-off" class="w-4 h-4"></i> End Call
                    </button>
                </div>
            </div>

            <!-- Quick Templates Bar -->
            <div x-show="status === 'in_progress'" class="flex items-center gap-3 mb-6 overflow-x-auto pb-2 custom-scrollbar">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Quick Templates:</span>
                <button @click="applyTemplate('Fever')" class="px-4 py-2 bg-amber-50 text-amber-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-amber-100 transition-all">Common Fever</button>
                <button @click="applyTemplate('Cold')" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-100 transition-all">Viral Cold</button>
                <button @click="repeatLast()" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all flex items-center gap-2">
                    <i data-lucide="rotate-ccw" class="w-3 h-3"></i> Repeat Last Prescription
                </button>
            </div>

            <!-- Fast Navigation Tabs -->
            <div x-show="status === 'in_progress'" class="bg-white p-2 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-2 mb-8 sticky top-0 z-[60]">
                <button @click="activeTab = 'problem'" :class="activeTab === 'problem' ? 'bg-slate-900 text-white shadow-xl shadow-slate-900/10' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all">1. The Problem</button>
                <button @click="activeTab = 'checkup'" :class="activeTab === 'checkup' ? 'bg-slate-900 text-white shadow-xl shadow-slate-900/10' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all">2. Checkup</button>
                <button @click="activeTab = 'diagnosis'" :class="activeTab === 'diagnosis' ? 'bg-slate-900 text-white shadow-xl shadow-slate-900/10' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all">3. Diagnosis</button>
                <button @click="activeTab = 'treatment'" :class="activeTab === 'treatment' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-600/10' : 'text-slate-400 hover:bg-slate-50'" class="flex-1 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] transition-all">4. Treatment</button>
            </div>

            <!-- Simplified Input Sections -->
            <div x-show="status === 'in_progress'" x-transition class="space-y-8">
                
                <!-- SECTION 1: THE PROBLEM -->
                <div x-show="activeTab === 'problem'" class="animate-in fade-in slide-in-from-bottom-4 duration-300">
                    <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm space-y-8">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Main Issue</h4>
                            <input type="text" x-model="chief_complaint" placeholder="e.g. Fever since 2 days, sharp pain in lower abdomen..." class="w-full p-6 bg-slate-50 border-none rounded-2xl text-base font-bold text-slate-800 outline-none focus:ring-2 focus:ring-emerald-500/10">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Select Symptoms</h4>
                                <div class="flex flex-wrap gap-2">
                                    <template x-for="s in ['Fever', 'Pain', 'Cough', 'Cold', 'Weakness', 'Headache', 'Nausea', 'Fatigue']">
                                        <button @click="toggleSymptom(s)" :class="symptoms_data.includes(s) ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-slate-50 text-slate-500'" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all" x-text="s"></button>
                                    </template>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Since when?</h4>
                                <input type="text" x-model="since_when" placeholder="e.g. 1 day, 2 weeks..." class="w-full p-6 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-800 outline-none">
                            </div>
                        </div>

                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Extra Details</h4>
                            <textarea x-model="history_illness" placeholder="Any additional details or history of present illness..." class="w-full h-48 p-8 bg-slate-50 border-none rounded-[2.5rem] text-sm font-medium outline-none leading-relaxed"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: CHECKUP -->
                <div x-show="activeTab === 'checkup'" class="animate-in fade-in slide-in-from-bottom-4 duration-300">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                        <template x-for="(label, key) in {temp: 'Temp (°F)', bp: 'BP (mmHg)', pulse: 'Pulse (bpm)', spo2: 'SpO2 (%)', weight: 'Weight (kg)'}">
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm text-center">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2" x-text="label"></p>
                                <input type="text" x-model="vitals[key]" class="w-full bg-slate-50 border-none rounded-xl p-3 text-sm font-black text-slate-800 outline-none text-center">
                            </div>
                        </template>
                    </div>

                    <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Doctor's Observation Notes</h4>
                        <textarea x-model="doctor_notes" placeholder="Observations from visual or physical examination..." class="w-full h-64 p-8 bg-slate-50 border-none rounded-[2.5rem] text-sm font-medium outline-none"></textarea>
                    </div>
                </div>

                <!-- SECTION 3: DIAGNOSIS -->
                <div x-show="activeTab === 'diagnosis'" class="animate-in fade-in slide-in-from-bottom-4 duration-300">
                    <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm space-y-8">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Main Diagnosis</h4>
                            <input type="text" x-model="diagnosis" placeholder="Primary diagnosis..." class="w-full p-6 bg-slate-50 border-none rounded-2xl text-base font-bold text-slate-800 outline-none">
                        </div>
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Other Possible Causes</h4>
                            <textarea x-model="other_causes" placeholder="List other potential causes..." class="w-full h-40 p-8 bg-slate-50 border-none rounded-[2rem] text-sm font-medium outline-none"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: TREATMENT -->
                <div x-show="activeTab === 'treatment'" class="animate-in fade-in slide-in-from-bottom-4 duration-300">
                    <!-- Medicine Builder -->
                    <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm space-y-8">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Medicines</h4>
                            <button @click="addMedicine()" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Medicine
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            <template x-for="(med, index) in prescriptions" :key="index">
                                <div class="p-8 bg-slate-50 rounded-[2.5rem] border border-slate-100 grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                                    <div class="md:col-span-4">
                                        <label class="text-[8px] font-black text-slate-400 uppercase mb-2 block">Medicine Name</label>
                                        <input type="text" x-model="med.medicine_name" placeholder="e.g. Paracetamol" class="w-full p-4 bg-white border border-slate-100 rounded-xl text-sm font-bold outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-[8px] font-black text-slate-400 uppercase mb-2 block">Dose</label>
                                        <input type="text" x-model="med.dosage" placeholder="500mg" class="w-full p-4 bg-white border border-slate-100 rounded-xl text-sm font-bold outline-none">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-[8px] font-black text-slate-400 uppercase mb-2 block">Timing</label>
                                        <select x-model="med.frequency" class="w-full p-4 bg-white border border-slate-100 rounded-xl text-sm font-bold outline-none">
                                            <option value="1-0-1">1-0-1 (M/N)</option>
                                            <option value="1-1-1">1-1-1 (M/A/N)</option>
                                            <option value="0-0-1">0-0-1 (Night)</option>
                                            <option value="1-0-0">1-0-0 (Morning)</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="text-[8px] font-black text-slate-400 uppercase mb-2 block">Duration</label>
                                        <input type="text" x-model="med.duration" placeholder="5 days" class="w-full p-4 bg-white border border-slate-100 rounded-xl text-sm font-bold outline-none">
                                    </div>
                                    <div class="md:col-span-2 flex justify-end">
                                        <button @click="removeMedicine(index)" class="w-12 h-12 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <div class="md:col-span-8">
                                        <select x-model="med.instructions" class="w-full p-4 bg-white/50 border-none rounded-xl text-xs font-bold outline-none">
                                            <option value="After Food">After Food</option>
                                            <option value="Before Food">Before Food</option>
                                            <option value="Empty Stomach">Empty Stomach</option>
                                        </select>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tests & Advice -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm">
                            <div class="flex items-center justify-between mb-8">
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recommended Tests</h4>
                                <button @click="addTest()" class="text-emerald-600 font-black text-[10px] uppercase tracking-widest">Add Test</button>
                            </div>
                            <div class="space-y-4">
                                <template x-for="(test, index) in investigations" :key="index">
                                    <div class="flex items-center gap-3">
                                        <input type="text" x-model="test.test_name" placeholder="Blood Test, X-ray..." class="flex-1 p-4 bg-slate-50 border-none rounded-2xl text-xs font-bold outline-none">
                                        <button @click="removeTest(index)" class="text-red-400 hover:text-red-600 transition-all"><i data-lucide="x" class="w-5 h-5"></i></button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8">Doctor's Advice</h4>
                            <textarea x-model="advice" placeholder="Rest, drink fluids, light diet..." class="w-full h-32 p-6 bg-slate-50 border-none rounded-[2rem] text-xs font-medium outline-none"></textarea>
                        </div>
                    </div>

                    <!-- Prescription Preview & Follow-up -->
                    <div class="bg-slate-900 p-10 rounded-[3.5rem] shadow-2xl space-y-8">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                            <div class="flex items-center gap-6">
                                <div>
                                    <p class="text-white/40 text-[9px] font-black uppercase mb-3">Case Severity</p>
                                    <div class="flex gap-2">
                                        <template x-for="s in ['mild', 'moderate', 'severe']">
                                            <button @click="severity = s" :class="severity === s ? 'bg-red-500 text-white' : 'bg-white/5 text-white/40'" class="px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all" x-text="s"></button>
                                        </template>
                                    </div>
                                </div>
                                <div class="w-px h-12 bg-white/10 hidden md:block"></div>
                                <div>
                                    <p class="text-white/40 text-[9px] font-black uppercase mb-3">Next Follow-up</p>
                                    <input type="date" x-model="follow_up_date" class="bg-white/5 border-none rounded-xl px-4 py-2 text-white text-xs outline-none">
                                </div>
                            </div>
                            <button @click="endConsultation()" class="px-10 py-5 bg-emerald-500 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:scale-105 transition-all">
                                Finalize & Print
                            </button>
                        </div>
                        
                        <!-- Mini Prescription Preview -->
                        <div x-show="prescriptions.length" class="p-8 bg-white/5 rounded-[2.5rem] border border-white/5 animate-in fade-in zoom-in duration-500">
                            <h5 class="text-[9px] font-black text-white/20 uppercase tracking-widest mb-6">Live Prescription Preview</h5>
                            <div class="space-y-4">
                                <template x-for="p in prescriptions">
                                    <div class="flex items-center justify-between py-3 border-b border-white/5">
                                        <div>
                                            <span class="text-white font-bold text-xs" x-text="p.medicine_name"></span>
                                            <span class="text-white/40 text-[10px] ml-2" x-text="p.dosage"></span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-emerald-400 font-black text-[10px] uppercase" x-text="p.frequency"></span>
                                            <span class="text-white/30 text-[10px] block" x-text="p.instructions"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Patient Info Card & Records -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-sm sticky top-32">
                
                <!-- Patient Identity Card -->
                <div class="text-center mb-10 pb-10 border-b border-slate-50">
                    <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] mx-auto mb-6 flex items-center justify-center text-slate-300 shadow-inner overflow-hidden">
                        <template x-if="patientInfo.picture_url">
                            <img :src="'../' + patientInfo.picture_url" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!patientInfo.picture_url">
                            <i data-lucide="user" class="w-10 h-10"></i>
                        </template>
                    </div>
                    <h3 class="text-xl font-black text-slate-800" x-text="patientInfo.name"></h3>
                    <div class="flex items-center justify-center gap-3 mt-2">
                        <span class="px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-black uppercase" x-text="patientInfo.sex"></span>
                        <span class="px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-black uppercase" x-text="patientInfo.age + ' Years'"></span>
                        <span class="px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-black uppercase" x-text="patientInfo.blood_group"></span>
                    </div>
                </div>

                <!-- Medical Context Highlighting -->
                <div class="space-y-6 mb-10">
                    <div :class="patientInfo.allergies ? 'bg-red-50 border-red-100' : 'bg-slate-50 border-slate-100'" class="p-6 rounded-[2rem] border transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <i data-lucide="alert-triangle" :class="patientInfo.allergies ? 'text-red-500' : 'text-slate-400'" class="w-4 h-4"></i>
                            <h4 :class="patientInfo.allergies ? 'text-red-600' : 'text-slate-500'" class="text-[9px] font-black uppercase tracking-widest">Critical Allergies</h4>
                        </div>
                        <p :class="patientInfo.allergies ? 'text-red-700' : 'text-slate-400 italic'" class="text-xs font-bold" x-text="patientInfo.allergies || 'None disclosed'"></p>
                    </div>
                    
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
                        <div class="flex items-center gap-3 mb-3">
                            <i data-lucide="activity" class="text-slate-400 w-4 h-4"></i>
                            <h4 class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Chronic Conditions</h4>
                        </div>
                        <p class="text-xs font-bold text-slate-600" x-text="patientInfo.chronic_conditions || 'None recorded'"></p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button @click="fetchHistory()" class="w-full flex items-center justify-between p-5 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-all group border border-transparent hover:border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-teal-600 shadow-sm">
                                <i data-lucide="history" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-black text-slate-700 uppercase tracking-widest">Past Visits</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    <button @click="fetchReports()" class="w-full flex items-center justify-between p-5 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-all group border border-transparent hover:border-slate-200 relative overflow-hidden">
                        <!-- Pulse Effect for New Reports -->
                        <template x-if="reportCount > 0">
                            <div class="absolute top-0 right-0 w-20 h-20 bg-orange-500/5 -mr-10 -mt-10 blur-xl"></div>
                        </template>
                        
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-500 shadow-sm relative">
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                <template x-if="reportCount > 0">
                                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-orange-500 border-2 border-white rounded-full flex items-center justify-center text-[8px] font-black text-white" x-text="reportCount"></span>
                                </template>
                            </div>
                            <div class="text-left">
                                <span class="text-xs font-black text-slate-700 uppercase tracking-widest block">Diagnostics</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase" x-text="reportCount + ' External Files'"></span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Medical History -->
    <div x-show="showHistory" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-md" x-cloak>
        <div class="bg-white w-full max-w-4xl max-h-[80vh] rounded-[3.5rem] shadow-2xl overflow-hidden flex flex-col border-[6px] border-white" @click.away="showHistory = false">
            <div class="p-10 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-teal-600 rounded-[1.2rem] flex items-center justify-center text-white shadow-lg shadow-teal-600/20">
                        <i data-lucide="history" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800">Medical History</h3>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Visit Timeline: <?php echo e($apt['patient_name']); ?></p>
                    </div>
                </div>
                <button @click="showHistory = false" class="w-12 h-12 bg-white hover:bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 transition-all shadow-sm border border-slate-100">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-10 space-y-8 bg-white custom-scrollbar">
                <template x-if="historyData.length === 0">
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-6">
                            <i data-lucide="ghost" class="w-10 h-10"></i>
                        </div>
                        <h4 class="text-slate-800 font-black text-lg">No Previous Visits</h4>
                        <p class="text-slate-400 text-sm max-w-xs mt-2 font-medium">This patient has no recorded consultation history in the platform yet.</p>
                    </div>
                </template>
                <template x-for="visit in historyData" :key="visit.id">
                    <div class="relative pl-12 border-l-2 border-teal-100 last:border-transparent">
                        <div class="absolute left-[-9px] top-0 w-4 h-4 rounded-full bg-teal-500 border-4 border-white ring-4 ring-teal-50 shadow-sm"></div>
                        <div class="bg-slate-50/50 border border-slate-100 p-8 rounded-[2.5rem] hover:bg-white hover:shadow-xl hover:shadow-slate-200/40 transition-all duration-500 group">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <span class="text-[10px] font-black text-teal-600 uppercase tracking-widest bg-teal-50 px-3 py-1 rounded-full" x-text="new Date(visit.completed_at).toLocaleDateString(undefined, { dateStyle: 'long' })"></span>
                                    <h4 class="text-lg font-black text-slate-800 mt-2" x-text="'Dr. ' + visit.doctor_name"></h4>
                                </div>
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 group-hover:text-teal-500 transition-colors shadow-sm border border-slate-50">
                                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Diagnosis</p>
                                    <p class="text-sm font-bold text-slate-700 leading-relaxed" x-text="visit.diagnosis || 'No diagnosis recorded'"></p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Clinical Notes</p>
                                    <p class="text-xs font-medium text-slate-500 leading-relaxed italic" x-text="visit.notes || 'No notes recorded'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Modal: Lab Repository -->
    <div x-show="showReports" class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/40 backdrop-blur-md" x-cloak>
        <div class="bg-white w-full max-w-4xl max-h-[80vh] rounded-[3.5rem] shadow-2xl overflow-hidden flex flex-col border-[6px] border-white" @click.away="showReports = false">
            <div class="p-10 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 bg-orange-500 rounded-[1.2rem] flex items-center justify-center text-white shadow-lg shadow-orange-500/20">
                        <i data-lucide="file-text" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800">Lab Repository</h3>
                        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Secure Documents: <?php echo e($apt['patient_name']); ?></p>
                    </div>
                </div>
                <button @click="showReports = false" class="w-12 h-12 bg-white hover:bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 transition-all shadow-sm border border-slate-100">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-10 bg-white">
                <template x-if="reportsData.length === 0">
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-6">
                            <i data-lucide="file-warning" class="w-10 h-10"></i>
                        </div>
                        <h4 class="text-slate-800 font-black text-lg">No Documents Found</h4>
                        <p class="text-slate-400 text-sm max-w-xs mt-2 font-medium">There are no diagnostic reports or clinical documents uploaded for this patient.</p>
                    </div>
                </template>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <template x-for="doc in reportsData" :key="doc.id">
                        <div class="bg-white border border-slate-100 p-6 rounded-[2.5rem] flex items-center justify-between hover:shadow-xl hover:shadow-slate-200/40 transition-all group relative overflow-hidden">
                            <!-- Background Decoration -->
                            <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 -mr-12 -mt-12 rounded-full opacity-50 group-hover:bg-orange-50 transition-colors"></div>
                            
                            <div class="flex items-center gap-5 relative z-10">
                                <div class="w-14 h-14 bg-slate-50 group-hover:bg-orange-500 group-hover:text-white rounded-2xl flex items-center justify-center text-orange-500 transition-all shadow-sm border border-slate-100 group-hover:border-transparent">
                                    <template x-if="doc.file_url.toLowerCase().endsWith('.pdf')">
                                        <i data-lucide="file-type-2" class="w-7 h-7"></i>
                                    </template>
                                    <template x-if="!doc.file_url.toLowerCase().endsWith('.pdf')">
                                        <i data-lucide="image" class="w-7 h-7"></i>
                                    </template>
                                </div>
                                <div>
                                    <h5 class="text-sm font-black text-slate-800 line-clamp-1" x-text="doc.title || 'Untitled Report'"></h5>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest" x-text="new Date(doc.created_at).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' })"></span>
                                        <span class="w-1 h-1 bg-slate-200 rounded-full"></span>
                                        <span class="text-[9px] font-black text-orange-500 uppercase tracking-widest" x-text="doc.file_url.split('.').pop()"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 relative z-10">
                                <a :href="'../' + doc.file_url" target="_blank" class="w-10 h-10 bg-slate-50 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center text-slate-400 transition-all shadow-sm border border-slate-100 group/view" title="View Fullscreen">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                </a>
                                <a :href="'../' + doc.file_url" download class="w-10 h-10 bg-slate-50 hover:bg-teal-600 hover:text-white rounded-xl flex items-center justify-center text-slate-400 transition-all shadow-sm border border-slate-100 group/down" title="Download">
                                    <i data-lucide="download" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'components/footer.php'; ?>
