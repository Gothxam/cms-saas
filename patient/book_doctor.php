<?php
require_once '../core/init.php';
Auth::protect('Patient');

$doctor_id = $_GET['id'] ?? 0;
$db = getDB();

$stmt = $db->prepare("
    SELECT u.name, u.email, dp.* 
    FROM users u 
    JOIN doctor_profiles dp ON u.id = dp.user_id 
    WHERE u.id = ?
");
$stmt->execute([$doctor_id]);
$doctor = $stmt->fetch();

if (!$doctor) {
    header('Location: doctors.php');
    exit;
}

$page_title = "Book Appointment with Dr. " . $doctor['name'];
require_once 'components/header.php';
require_once 'components/sidebar.php';
?>

<div class="max-w-6xl mx-auto py-8 px-6 animate-in fade-in duration-500" x-data="bookingApp(<?php echo $doctor_id; ?>)">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <!-- Left: Doctor Profile -->
        <div class="lg:col-span-5 space-y-8">
            <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm text-center relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-50 rounded-full blur-3xl opacity-50"></div>
                
                <div class="relative mb-6">
                    <div class="w-28 h-28 bg-teal-600 rounded-[2.5rem] flex items-center justify-center text-white text-4xl font-black mx-auto shadow-2xl shadow-teal-600/20">
                        <span><?php echo strtoupper(substr($doctor['name'], 0, 1)); ?></span>
                    </div>
                </div>

                <h2 class="text-2xl font-black text-slate-900">Dr. <?php echo e($doctor['name']); ?></h2>
                <p class="text-teal-600 font-black text-[10px] uppercase tracking-[0.2em] mt-2"><?php echo e($doctor['specialization'] ?: 'General Physician'); ?></p>
                
                <div class="flex items-center justify-center gap-6 mt-8">
                    <div class="text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Experience</p>
                        <p class="text-sm font-black text-slate-800"><?php echo $doctor['experience']; ?> Years</p>
                    </div>
                    <div class="w-px h-8 bg-slate-100"></div>
                    <div class="text-center">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Consultation</p>
                        <p class="text-sm font-black text-slate-800">₹<?php echo number_format($doctor['video_fee'], 0); ?></p>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-50 text-left">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">About Doctor</h4>
                    <p class="text-xs font-medium text-slate-600 leading-relaxed">
                        <?php echo e($doctor['bio'] ?: 'Dr. ' . $doctor['name'] . ' is a highly experienced ' . ($doctor['specialization'] ?: 'medical professional') . ' committed to providing excellent patient care.'); ?>
                    </p>
                </div>

                <div class="mt-6 flex flex-wrap gap-2 justify-center">
                    <?php if($doctor['qualifications']): ?>
                        <?php foreach(explode(',', $doctor['qualifications']) as $q): ?>
                            <span class="px-3 py-1.5 bg-slate-50 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest"><?php echo e(trim($q)); ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Clinic Info -->
            <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white flex items-center gap-6">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-teal-400">
                    <i data-lucide="map-pin" class="w-7 h-7"></i>
                </div>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-widest text-white/50">Location</h4>
                    <p class="text-sm font-bold mt-1"><?php echo e($doctor['clinic_name'] ?: 'Available for Video Consultation'); ?></p>
                </div>
            </div>
        </div>

        <!-- Right: Slot Selection -->
        <div class="lg:col-span-7 space-y-8">
            
            <!-- Date Selection -->
            <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Select Date</h3>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1">Check availability</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 overflow-x-auto pb-4 custom-scrollbar">
                    <template x-for="date in nextDates" :key="date.full">
                        <button @click="selectedDate = date.full; fetchSlots()" 
                            :class="selectedDate === date.full ? 'bg-slate-900 text-white shadow-xl shadow-slate-900/20' : 'bg-slate-50 text-slate-400 hover:bg-slate-100'"
                            class="flex flex-col items-center min-w-[80px] p-4 rounded-3xl transition-all">
                            <span class="text-[9px] font-black uppercase tracking-widest mb-2" x-text="date.day"></span>
                            <span class="text-lg font-black" x-text="date.date"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Slots Selection -->
            <div class="bg-white p-10 rounded-[3.5rem] border border-slate-100 shadow-sm min-h-[400px] flex flex-col">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Select Time</h3>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mt-1" x-text="slots.length + ' slots available'"></p>
                    </div>
                </div>

                <div x-show="isLoadingSlots" class="flex-1 flex flex-col items-center justify-center py-20 animate-pulse">
                    <div class="w-12 h-12 border-4 border-slate-100 border-t-teal-600 rounded-full animate-spin mb-4"></div>
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest">Checking Slots...</p>
                </div>

                <div x-show="!isLoadingSlots && slots.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                    <template x-for="slot in slots" :key="slot.time">
                        <button @click="slot.available ? selectedSlot = slot.time : null" 
                            :disabled="!slot.available"
                            :class="[
                                selectedSlot === slot.time ? 'bg-teal-600 text-white shadow-xl shadow-teal-600/30 border-teal-600' : 'bg-white border-slate-100 text-slate-700 hover:border-teal-500 hover:text-teal-600',
                                !slot.available ? 'opacity-30 cursor-not-allowed bg-slate-50' : 'cursor-pointer'
                            ]"
                            class="p-4 rounded-2xl border-2 text-center transition-all">
                            <span class="text-xs font-black" x-text="slot.label"></span>
                        </button>
                    </template>
                </div>

                <div x-show="!isLoadingSlots && slots.length === 0" class="flex-1 flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mb-4">
                        <i data-lucide="calendar-off" class="w-8 h-8"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-bold">No slots available for this date.</p>
                </div>

                <!-- Booking Action -->
                <div x-show="selectedSlot" class="mt-10 pt-10 border-t border-slate-50 animate-in slide-in-from-bottom-4">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Appointment</p>
                            <p class="text-sm font-black text-slate-800">
                                <span x-text="formatFullDate(selectedDate)"></span> at <span x-text="formatTime(selectedSlot)"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Fee</p>
                            <p class="text-lg font-black text-teal-600">₹<?php echo number_format($doctor['video_fee'], 0); ?></p>
                        </div>
                    </div>
                    <button @click="confirmBooking()" class="w-full bg-slate-900 text-white p-6 rounded-[2rem] font-black text-xs uppercase tracking-[0.3em] shadow-2xl shadow-slate-900/30 hover:bg-slate-800 transition-all flex items-center justify-center gap-4 group">
                        <template x-if="isBooking">
                            <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        </template>
                        <span x-text="isBooking ? 'Processing...' : 'Confirm Booking'"></span>
                        <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-2 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function bookingApp(doctorId) {
    return {
        doctorId: doctorId,
        nextDates: [],
        selectedDate: '',
        selectedSlot: '',
        slots: [],
        isLoadingSlots: false,
        isBooking: false,

        init() {
            this.generateNextDates();
            this.selectedDate = this.nextDates[0].full;
            this.fetchSlots();
        },

        generateNextDates() {
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            for (let i = 0; i < 14; i++) {
                const d = new Date();
                d.setDate(d.getDate() + i);
                this.nextDates.push({
                    full: d.toISOString().split('T')[0],
                    date: d.getDate(),
                    day: days[d.getDay()]
                });
            }
        },

        fetchSlots() {
            this.selectedSlot = '';
            this.isLoadingSlots = true;
            fetch(`../api/booking_actions.php?action=get_slots&doctor_id=${this.doctorId}&date=${this.selectedDate}`)
                .then(r => r.json())
                .then(data => {
                    this.slots = data.slots || [];
                    this.isLoadingSlots = false;
                    this.$nextTick(() => lucide.createIcons());
                });
        },

        formatFullDate(dateStr) {
            return new Date(dateStr).toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
        },

        formatTime(timeStr) {
            const [h, m] = timeStr.split(':');
            const hour = parseInt(h);
            return (hour % 12 || 12) + ':' + m + (hour < 12 ? ' AM' : ' PM');
        },

        confirmBooking() {
            if (this.isBooking) return;
            this.isBooking = true;
            
            fetch('../api/booking_actions.php?action=book', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    doctor_id: this.doctorId,
                    date: this.selectedDate,
                    time: this.selectedSlot,
                    type: 'Video'
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'appointments.php?success=booked';
                } else {
                    alert(data.error || 'Failed to book appointment');
                    this.isBooking = false;
                }
            });
        }
    }
}
</script>

<?php require_once 'components/footer.php'; ?>
