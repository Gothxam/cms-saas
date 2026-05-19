<?php
// contact.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Our Team | MedOS Clinical Partnership</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        emerald: { 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#10b981', 600: '#059669', 700: '#047857', 800: '#065f46', 900: '#064e3b' }
                    },
                    letterSpacing: {
                        tightest: '-.04em',
                        tighter: '-.03em',
                    }
                }
            }
        }
    </script>
    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .emerald-gradient { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .text-gradient { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#fcfdfd] text-slate-600 font-sans selection:bg-emerald-100 selection:text-emerald-900" x-data="{ tab: 'demo' }">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-[100] border-b border-slate-100/50 glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span class="text-emerald-600">OS</span></span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="index.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Home</a>
                <a href="about.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">About Us</a>
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">How it Works</a>
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="login.php" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign In</a>
                <a href="login.php" class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Immersive Hero -->
    <header class="relative pt-48 pb-32 overflow-hidden bg-white">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-[120px] opacity-60 animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-teal-50 rounded-full blur-[120px] opacity-60 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full shadow-sm mb-12">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Clinical Concierge</span>
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 tracking-tightest mb-10 leading-[1.05]">
                Let's build your <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">modern practice.</span>
            </h1>
            <p class="text-xl md:text-2xl text-slate-500 font-medium leading-relaxed max-w-3xl mx-auto mb-16 italic">
                Whether you're looking for a personalized demo or need mission-critical technical support, our team is standing by.
            </p>
        </div>
    </header>

    <!-- Contact Hub -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-12 gap-20">
            
            <!-- Left: Info & Details -->
            <div class="lg:col-span-4 space-y-12">
                <div class="space-y-8">
                    <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.3em]">Contact Details</h4>
                    
                    <div class="group flex gap-6 p-6 bg-slate-50 border border-slate-100 rounded-3xl hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                            <i data-lucide="map-pin" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Global HQ</p>
                            <p class="text-sm font-bold text-slate-900 leading-relaxed">123 Clinical Plaza, Innovation District<br>New York, NY 10001</p>
                        </div>
                    </div>

                    <div class="group flex gap-6 p-6 bg-slate-50 border border-slate-100 rounded-3xl hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                            <i data-lucide="mail" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Email Us</p>
                            <p class="text-sm font-bold text-slate-900 leading-relaxed">hello@medos-clinical.com<br>sales@medos-clinical.com</p>
                        </div>
                    </div>

                    <div class="group flex gap-6 p-6 bg-slate-50 border border-slate-100 rounded-3xl hover:bg-white hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                            <i data-lucide="phone" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Call Support</p>
                            <p class="text-sm font-bold text-slate-900 leading-relaxed">+1 (800) MED-OS-01<br>Mon-Fri, 9am - 6pm EST</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-emerald-600 rounded-[2.5rem] text-white relative overflow-hidden shadow-2xl shadow-emerald-600/30">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                    <i data-lucide="shield-check" class="w-10 h-10 mb-6 opacity-50"></i>
                    <h5 class="text-xl font-black mb-3 leading-tight">Priority Support for Professionals</h5>
                    <p class="text-sm text-emerald-50 font-medium leading-relaxed mb-6 opacity-80">Our Practice Plan users get a dedicated 2-hour response time for all clinical queries.</p>
                    <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-white border-b-2 border-white/30 hover:border-white transition-all pb-1">Upgrade your support tier</a>
                </div>
            </div>

            <!-- Right: Interactive Form -->
            <div class="lg:col-span-8">
                <!-- Form Switcher -->
                <div class="flex p-1.5 bg-slate-100 rounded-[3rem] mb-12 max-w-md">
                    <button @click="tab = 'demo'" :class="tab === 'demo' ? 'bg-white text-slate-900 shadow-xl' : 'text-slate-400'" 
                            class="flex-1 py-4 rounded-[2.5rem] text-[10px] font-black uppercase tracking-widest transition-all">Book a Demo</button>
                    <button @click="tab = 'support'" :class="tab === 'support' ? 'bg-white text-slate-900 shadow-xl' : 'text-slate-400'" 
                            class="flex-1 py-4 rounded-[2.5rem] text-[10px] font-black uppercase tracking-widest transition-all">Technical Support</button>
                </div>

                <div class="bg-white p-10 md:p-16 rounded-[4rem] border border-slate-100 shadow-2xl shadow-emerald-900/5 relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl -z-10"></div>
                    
                    <!-- Demo Form -->
                    <form x-show="tab === 'demo'" x-cloak class="space-y-8 animate-in fade-in duration-500">
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                                <input type="text" placeholder="Dr. John Smith" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Clinic Email</label>
                                <input type="email" placeholder="john@clinic.com" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Clinic Size</label>
                                <select class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 outline-none transition-all appearance-none">
                                    <option>Solo Practitioner</option>
                                    <option>2-5 Doctors</option>
                                    <option>5-20 Doctors</option>
                                    <option>Large Hospital Network</option>
                                </select>
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Desired Implementation Date</label>
                                <input type="text" placeholder="e.g. Next Month" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Message</label>
                            <textarea rows="4" placeholder="How can we help your practice?" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all"></textarea>
                        </div>
                        <button type="submit" class="w-full py-6 emerald-gradient text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-[1.02] active:scale-[0.98] transition-all">Request Implementation Call</button>
                    </form>

                    <!-- Support Form -->
                    <form x-show="tab === 'support'" x-cloak class="space-y-8 animate-in fade-in duration-500">
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Clinic License ID</label>
                                <input type="text" placeholder="MD-XXXX-XXXX" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Severity</label>
                                <select class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 outline-none transition-all appearance-none">
                                    <option>Low - Question / Inquiry</option>
                                    <option>Medium - Minor Bug</option>
                                    <option>High - Clinical Blocker</option>
                                    <option>Critical - System Access Issue</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Incident Details</label>
                            <textarea rows="6" placeholder="Describe the issue in detail..." class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-6 py-5 text-slate-700 font-bold text-sm focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 outline-none transition-all"></textarea>
                        </div>
                        <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[2rem] font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all">Open Critical Ticket</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Global Departments -->
    <section class="py-32 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tightest leading-tight">Reach the right <br> <span class="text-emerald-600">experts.</span></h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Sales -->
                <div class="p-12 bg-white border border-slate-100 rounded-[3.5rem] hover:shadow-2xl transition-all duration-700 group text-center">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i data-lucide="bar-chart-3" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2">Clinical Sales</h4>
                    <p class="text-sm text-slate-500 font-medium mb-6">Talk to us about volume pricing and multi-clinic setups.</p>
                    <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">sales@medos.com</p>
                </div>

                <!-- Media -->
                <div class="p-12 bg-white border border-slate-100 rounded-[3.5rem] hover:shadow-2xl transition-all duration-700 group text-center">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i data-lucide="megaphone" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2">Media & Press</h4>
                    <p class="text-sm text-slate-500 font-medium mb-6">Inquiries regarding partnerships and brand features.</p>
                    <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">media@medos.com</p>
                </div>

                <!-- Careers -->
                <div class="p-12 bg-white border border-slate-100 rounded-[3.5rem] hover:shadow-2xl transition-all duration-700 group text-center">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-sm">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2">Join Our Team</h4>
                    <p class="text-sm text-slate-500 font-medium mb-6">We're always looking for clinical and tech talent.</p>
                    <p class="text-xs font-black text-emerald-600 uppercase tracking-widest">careers@medos.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA (Green Theme Sync) -->
    <section class="py-32 bg-emerald-600 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="max-w-6xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-5xl md:text-7xl font-black text-white tracking-tightest mb-12 leading-[1.1]">
                Your modern practice <br> starts right here.
            </h2>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 mb-16">
                <a href="login.php" class="w-full sm:w-auto px-10 py-5 bg-white text-emerald-600 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl shadow-emerald-900/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                    Launch MedOS Now
                </a>
                <a href="contact.php" class="w-full sm:w-auto px-10 py-5 bg-emerald-800 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-900 transition-all flex items-center justify-center gap-2">
                    Book Implementation
                </a>
            </div>
            <p class="text-[10px] font-black text-emerald-100 uppercase tracking-[0.4em] opacity-60">
                No hidden fees. No patient limits. Professional standards only.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 pt-24 pb-12">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-12 gap-16 mb-24 text-left">
            <!-- Brand Column -->
            <div class="md:col-span-4">
                <a href="index.php" class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                    </div>
                    <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span class="text-emerald-600">OS</span></span>
                </a>
                <p class="text-sm text-slate-500 font-medium leading-relaxed max-w-sm mb-10">
                    The OS for modern practices. High-fidelity clinical management built for the next generation of healthcare delivery.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 border border-slate-100/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 border border-slate-100/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 border border-slate-100/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                </div>
            </div>

            <!-- Links Columns -->
            <div class="md:col-span-2">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-8">Product</h4>
                <ul class="space-y-4">
                    <li><a href="how-it-works.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">How it Works</a></li>
                    <li><a href="pricing.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Pricing Plans</a></li>
                    <li><a href="login.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Staff Portal</a></li>
                    <li><a href="login.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Patient Portal</a></li>
                </ul>
            </div>

            <div class="md:col-span-2">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-8">Company</h4>
                <ul class="space-y-4">
                    <li><a href="about.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Our Mission</a></li>
                    <li><a href="contact.php" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Contact Sales</a></li>
                    <li><a href="#" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Security Standards</a></li>
                    <li><a href="#" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="md:col-span-4">
                <h4 class="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-8">Stay Updated</h4>
                <p class="text-xs text-slate-500 font-bold mb-6">Join our newsletter for clinical management tips.</p>
                <div class="relative group">
                    <input type="email" placeholder="Enter your email" class="w-full px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500/50 transition-all">
                    <button class="absolute right-2 top-2 bottom-2 px-4 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all">Join</button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pt-12 border-t border-slate-50 flex flex-col md:row justify-between items-center gap-6">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">
                © 2026 MedOS Clinical Systems. All rights reserved. Built with excellence.
            </p>
        </div>
    </footer>


    <script>
        window.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
