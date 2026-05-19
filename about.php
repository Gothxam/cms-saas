<?php
// about.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Our Mission | MedOS Modern Healthcare</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

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
                <a href="about.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">About Us</a>
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">How it Works</a>
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Pricing</a>
                <a href="contact.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">Contact</a>
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
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Designing the Future</span>
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 tracking-tightest mb-10 leading-[1.05]">
                We build for the <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">next era of care.</span>
            </h1>
            <p class="text-xl md:text-2xl text-slate-500 font-medium leading-relaxed max-w-3xl mx-auto mb-16 italic">
                MedOS was built to return the doctor's focus to the patient by eliminating the administrative static of legacy systems.
            </p>
            
            <!-- Trust Banner -->
            <div class="flex flex-wrap items-center justify-center gap-8 opacity-40 grayscale group hover:grayscale-0 hover:opacity-100 transition-all duration-700">
                <span class="text-sm font-black tracking-widest uppercase">Verified Security</span>
                <span class="text-sm font-black tracking-widest uppercase">Cloud Native</span>
                <span class="text-sm font-black tracking-widest uppercase">HIPAA Compliant</span>
            </div>
        </div>
    </header>

    <!-- The Problem Narrative -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-24 items-center">
            <!-- Visual Content -->
            <div class="relative">
                <div class="relative w-full aspect-square bg-emerald-50 rounded-[4rem] overflow-hidden group">
                    <img src="about.png" alt="Mission Workflow" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-emerald-500/10 to-transparent"></div>
                </div>
                <!-- Floating Card -->
                <div class="absolute -bottom-10 -right-10 bg-white p-8 rounded-3xl shadow-2xl border border-slate-100 max-w-xs animate-bounce" style="animation-duration: 4s;">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white">
                            <i data-lucide="zap" class="w-6 h-6"></i>
                        </div>
                        <h4 class="font-black text-slate-900 text-lg">Instant Sync</h4>
                    </div>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Our clinical engine synchronizes patient data across every clinic in real-time.</p>
                </div>
            </div>

            <!-- Text Content -->
            <div class="space-y-10">
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                        Our Genesis
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter leading-tight">
                        Doctors should <br>
                        <span class="text-emerald-600 italic">be doctors again.</span>
                    </h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed">
                        In 2021, we noticed a dangerous trend: doctors were spending more time with keyboards than with patients. Clunky legacy systems, manual paperwork, and fragmented data were slowing down the healing process.
                    </p>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed">
                        We built MedOS to be the operating system for modern clinics. A platform so intuitive it disappears, allowing medical professionals to focus on what matters most—human lives.
                    </p>
                </div>

                <div class="flex items-center gap-6 pt-10 border-t border-slate-50">
                    <div>
                        <h3 class="text-5xl font-black text-emerald-600 tracking-tightest">500+</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Clinics Modernized</p>
                    </div>
                    <div class="w-px h-12 bg-slate-100"></div>
                    <div>
                        <h3 class="text-5xl font-black text-emerald-600 tracking-tightest">1M+</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lives Impacted</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- The "MedOS Way" Values -->
    <section class="py-32 bg-slate-900 relative overflow-hidden">
        <!-- Ambient Mesh Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-50">
            <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[60%] bg-emerald-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-[20%] -right-[10%] w-[60%] h-[60%] bg-emerald-600/5 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-24">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/5 border border-white/10 rounded-full text-[10px] font-black text-emerald-400 uppercase tracking-[0.2em] mb-8 shadow-sm">
                    The MedOS Way
                </div>
                <h2 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-8 leading-tight">
                    The values that guide <br> our every decision.
                </h2>
                <p class="text-slate-400 font-medium text-xl max-w-2xl mx-auto">
                    We don't just build features; we build solutions that honor the gravity of healthcare.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Value 1 -->
                <div class="p-12 bg-white/[0.03] border border-white/10 rounded-[3.5rem] hover:bg-white/[0.05] hover:border-emerald-500/50 transition-all duration-700 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center mb-10 shadow-inner group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="shield-check" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4 tracking-tight">Security as a Right</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Healthcare data is sacred. We treat security not as a feature, but as a fundamental human right for every patient.</p>
                </div>

                <!-- Value 2 -->
                <div class="p-12 bg-white/[0.03] border border-white/10 rounded-[3.5rem] hover:bg-white/[0.05] hover:border-emerald-500/50 transition-all duration-700 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center mb-10 shadow-inner group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="zap" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4 tracking-tight">Zero-Friction UI</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">We obsess over every click. If a workflow takes three steps, we find a way to make it one. Speed is everything.</p>
                </div>

                <!-- Value 3 -->
                <div class="p-12 bg-white/[0.03] border border-white/10 rounded-[3.5rem] hover:bg-white/[0.05] hover:border-emerald-500/50 transition-all duration-700 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl -mr-16 -mt-16 group-hover:bg-emerald-500/20 transition-all"></div>
                    <div class="w-16 h-16 bg-emerald-500/10 text-emerald-400 rounded-2xl flex items-center justify-center mb-10 shadow-inner group-hover:scale-110 transition-transform duration-500">
                        <i data-lucide="users" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4 tracking-tight">Patient-First Focus</h3>
                    <p class="text-slate-400 text-base leading-relaxed font-medium">Every tool we build is ultimately for the person in the waiting room. We build software that makes people feel seen.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bold Vision Section -->
    <section class="py-40 bg-white relative overflow-hidden text-center">
        <div class="max-w-5xl mx-auto px-6 relative z-10">
            <h2 class="text-5xl md:text-7xl lg:text-9xl font-black text-slate-100 tracking-tightest absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -z-10 select-none">
                OUR VISION
            </h2>
            <div class="space-y-10">
                <h3 class="text-3xl md:text-6xl font-black text-slate-900 tracking-tighter leading-tight">
                    Healthcare should be <br>
                    <span class="text-emerald-600">invisible.</span>
                </h3>
                <p class="text-xl md:text-2xl text-slate-500 font-medium leading-relaxed max-w-3xl mx-auto italic">
                    "When technology is at its best, it disappears. Our vision is a world where doctors never have to worry about their software again."
                </p>
                <div class="pt-10">
                    <a href="index.php" class="inline-flex items-center gap-3 text-emerald-600 font-black uppercase tracking-widest text-xs group">
                        See the product in action
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Standards (Refined) -->
    <section class="py-32 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-8">
                        The Emerald Vault
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tighter mb-8 leading-tight">
                        Your clinical data, <br>
                        <span class="text-emerald-600">protected.</span>
                    </h2>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10">
                        We understand the weight of clinical responsibility. That's why we built MedOS with bank-level encryption and decentralized architectures.
                    </p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-6 bg-white border border-slate-100 rounded-3xl">
                            <h6 class="font-black text-emerald-600 text-[10px] uppercase tracking-widest mb-2">HIPAA</h6>
                            <p class="text-xs font-bold text-slate-400 tracking-widest uppercase">Certified</p>
                        </div>
                        <div class="p-6 bg-white border border-slate-100 rounded-3xl">
                            <h6 class="font-black text-emerald-600 text-[10px] uppercase tracking-widest mb-2">GDPR</h6>
                            <p class="text-xs font-bold text-slate-400 tracking-widest uppercase">Compliant</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="p-12 bg-white rounded-[3rem] border border-slate-100 shadow-2xl relative z-10">
                        <div class="space-y-8">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="lock" class="w-7 h-7"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-900">End-to-End Encryption</h4>
                                    <p class="text-xs text-slate-500 font-medium">AES-256 military-grade protection.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 shadow-sm">
                                    <i data-lucide="database" class="w-7 h-7"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-900">Cloud Isolation</h4>
                                    <p class="text-xs text-slate-500 font-medium">Your data, completely siloed and secure.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Decor -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl -z-10"></div>
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
    </section>    <!-- Footer -->
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
ml>
