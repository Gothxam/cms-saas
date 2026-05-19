<?php
// index.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedOS | The Operating System for Modern Medical Practices</title>
    
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
                    }
                }
            }
        }
    </script>
    
    <style>
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .emerald-gradient { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .text-gradient { background: linear-gradient(135deg, #064e3b 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-[#fcfdfd] text-slate-600 font-sans selection:bg-emerald-100 selection:text-emerald-900">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-[100] border-b border-slate-100/50 glass">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-3">
                <div
                    class="w-10 h-10 emerald-gradient text-white rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <i data-lucide="heart-pulse" class="w-6 h-6"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter uppercase text-slate-900">MED<span
                        class="text-emerald-600">OS</span></span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="index.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Home</a>
                <a href="about.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">About
                    Us</a>
                <a href="how-it-works.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">How
                    it Works</a>
                <a href="pricing.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php"
                    class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Contact</a>
            </div>

            <div class="flex items-center gap-4">
                <a href="login.php"
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">Sign
                    In</a>
                <a href="login.php"
                    class="px-6 py-2.5 emerald-gradient text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all">Get
                    Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-32 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10">
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-3xl opacity-60">
            </div>
            <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-teal-50 rounded-full blur-3xl opacity-60">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center">
            <div class="text-left">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-full text-emerald-700 text-[10px] font-black uppercase tracking-widest mb-8">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    The Operating System for Modern Healthcare
                </div>

                <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tighter leading-[1.1] mb-8">
                    Modern software for your clinic. <br>
                    <span class="text-gradient">Better care for your patients.</span>
                </h1>

                <p class="text-xl text-slate-500 font-medium mb-12">
                    Stop using paper and old, slow software. MedOS is a fast and easy way to manage your entire clinic in one place.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 mb-10">
                    <a href="login.php"
                        class="w-full sm:w-auto px-10 py-5 emerald-gradient text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-600/30 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-3">
                        Launch Your Practice <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                    <a href="how-it-works.php"
                        class="w-full sm:w-auto px-10 py-5 bg-white border border-slate-100 text-slate-600 rounded-2xl font-black text-xs uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all flex items-center justify-center gap-3">
                        See How it Works
                    </a>
                </div>

                <!-- Floating Trust Badges Inline -->
                <div class="flex flex-wrap gap-3">
                    <div
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-100 rounded-xl shadow-sm">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">AES-256
                            Secure</span>
                    </div>
                    <div
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-100 rounded-xl shadow-sm">
                        <i data-lucide="clock" class="w-4 h-4 text-emerald-500"></i>
                        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Real-time
                            Sync</span>
                    </div>
                </div>
            </div>

            <!-- Hero Visual -->
            <div class="relative">
                <div class="absolute inset-0 bg-emerald-500/10 blur-[100px] rounded-full -z-10 animate-pulse"></div>
                <div class="p-3 bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl scale-110">
                    <img src="medos_hero.png" alt="MedOS Clinical Dashboard" class="w-full h-auto rounded-3xl">
                </div>

                <!-- Mini Float Stats -->
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-3xl shadow-2xl border border-slate-50 animate-float z-20"
                    style="animation-delay: 1s;">
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-1">Clinic Performance</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter">+42% Efficiency</p>
                </div>
            </div>
        </div>

        <!-- Trusted By Logos -->

        <!-- Clinical Impact: Trust by the Numbers -->
        <div class="pt-24 border-t border-slate-100/50">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-12">
                <!-- Stat 1 -->
                <div class="text-center px-6">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">3,200+</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Active Practices</p>
                </div>
                <!-- Stat 2 -->
                <div class="text-center px-6 border-l border-slate-100">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">1.2M+</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Patients Managed</p>
                </div>
                <!-- Stat 3 -->
                <div class="text-center px-6 border-l border-slate-100">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">99.9%</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Clinical Uptime</p>
                </div>
                <!-- Stat 4 -->
                <div class="text-center px-6 border-l border-slate-100">
                    <p class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2">AES-256</p>
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Security Standard</p>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- The "Mistake" Section -->
    <section class="py-16 bg-[#fbfdfb] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            
            <!-- Warning Badge -->
            <div class="inline-flex items-center gap-2 px-6 py-2 bg-emerald-600 rounded-full text-xs font-black text-white uppercase tracking-widest mb-8 shadow-lg shadow-emerald-200">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                Are You Making This Mistake?
            </div>

            <!-- Main Heading -->
            <div class="relative mb-12">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter mb-2 leading-tight">
                    Still Managing Your Clinic <br>
                    <span class="text-emerald-600">Without Modern Software?</span>
                </h2>
                <div class="w-24 h-1 bg-emerald-500/30 mx-auto rounded-full"></div>
            </div>

            <!-- Grid of Cards -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <!-- Card 1 -->
                <div class="p-8 bg-white border border-emerald-100/50 rounded-[2.5rem] shadow-[0_15px_40px_rgba(16,185,129,0.03)] hover:shadow-[0_30px_60px_rgba(16,185,129,0.06)] hover:border-emerald-600 transition-all duration-500 group text-center cursor-pointer">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i data-lucide="search" class="w-10 h-10"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2 tracking-tight">Patients Search Google First</h4>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm">
                        90% of patients check online reviews & software before choosing a doctor. If you're not found, you're losing them to competitors.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="p-8 bg-white border border-emerald-100/50 rounded-[2.5rem] shadow-[0_15px_40px_rgba(16,185,129,0.03)] hover:shadow-[0_30px_60px_rgba(16,185,129,0.06)] hover:border-emerald-600 transition-all duration-500 group text-center cursor-pointer">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i data-lucide="user-minus" class="w-10 h-10"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2 tracking-tight">No Online Presence = Lost Patients</h4>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm">
                        Every day without professional software means missed appointments, lower trust, and fewer new patients walking through your door.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="p-8 bg-white border border-emerald-100/50 rounded-[2.5rem] shadow-[0_15px_40px_rgba(16,185,129,0.03)] hover:shadow-[0_30px_60px_rgba(16,185,129,0.06)] hover:border-emerald-600 transition-all duration-500 group text-center cursor-pointer">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-inner">
                        <i data-lucide="calendar-x" class="w-10 h-10"></i>
                    </div>
                    <h4 class="text-xl font-black text-slate-900 mb-2 tracking-tight">Manual Booking = Chaos</h4>
                    <p class="text-slate-500 font-medium leading-relaxed text-sm">
                        Phone tag, double bookings, missed calls, and frustrated staff. Your clinic deserves better than outdated software.
                    </p>
                </div>
            </div>

            <!-- Bottom Warning Banner -->
            <div class="bg-emerald-600 rounded-[2rem] p-6 md:p-8 text-left relative overflow-hidden shadow-xl shadow-emerald-100">
                <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center text-white shrink-0">
                            <i data-lucide="trending-up" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base md:text-xl font-black text-white tracking-tight leading-tight">
                                Your competitors are already getting patients online — <br class="hidden md:block"> 
                                <span class="text-emerald-100/80 text-sm md:text-base">while you're losing them daily.</span>
                            </h3>
                        </div>
                    </div>
                    <a href="#contact" class="px-6 py-2.5 bg-white text-slate-900 font-black rounded-xl shadow-lg hover:bg-emerald-50 transition-all flex items-center gap-2 shrink-0 text-xs">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        Don't wait
                    </a>
                </div>
            </div>

            <!-- Final CTA Button -->
            <div class="mt-8 flex flex-col items-center gap-3">
                <a href="#pricing" class="inline-flex items-center gap-2 px-8 py-4 bg-emerald-600 text-white font-black text-lg rounded-2xl shadow-[0_15px_30px_rgba(16,185,129,0.15)] hover:shadow-[0_20px_40px_rgba(16,185,129,0.25)] hover:-translate-y-0.5 transition-all duration-300">
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    Get Your Software Before Patients Go Elsewhere
                </a>
                <p class="flex items-center gap-2 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                    <i data-lucide="lock" class="w-3 h-3"></i>
                    Free consultation | No obligation
                </p>
            </div>

        </div>
    </section>

    <!-- The "Everything Included" Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-6 py-2 bg-emerald-600 rounded-full text-xs font-black text-white uppercase tracking-widest mb-10 shadow-lg shadow-emerald-100">
                <i data-lucide="package-plus" class="w-4 h-4"></i>
                Everything Included
            </div>

            <!-- Main Heading -->
            <div class="max-w-4xl mx-auto mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter mb-6 leading-tight">
                    Everything Included in <br class="hidden md:block">
                    <span class="text-emerald-600">Your Software Package</span>
                </h2>
                <div class="w-24 h-1 bg-emerald-500/30 mx-auto rounded-full mb-8"></div>
                <p class="text-slate-500 font-medium leading-relaxed text-lg max-w-2xl mx-auto">
                    No hidden costs. No surprises. Just a complete, ready-to-launch healthcare software platform.
                </p>
            </div>

            <!-- Grid of 8 Technical Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-16">
                <!-- Card 1 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="user-cog" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">Doctor Profile Pages</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Individual profiles with qualifications, experience, and hours.</p>
                </div>

                <!-- Card 2 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">WhatsApp Integration</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Click-to-chat WhatsApp button. Patients message you directly.</p>
                </div>

                <!-- Card 3 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="map-pin" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">Google Map Integration</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Interactive map with directions, location details & reviews.</p>
                </div>

                <!-- Card 4 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="search" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">SEO Optimized Pages</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Meta tags, headings, and local SEO setup for "near me" searches.</p>
                </div>

                <!-- Card 5 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="zap" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">Fast Loading Software</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Optimized images and CDN for < 2s load time on mobile.</p>
                </div>

                <!-- Card 6 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">Admin Dashboard</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Easy-to-use interface to manage doctors and patient data.</p>
                </div>

                <!-- Card 7 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="lock" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">SSL & Security</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Bank-level encryption and daily backups are included.</p>
                </div>

                <!-- Card 8 -->
                <div class="p-6 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:border-emerald-600 transition-all duration-500 group text-center">
                    <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 mx-auto">
                        <i data-lucide="tablet-smartphone" class="w-6 h-6"></i>
                    </div>
                    <h4 class="text-sm font-black text-slate-900 mb-2">Mobile App Ready</h4>
                    <p class="text-slate-400 text-xs leading-relaxed">Works perfectly as a web app on all iOS and Android devices.</p>
                </div>
            </div>

            <!-- Bonus Footer -->
            <div class="inline-flex flex-wrap items-center justify-center gap-8 py-4 px-8 bg-slate-50 rounded-full border border-slate-100">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Bonus: Email Integration</span>
                </div>
                <div class="flex items-center gap-2 border-l border-slate-200 pl-8">
                    <i data-lucide="infinity" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">No Hidden Fees</span>
                </div>
            </div>

        </div>
    </section>

    <!-- The "Professional Suite" (Solution) Section -->
    <section class="py-24 bg-[#fbfdfc] relative overflow-hidden">
        <!-- Subtle Premium Ambient Accents -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-100/20 rounded-full blur-[120px] -z-10"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-50/30 rounded-full blur-[120px] -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            
            <!-- Suite Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white border border-emerald-100 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-8 shadow-sm">
                <i data-lucide="layers" class="w-3 h-3"></i>
                Professional Suite
            </div>

            <!-- Main Heading -->
            <div class="max-w-4xl mx-auto mb-20">
                <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter mb-6 leading-[1.05]">
                    One software. <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">Every part of your clinic.</span>
                </h2>
                <p class="text-slate-500 font-medium leading-relaxed text-lg max-w-2xl mx-auto italic">
                    From the first booking to the final payment — MedOS handles <br class="hidden md:block"> everything for your whole team in one place.
                </p>
            </div>

            <!-- Grid of 6 Suite Cards -->
            <div class="grid md:grid-cols-3 gap-8 mb-20">
                <!-- Card 1: Online Bookings -->
                <div class="p-10 bg-white border border-slate-100 rounded-[3rem] shadow-[0_15px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.12)] hover:-translate-y-2 transition-all duration-700 text-left group cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-50 to-white rounded-2xl flex items-center justify-center mb-8 text-emerald-600 shadow-sm border border-emerald-50 group-hover:from-emerald-600 group-hover:to-emerald-500 group-hover:text-white transition-all duration-500 relative z-10">
                        <i data-lucide="calendar" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 mb-4 tracking-tight relative z-10">Online Bookings</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-12 text-sm relative z-10">
                        Patients can book online. We send automatic reminders to their phone so they don't forget.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Saves 4hrs / day</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Patient Records -->
                <div class="p-10 bg-white border border-slate-100 rounded-[3rem] shadow-[0_15px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.12)] hover:-translate-y-2 transition-all duration-700 text-left group cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-50 to-white rounded-2xl flex items-center justify-center mb-8 text-emerald-600 shadow-sm border border-emerald-50 group-hover:from-emerald-600 group-hover:to-emerald-500 group-hover:text-white transition-all duration-500 relative z-10">
                        <i data-lucide="file-text" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 mb-4 tracking-tight relative z-10">Patient Records</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-12 text-sm relative z-10">
                        See patient history and reports instantly. Write your notes faster with easy clinical templates.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Safe Storage</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Digital Prescriptions -->
                <div class="p-10 bg-white border border-slate-100 rounded-[3rem] shadow-[0_15px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.12)] hover:-translate-y-2 transition-all duration-700 text-left group cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-50 to-white rounded-2xl flex items-center justify-center mb-8 text-emerald-600 shadow-sm border border-emerald-50 group-hover:from-emerald-600 group-hover:to-emerald-500 group-hover:text-white transition-all duration-500 relative z-10">
                        <i data-lucide="stethoscope" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 mb-4 tracking-tight relative z-10">Digital Prescriptions</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-12 text-sm relative z-10">
                        Create and send prescriptions in seconds. They are clear, safe, and easy for patients to get.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Fast & Easy</span>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Live Patient Queue -->
                <div class="p-10 bg-white border border-slate-100 rounded-[3rem] shadow-[0_15px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.12)] hover:-translate-y-2 transition-all duration-700 text-left group cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-50 to-white rounded-2xl flex items-center justify-center mb-8 text-emerald-600 shadow-sm border border-emerald-50 group-hover:from-emerald-600 group-hover:to-emerald-500 group-hover:text-white transition-all duration-500 relative z-10">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 mb-4 tracking-tight relative z-10">Live Patient Queue</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-12 text-sm relative z-10">
                        Track patients live and show their status on a TV. No more waiting room mess or confusion.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Real-time sync</span>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Online Video Calls -->
                <div class="p-10 bg-white border border-slate-100 rounded-[3rem] shadow-[0_15px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.12)] hover:-translate-y-2 transition-all duration-700 text-left group cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-50 to-white rounded-2xl flex items-center justify-center mb-8 text-emerald-600 shadow-sm border border-emerald-50 group-hover:from-emerald-600 group-hover:to-emerald-500 group-hover:text-white transition-all duration-500 relative z-10">
                        <i data-lucide="video" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 mb-4 tracking-tight relative z-10">Online Video Calls</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-12 text-sm relative z-10">
                        Talk to patients over high-quality video inside the software. No need for extra apps or links.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Easy Video</span>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Easy Billing -->
                <div class="p-10 bg-white border border-slate-100 rounded-[3rem] shadow-[0_15px_40px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(16,185,129,0.12)] hover:-translate-y-2 transition-all duration-700 text-left group cursor-pointer relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50/50 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-50 to-white rounded-2xl flex items-center justify-center mb-8 text-emerald-600 shadow-sm border border-emerald-50 group-hover:from-emerald-600 group-hover:to-emerald-500 group-hover:text-white transition-all duration-500 relative z-10">
                        <i data-lucide="credit-card" class="w-8 h-8"></i>
                    </div>
                    <h4 class="text-2xl font-black text-slate-900 mb-4 tracking-tight relative z-10">Easy Billing</h4>
                    <p class="text-slate-500 font-medium leading-relaxed mb-12 text-sm relative z-10">
                        Create simple bills and track payments instantly. Manage your money without extra accounting software.
                    </p>
                    <div class="pt-6 border-t border-slate-50 flex items-center relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 rounded-full">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Money Tracking</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suite Footer -->
            <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">
                Built for real clinics — not confusing hospital systems
            </p>

        </div>
    </section>

    <!-- The "Comparison" Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 relative z-10 text-center">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-6 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-xs font-black text-emerald-600 uppercase tracking-widest mb-10">
                <i data-lucide="star" class="w-4 h-4"></i>
                MedOS Advantages
            </div>

            <!-- Main Heading -->
            <div class="mb-16">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter mb-4 leading-tight">
                    Stop Running Your Clinic on <br class="hidden md:block"> 
                    <span class="text-emerald-600">Software That Slows You Down</span>
                </h2>
                <p class="text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto">
                    MedOS replaces manual busywork with smart automation, giving your team hours back every single day.
                </p>
            </div>

            <!-- Comparison Table -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 text-left">
                    
                    <!-- Others Column (NOW LEFT) -->
                    <div class="p-8 md:p-12 border-b md:border-b-0 md:border-r border-slate-200 opacity-80 grayscale-[0.5] bg-slate-50/30">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-12 h-12 bg-slate-200 rounded-2xl flex items-center justify-center text-slate-600">
                                <i data-lucide="x-circle" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-600 tracking-tight">Other Legacy Methods</h3>
                        </div>

                        <ul class="space-y-8">
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 shrink-0 mt-1">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-400 block text-lg">Wasting Time Searching</span>
                                    <span class="text-slate-400 text-sm">Struggling to find files while patients wait at the front desk.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 shrink-0 mt-1">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-400 block text-lg">Non-Stop Phone Calls</span>
                                    <span class="text-slate-400 text-sm">Staff tied to the desk handling routine bookings manually.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 shrink-0 mt-1">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-400 block text-lg">High Risk of Data Loss</span>
                                    <span class="text-slate-400 text-sm">One hardware failure could wipe out years of patient history.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-rose-50 rounded-full flex items-center justify-center text-rose-500 shrink-0 mt-1">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-400 block text-lg">Office-Only Access</span>
                                    <span class="text-slate-400 text-sm">You’re stuck at your desk just to check a simple patient detail.</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <!-- Our Software Column (NOW RIGHT) -->
                    <div class="p-8 md:p-12 bg-emerald-50/30">
                        <div class="flex items-center gap-4 mb-10">
                            <div class="w-12 h-12 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                                <i data-lucide="sparkles" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Our Modern Software</h3>
                        </div>

                        <ul class="space-y-8">
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0 mt-1">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-900 block text-lg">Find Any Record Instantly</span>
                                    <span class="text-slate-500 text-sm">Access complete patient histories in two seconds, not ten minutes.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0 mt-1">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-900 block text-lg">Bookings That Manage Themselves</span>
                                    <span class="text-slate-500 text-sm">Patients schedule online 24/7, reducing front-desk calls by 60%.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0 mt-1">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-900 block text-lg">Data That’s Always Safe</span>
                                    <span class="text-slate-500 text-sm">Automatic cloud backups mean you never have to worry about a crash.</span>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 shrink-0 mt-1">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <span class="font-black text-slate-900 block text-lg">Manage From Anywhere</span>
                                    <span class="text-slate-500 text-sm">Check your schedule and patient notes from your phone or home office.</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Proof Line & CTA -->
            <div class="mt-12 flex flex-col items-center gap-8">
                <div class="px-6 py-3 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center gap-3">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                    <p class="text-emerald-900 font-bold text-sm">
                        Clinics save an average of 12+ hours of admin work every week using MedOS.
                    </p>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <a href="#contact" class="px-10 py-5 bg-emerald-600 text-white font-black text-xl rounded-2xl shadow-[0_20px_40px_rgba(16,185,129,0.2)] hover:shadow-[0_25px_50px_rgba(16,185,129,0.3)] hover:-translate-y-1 transition-all duration-300 flex items-center gap-3">
                        Get Started Now
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </a>
                    <p class="text-slate-400 font-bold text-sm uppercase tracking-widest">
                        Join 500+ modern clinics today
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- The "Testimonials" Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-8">
                Customer Stories
            </div>

            <!-- Main Heading -->
            <div class="max-w-3xl mx-auto mb-20">
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tighter mb-6 leading-tight">
                    Trusted by Modern Clinics <br class="hidden md:block">
                    Every Single Day
                </h2>
                <p class="text-slate-500 font-medium leading-relaxed text-lg">
                    See how MedOS is helping doctors spend less time on admin <br class="hidden md:block"> and more time with their patients.
                </p>
            </div>

            <!-- Testimonial Grid -->
            <div class="grid md:grid-cols-3 gap-8">
                
                <!-- Testimonial 1 -->
                <div class="p-10 bg-emerald-50/30 border border-emerald-100/50 rounded-[3rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 text-left relative flex flex-col justify-between group">
                    <div class="mb-8">
                        <!-- Stars -->
                        <div class="flex gap-1 text-emerald-500 mb-6">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed italic">
                            "We used to lose hours every day just managing phone calls and paper files. Since switching to MedOS, our front desk is finally calm, and I have more time for my patients."
                        </p>
                    </div>
                    <div class="flex items-center gap-4 pt-8 border-t border-emerald-100/50">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold">
                            SC
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-sm">Dr. Sarah Chen</h4>
                            <p class="text-emerald-600 text-[10px] font-bold uppercase tracking-widest">Chen Family Clinic</p>
                        </div>
                    </div>
                    <i data-lucide="quote" class="absolute top-10 right-10 w-12 h-12 text-emerald-500/10 group-hover:text-emerald-500/20 transition-colors"></i>
                </div>

                <!-- Testimonial 2 -->
                <div class="p-10 bg-white border border-slate-100 rounded-[3rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 text-left relative flex flex-col justify-between group">
                    <div class="mb-8">
                        <!-- Stars -->
                        <div class="flex gap-1 text-emerald-500 mb-6">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed italic">
                            "Finding a patient history used to take 10 minutes of digging through drawers. Now it's instant. The staff learned it in one afternoon—it really is that simple."
                        </p>
                    </div>
                    <div class="flex items-center gap-4 pt-8 border-t border-slate-50">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-700 font-bold">
                            JW
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-sm">Dr. James Wilson</h4>
                            <p class="text-emerald-600 text-[10px] font-bold uppercase tracking-widest">City Wellness Center</p>
                        </div>
                    </div>
                    <i data-lucide="quote" class="absolute top-10 right-10 w-12 h-12 text-slate-500/5 group-hover:text-emerald-500/10 transition-colors"></i>
                </div>

                <!-- Testimonial 3 -->
                <div class="p-10 bg-emerald-50/30 border border-emerald-100/50 rounded-[3rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 text-left relative flex flex-col justify-between group">
                    <div class="mb-8">
                        <!-- Stars -->
                        <div class="flex gap-1 text-emerald-500 mb-6">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed italic">
                            "I was worried about moving everything online, but it was the best decision for my practice. Everything from booking to billing is just smoother now."
                        </p>
                    </div>
                    <div class="flex items-center gap-4 pt-8 border-t border-emerald-100/50">
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-700 font-bold">
                            MR
                        </div>
                        <div>
                            <h4 class="font-black text-slate-900 text-sm">Dr. Maria Rodriguez</h4>
                            <p class="text-emerald-600 text-[10px] font-bold uppercase tracking-widest">Grace Pediatrics</p>
                        </div>
                    </div>
                    <i data-lucide="quote" class="absolute top-10 right-10 w-12 h-12 text-emerald-500/10 group-hover:text-emerald-500/20 transition-colors"></i>
                </div>

            </div>

            <!-- Verified Line -->
            <div class="mt-16 flex items-center justify-center gap-3">
                <div class="flex -space-x-3">
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-emerald-500 flex items-center justify-center text-[10px] text-white font-bold">A</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-800 flex items-center justify-center text-[10px] text-white font-bold">B</div>
                    <div class="w-8 h-8 rounded-full border-2 border-white bg-emerald-600 flex items-center justify-center text-[10px] text-white font-bold">C</div>
                </div>
                <p class="text-slate-400 font-bold text-xs uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i>
                    Over 500 verified clinic reviews
                </p>
            </div>

        </div>
    </section>

    <section class="py-32 bg-[#0b1120] text-white relative overflow-hidden">
        <!-- Premium Mesh Gradient Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[60%] bg-emerald-500/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[60%] h-[60%] bg-emerald-600/5 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-24 items-center relative z-10">

            <!-- LEFT CONTENT -->
            <div class="space-y-12">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[10px] font-black text-emerald-400 uppercase tracking-widest">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        System Performance
                    </div>
                    <h2 class="text-5xl md:text-6xl font-black leading-[1.05] tracking-tighter">
                        Your clinic isn’t slow. <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-600">Your software is.</span>
                    </h2>
                    <p class="text-slate-400 text-lg leading-relaxed max-w-xl font-medium">
                        MedOS is built to solve the administrative friction that slows down modern healthcare. We return 2+ hours of your day back to clinical care.
                    </p>
                </div>

                <!-- FEATURES -->
                <div class="space-y-4">
                    <!-- FEATURE 1 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="zap" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Documentation at the speed of care</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                Stop spending hours on notes. Our smart templates learn your style and finish clinical documentation before the patient leaves the room.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 2 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="database" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Data that moves with you</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                No more spinning wheels. Access complete patient history, lab reports, and vitals in under 200ms on any device, anywhere.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 3 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="layout-grid" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Chaos-free patient journeys</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                A unified hub for scheduling and queue management that keeps your staff synchronized and your waiting rooms empty.
                            </p>
                        </div>
                    </div>

                    <!-- FEATURE 4 -->
                    <div class="group p-6 rounded-3xl border border-white/5 hover:bg-white/[0.03] hover:border-white/10 transition-all duration-500 flex gap-6 items-start">
                        <div class="relative shrink-0">
                            <div class="absolute inset-0 bg-emerald-500 blur-xl opacity-0 group-hover:opacity-20 transition-opacity"></div>
                            <div class="w-14 h-14 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center border border-emerald-500/20 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i data-lucide="credit-card" class="w-7 h-7"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-bold text-white text-lg mb-1 tracking-tight">Clinical-First revenue engine</h5>
                            <p class="text-slate-400 text-sm leading-relaxed font-medium">
                                Billing is no longer an afterthought. Generate professional invoices and track revenue directly from your clinical workspace.
                            </p>
                        </div>
                    </div>
                </div>

            </div>


            <!-- RIGHT SIDE (REAL VALUE VISUAL) -->
            <div class="relative">
                <div class="relative z-10 p-5 bg-white/5 rounded-[4rem] border border-white/10 backdrop-blur-sm shadow-[0_0_50px_-12px_rgba(16,185,129,0.3)] overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1581056771107-24ca5f033842?q=80&w=2070&auto=format&fit=crop" 
                         alt="Doctor using MedOS" 
                         class="w-full h-auto rounded-[3rem] opacity-90 transition-all duration-700 group-hover:scale-105 group-hover:opacity-100">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-[#0b1120] via-transparent to-transparent opacity-60"></div>

                    <!-- Floating Interface Badge -->
                    <div class="absolute bottom-10 left-10 right-10 p-8 bg-white/[0.03] backdrop-blur-2xl border border-white/10 rounded-[2.5rem] shadow-2xl animate-float">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 border border-emerald-500/30">
                                <i data-lucide="zap" class="w-8 h-8"></i>
                            </div>
                            <div>
                                <div class="inline-flex items-center gap-2 px-2 py-0.5 bg-emerald-500/20 rounded text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-2">Performance Boost</div>
                                <p class="text-2xl font-black text-white tracking-tighter">Zero Latency Dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Abstract Glows -->
                <div class="absolute -top-10 -right-10 w-96 h-96 bg-emerald-500/20 blur-[120px] rounded-full animate-pulse"></div>
                <div class="absolute -bottom-10 -left-10 w-96 h-96 bg-emerald-600/10 blur-[120px] rounded-full animate-pulse" style="animation-delay: 1.5s;"></div>
            </div>


        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-32 bg-[#fcfdfd] relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-emerald-600/5 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/4"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    Transparent Pricing
                </div>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tightest mb-6 leading-[1.1]">
                    Simple plans for <br>
                    <span class="text-emerald-600">modern practices.</span>
                </h2>
                <p class="text-lg text-slate-500 max-w-xl mx-auto font-medium">Start free. Scale as your clinic grows. No hidden fees, no implementation charges—ever.</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <!-- Starter Plan -->
                <div class="group p-12 rounded-[2rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Starter</span>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-6xl font-black text-slate-900 tracking-tighter">$0</span>
                            <span class="text-slate-400 font-bold text-sm">/month</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-6 mb-12">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            1 Doctor Portal
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Up to 50 Patients
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Clinical Records
                        </li>
                    </ul>

                    <a href="login.php" class="block text-center w-full py-5 bg-slate-50 text-slate-900 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all">Get Started Free</a>
                </div>

                <!-- Practice Plan -->
                <div class="group p-12 rounded-[2rem] bg-white border-2 border-emerald-500 shadow-[0_40px_100px_rgba(16,185,129,0.15)] -translate-y-4 relative overflow-hidden scale-105 z-20">
                    <div class="absolute top-0 right-0 px-6 py-2 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-bl-3xl">Most Popular</div>
                    
                    <!-- Internal Glow -->
                    <div class="absolute -top-[10%] -left-[10%] w-[50%] h-[50%] bg-emerald-500/5 rounded-full blur-[80px] pointer-events-none"></div>

                    <div class="mb-12 relative z-10">
                        <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Practice</span>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-6xl font-black text-slate-900 tracking-tighter">$49</span>
                            <span class="text-slate-400 font-bold text-sm">/month</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-6 mb-12 relative z-10">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Unlimited Doctors
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Unlimited Patients
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Automated Billing
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Patient Portal Access
                        </li>
                    </ul>

                    <a href="login.php" class="block text-center w-full py-6 emerald-gradient text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-emerald-500/30 hover:scale-105 active:scale-95 transition-all relative z-10">Start 14-Day Trial</a>
                </div>

                <!-- Enterprise Plan -->
                <div class="group p-12 rounded-[2rem] bg-white border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.02)] hover:shadow-[0_40px_80px_rgba(0,0,0,0.05)] hover:-translate-y-2 transition-all duration-700 relative overflow-hidden">
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Enterprise</span>
                        <div class="mt-4 flex items-baseline gap-1">
                            <span class="text-5xl font-black text-slate-900 tracking-tighter">Custom</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-6 mb-12">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Multi-Branch Sync
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Dedicated Manager
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center"><i data-lucide="check" class="w-3 h-3"></i></div>
                            Custom API Access
                        </li>
                    </ul>

                    <a href="contact.php" class="block text-center w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-slate-800 transition-all">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-32 bg-white relative overflow-hidden" x-data="{ active: null }">
        <!-- Background Accents -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-emerald-50/50 rounded-full blur-[120px] -z-10 animate-pulse"></div>
        
        <div class="max-w-4xl mx-auto px-6 relative z-10">
            <div class="text-center mb-24">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-6">
                    Help Center
                </div>
                <h2 class="text-5xl font-black text-slate-900 tracking-tightest mb-6 leading-tight">
                    Got questions? <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">We've got answers.</span>
                </h2>
                <p class="text-slate-500 text-sm font-bold uppercase tracking-widest">Everything you need to know about MedOS.</p>
            </div>

            <div class="space-y-4">
                <!-- Q1 -->
                <div class="group border border-slate-100 rounded-[2rem] overflow-hidden transition-all duration-500"
                    :class="active === 1 ? 'bg-[#f8fafc] border-emerald-500/30 shadow-[0_0_40px_-10px_rgba(16,185,129,0.2)]' : 'bg-white hover:bg-slate-50/50'">
                    <button @click="active = active === 1 ? null : 1"
                        class="w-full p-8 flex items-center justify-between text-left transition-all">
                        <span class="text-lg font-black text-slate-900 tracking-tight">Is MedOS compliant with healthcare data laws?</span>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm"
                             :class="active === 1 ? 'bg-emerald-500 text-white rotate-45 shadow-emerald-500/40' : 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100'">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </div>
                    </button>
                    <div x-show="active === 1" x-collapse
                        class="p-8 pt-0 text-slate-500 text-base leading-relaxed border-t border-slate-50 font-medium">
                        Absolutely. MedOS utilizes bank-level AES-256 encryption and follows international healthcare
                        data protection standards (including HIPAA/GDPR compatibility). Your patient data is siloed and
                        protected at the highest tier.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="group border border-slate-100 rounded-[2rem] overflow-hidden transition-all duration-500"
                    :class="active === 2 ? 'bg-[#f8fafc] border-emerald-500/30 shadow-[0_0_40px_-10px_rgba(16,185,129,0.2)]' : 'bg-white hover:bg-slate-50/50'">
                    <button @click="active = active === 2 ? null : 2"
                        class="w-full p-8 flex items-center justify-between text-left transition-all">
                        <span class="text-lg font-black text-slate-900 tracking-tight">How long does it take to migrate our current data?</span>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm"
                             :class="active === 2 ? 'bg-emerald-500 text-white rotate-45 shadow-emerald-500/40' : 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100'">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </div>
                    </button>
                    <div x-show="active === 2" x-collapse
                        class="p-8 pt-0 text-slate-500 text-base leading-relaxed border-t border-slate-50 font-medium">
                        Our Intelligent Importer can handle thousands of records in minutes. Most clinics are fully
                        operational on MedOS in under an hour. We provide a migration guarantee to ensure zero data
                        loss.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="group border border-slate-100 rounded-[2rem] overflow-hidden transition-all duration-500"
                    :class="active === 3 ? 'bg-[#f8fafc] border-emerald-500/30 shadow-[0_0_40px_-10px_rgba(16,185,129,0.2)]' : 'bg-white hover:bg-slate-50/50'">
                    <button @click="active = active === 3 ? null : 3"
                        class="w-full p-8 flex items-center justify-between text-left transition-all">
                        <span class="text-lg font-black text-slate-900 tracking-tight">Do you offer support for multi-branch clinics?</span>
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 shadow-sm"
                             :class="active === 3 ? 'bg-emerald-500 text-white rotate-45 shadow-emerald-500/40' : 'bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100'">
                            <i data-lucide="plus" class="w-5 h-5"></i>
                        </div>
                    </button>
                    <div x-show="active === 3" x-collapse
                        class="p-8 pt-0 text-slate-500 text-base leading-relaxed border-t border-slate-50 font-medium">
                        Yes, our Enterprise plan includes specialized multi-branch sync, centralized reporting, and a
                        dedicated account manager to handle complex clinical networks.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA (Emerald Theme) -->
    <section class="py-32 bg-emerald-600 relative overflow-hidden">
        <!-- Subtle Dot Pattern Overlay -->
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
            
            <!-- Trust Note (Minimalist) -->
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