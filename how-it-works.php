<?php
// how-it-works.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How it Works | MedOS Clinical Implementation</title>

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

<body class="bg-[#fcfdfd] text-slate-600 font-sans selection:bg-emerald-100 selection:text-emerald-900">

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
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">How it Works</a>
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="login.php" class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign In</a>
                <a href="login.php" class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Interactive Hero -->
    <header class="relative pt-48 pb-32 overflow-hidden bg-white">
        <!-- Ambient Background Glows -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-[120px] opacity-60 animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-teal-50 rounded-full blur-[120px] opacity-60 animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full shadow-sm mb-12">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Zero-Migration Stress</span>
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 tracking-tightest mb-10 leading-[1.05]">
                Onboard in minutes. <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">Save hours every day.</span>
            </h1>
            <p class="text-xl md:text-2xl text-slate-500 font-medium leading-relaxed max-w-3xl mx-auto mb-16 italic">
                We've eliminated the technical friction of medical software. Here is your roadmap to a more efficient, modern clinic.
            </p>
            
            <!-- Process Progress Bar Visual -->
            <div class="max-w-3xl mx-auto flex items-center justify-between relative mt-24">
                <div class="absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 -z-10 rounded-full">
                    <div class="w-1/2 h-full bg-emerald-500 rounded-full"></div>
                </div>
                <div class="w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                </div>
                <div class="w-12 h-12 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <div class="w-12 h-12 bg-white text-slate-300 border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                    <i data-lucide="zap" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- The 3-Step Clinical Roadmap -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-12">
                
                <!-- Step 1 -->
                <div class="relative p-10 bg-slate-50 border border-slate-100 rounded-[3rem] hover:bg-white hover:shadow-2xl transition-all duration-700 group">
                    <div class="absolute -top-6 -left-6 w-16 h-16 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl">01</div>
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <i data-lucide="settings" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Connect Your Clinic</h3>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm">
                        Create your account and personalize your clinical portal with your logo, colors, and staff details. Our wizard handles the technical heavy lifting.
                    </p>
                    <ul class="mt-8 space-y-3">
                        <li class="flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            <i data-lucide="check" class="w-3 h-3"></i> Custom Branding
                        </li>
                        <li class="flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            <i data-lucide="check" class="w-3 h-3"></i> Staff Permissions
                        </li>
                    </ul>
                </div>

                <!-- Step 2 -->
                <div class="relative p-10 bg-slate-50 border border-slate-100 rounded-[3rem] hover:bg-white hover:shadow-2xl transition-all duration-700 group lg:translate-y-12">
                    <div class="absolute -top-6 -left-6 w-16 h-16 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl">02</div>
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <i data-lucide="refresh-cw" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Migrate with Ease</h3>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm">
                        Use our Smart Import tools to move patient history and medical records into the cloud. Our team is available to assist with custom data mapping.
                    </p>
                    <ul class="mt-8 space-y-3">
                        <li class="flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            <i data-lucide="check" class="w-3 h-3"></i> Bulk File Upload
                        </li>
                        <li class="flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            <i data-lucide="check" class="w-3 h-3"></i> History Preservation
                        </li>
                    </ul>
                </div>

                <!-- Step 3 -->
                <div class="relative p-10 bg-slate-50 border border-slate-100 rounded-[3rem] hover:bg-white hover:shadow-2xl transition-all duration-700 group lg:translate-y-24">
                    <div class="absolute -top-6 -left-6 w-16 h-16 bg-slate-900 text-white rounded-2xl flex items-center justify-center font-black text-2xl shadow-xl">03</div>
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-8 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                        <i data-lucide="rocket" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Launch & Optimize</h3>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm">
                        Activate your patient-facing booking site and start using the clinical dashboard. Experience immediate relief from administrative paperwork.
                    </p>
                    <ul class="mt-8 space-y-3">
                        <li class="flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            <i data-lucide="check" class="w-3 h-3"></i> Live in 30 mins
                        </li>
                        <li class="flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                            <i data-lucide="check" class="w-3 h-3"></i> 24/7 Support
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- The Efficiency Engine (Visual) -->
    <section class="py-40 bg-[#0b1120] relative overflow-hidden mt-32">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10 grid lg:grid-cols-2 gap-24 items-center">
            <div class="space-y-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[10px] font-black text-emerald-400 uppercase tracking-widest">
                    The Clinical Engine
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter leading-tight">
                    Behind the scenes <br>
                    <span class="text-emerald-500">of your success.</span>
                </h2>
                <p class="text-slate-400 text-lg font-medium leading-relaxed italic">
                    "MedOS isn't just a database. It's an intelligent engine that predicts your staff's needs and automates the busiest parts of your day."
                </p>
                <div class="space-y-6">
                    <div class="flex items-start gap-6 group">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-lg">Smart Scheduling</h4>
                            <p class="text-slate-500 text-sm">Automated reminders reduce no-shows by up to 60%.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-6 group">
                        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                            <i data-lucide="bar-chart" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold text-lg">Predictive Analytics</h4>
                            <p class="text-slate-500 text-sm">See your clinic's performance and peak hours in real-time.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative group">
                <div class="absolute inset-0 bg-emerald-500/20 rounded-full blur-[120px] -z-10 animate-pulse"></div>
                <img src="how_it_works_workflow_v2_1777718830106.png" alt="Workflow Dashboard" class="w-full h-auto rounded-[3rem] shadow-2xl group-hover:scale-105 transition-transform duration-700">
            </div>
        </div>
    </section>

    <!-- Advanced FAQ (Clinical Focus) -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 relative z-10" x-data="{ active: null }">
            <div class="text-center mb-24">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    Logistics & Trust
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-4">You have questions. <br> We have answers.</h2>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="bg-slate-50 border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500" :class="active === 1 ? 'bg-white ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-bold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Who owns my patient data?</span>
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center group-hover:bg-emerald-50 transition-colors shadow-sm">
                             <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 1 ? 'rotate-45 text-emerald-600' : ''"></i>
                        </div>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-base font-medium leading-relaxed pt-2">
                            You do. MedOS is merely the custodian of your data. You can export your patient records, clinical history, and billing data at any time in standard formats.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-slate-50 border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500" :class="active === 2 ? 'bg-white ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-bold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">How hard is staff training?</span>
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center group-hover:bg-emerald-50 transition-colors shadow-sm">
                             <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 2 ? 'rotate-45 text-emerald-600' : ''"></i>
                        </div>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-base font-medium leading-relaxed pt-2">
                            We designed MedOS for zero training. If your staff can use a smartphone, they can use MedOS. Most teams are proficient within the first afternoon of usage.
                        </div>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-slate-50 border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500" :class="active === 3 ? 'bg-white ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-bold text-slate-900 tracking-tight group-hover:text-emerald-600 transition-colors">Does it work offline?</span>
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center group-hover:bg-emerald-50 transition-colors shadow-sm">
                             <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 3 ? 'rotate-45 text-emerald-600' : ''"></i>
                        </div>
                    </button>
                    <div x-show="active === 3" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-base font-medium leading-relaxed pt-2">
                            MedOS is a cloud-native platform for maximum security and sync. While it requires an internet connection, it is optimized for low-bandwidth environments to ensure clinical continuity.
                        </div>
                    </div>
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
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-12 gap-16 mb-24">
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

