<?php
// pricing.php
require_once 'core/init.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Plans | MedOS Clinical Systems</title>

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
                <a href="how-it-works.php" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-emerald-600 transition-colors">How it Works</a>
                <a href="pricing.php" class="text-xs font-black uppercase tracking-widest text-emerald-600">Pricing</a>
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

        <div class="max-w-5xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-100 rounded-full shadow-sm mb-12">
                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em]">Predictable Pricing</span>
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-slate-900 tracking-tightest mb-10 leading-[1.05]">
                Scale your practice, <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-emerald-400">not your costs.</span>
            </h1>
            <p class="text-xl md:text-2xl text-slate-500 font-medium leading-relaxed max-w-3xl mx-auto mb-16 italic">
                Predictable plans for growing clinics. No hidden fees, no per-patient charges—just pure clinical efficiency.
            </p>
        </div>
    </header>

    <!-- Pricing Grid -->
    <section class="py-24 bg-[#f8fafc] border-y border-slate-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-3 gap-8 items-stretch mb-20">
                
                <!-- Solo Plan -->
                <div class="p-12 bg-white border border-slate-200 rounded-[3rem] hover:shadow-2xl transition-all duration-700 group flex flex-col">
                    <div class="mb-10">
                        <div class="inline-flex items-center px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8">Solo Practitioner</div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">Starter</h3>
                        <div class="flex items-baseline gap-2 mt-6">
                            <span class="text-5xl font-black text-slate-900 tracking-tightest">$49</span>
                            <span class="text-slate-400 font-bold text-sm tracking-tight">/mo</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-5 mb-12 flex-grow">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Up to 2 Staff Seats
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Unlimited Appointments
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <div class="w-5 h-5 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Basic Patient Records
                        </li>
                    </ul>

                    <a href="login.php" class="w-full py-5 bg-slate-50 text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all border border-slate-100 hover:border-emerald-600 text-center">Get Started</a>
                </div>

                <!-- Professional Plan -->
                <div class="p-12 bg-white border-2 border-emerald-500 rounded-[3.5rem] shadow-2xl shadow-emerald-900/10 lg:-translate-y-8 relative z-10 group flex flex-col">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/30">Most Popular</div>
                    
                    <div class="mb-10">
                        <div class="inline-flex items-center px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-8">Growing Clinics</div>
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">Professional</h3>
                        <div class="flex items-baseline gap-2 mt-6">
                            <span class="text-5xl font-black text-slate-900 tracking-tightest">$149</span>
                            <span class="text-slate-400 font-bold text-sm tracking-tight">/mo</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-5 mb-12 flex-grow">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-900">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Up to 10 Staff Seats
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-900">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Advanced Analytics
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-900">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Digital Prescriptions
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-900">
                            <div class="w-5 h-5 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Video Consultations
                        </li>
                    </ul>

                    <a href="login.php" class="w-full py-5 emerald-gradient text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:scale-105 active:scale-95 transition-all text-center">Start Free Trial</a>
                </div>

                <!-- Network Plan -->
                <div class="p-12 bg-slate-900 border border-white/10 rounded-[3rem] hover:shadow-2xl transition-all duration-700 group flex flex-col text-white">
                    <div class="mb-10">
                        <div class="inline-flex items-center px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-8">Large Networks</div>
                        <h3 class="text-3xl font-black tracking-tight">Enterprise</h3>
                        <div class="flex items-baseline gap-2 mt-6">
                            <span class="text-4xl font-black tracking-tightest leading-none italic">Custom</span>
                        </div>
                    </div>
                    
                    <ul class="space-y-5 mb-12 flex-grow">
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-300">
                            <div class="w-5 h-5 bg-white/5 text-emerald-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Unlimited Staff Seats
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-300">
                            <div class="w-5 h-5 bg-white/5 text-emerald-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Multi-Location Sync
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-300">
                            <div class="w-5 h-5 bg-white/5 text-emerald-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Custom API Access
                        </li>
                        <li class="flex items-center gap-3 text-sm font-bold text-slate-300">
                            <div class="w-5 h-5 bg-white/5 text-emerald-400 rounded-full flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check" class="w-3 h-3"></i>
                            </div>
                            Dedicated Success Manager
                        </li>
                    </ul>

                    <a href="contact.php" class="w-full py-5 bg-white text-slate-900 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all text-center">Contact Sales</a>
                </div>

            </div>
        </div>
    </section>

    <!-- "Included Standard" Grid -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 tracking-tightest mb-20 leading-tight">
                Standard on every plan. <br>
                <span class="text-emerald-600">No compromises.</span>
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-black text-slate-900 text-xs uppercase tracking-widest">HIPAA Ready</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Top-tier patient data protection.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <i data-lucide="history" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-black text-slate-900 text-xs uppercase tracking-widest">Daily Backups</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Your data, backed up every 24hrs.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <i data-lucide="headphones" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-black text-slate-900 text-xs uppercase tracking-widest">24/7 Support</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Direct access to clinical experts.</p>
                </div>
                <div class="space-y-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <i data-lucide="refresh-cw" class="w-6 h-6"></i>
                    </div>
                    <h4 class="font-black text-slate-900 text-xs uppercase tracking-widest">Free Migration</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">We help move your legacy data.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Comparison -->
    <section class="py-32 bg-slate-50 relative overflow-hidden">
        <div class="max-w-5xl mx-auto px-6 relative z-10">
            <div class="text-center mb-20">
                <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Detailed Comparison</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Feature</th>
                            <th class="py-6 px-4 text-[10px] font-black text-slate-900 uppercase tracking-widest">Starter</th>
                            <th class="py-6 px-4 text-[10px] font-black text-emerald-600 uppercase tracking-widest">Professional</th>
                            <th class="py-6 px-4 text-[10px] font-black text-slate-900 uppercase tracking-widest">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-5 text-sm font-bold text-slate-600">Staff Seats</td>
                            <td class="py-5 px-4 text-sm font-black text-slate-900">2</td>
                            <td class="py-5 px-4 text-sm font-black text-emerald-600">10</td>
                            <td class="py-5 px-4 text-sm font-black text-slate-900 italic">Unlimited</td>
                        </tr>
                        <tr>
                            <td class="py-5 text-sm font-bold text-slate-600">Patient Dashboard</td>
                            <td class="py-5 px-4 text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            <td class="py-5 px-4 text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            <td class="py-5 px-4 text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                        </tr>
                        <tr>
                            <td class="py-5 text-sm font-bold text-slate-600">Video Consultations</td>
                            <td class="py-5 px-4 text-slate-300">—</td>
                            <td class="py-5 px-4 text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                            <td class="py-5 px-4 text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                        </tr>
                        <tr>
                            <td class="py-5 text-sm font-bold text-slate-600">API Access</td>
                            <td class="py-5 px-4 text-slate-300">—</td>
                            <td class="py-5 px-4 text-slate-300">—</td>
                            <td class="py-5 px-4 text-emerald-500"><i data-lucide="check" class="w-5 h-5"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- B2B FAQ -->
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 relative z-10" x-data="{ active: null }">
            <div class="text-center mb-24">
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tightest">Common Questions</h2>
            </div>

            <div class="space-y-4">
                <!-- Q1 -->
                <div class="bg-slate-50 border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500" :class="active === 1 ? 'bg-white ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Can I change plans at any time?</span>
                        <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 1 ? 'rotate-45 text-emerald-600' : ''"></i>
                    </button>
                    <div x-show="active === 1" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-sm font-medium leading-relaxed">
                            Yes, you can upgrade or downgrade your plan directly from your dashboard. Plan changes are prorated immediately.
                        </div>
                    </div>
                </div>

                <!-- Q2 -->
                <div class="bg-slate-50 border border-slate-100 rounded-3xl overflow-hidden transition-all duration-500" :class="active === 2 ? 'bg-white ring-2 ring-emerald-500/20 border-emerald-500/30' : ''">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full px-8 py-7 text-left flex justify-between items-center group">
                        <span class="text-lg font-bold text-slate-900 group-hover:text-emerald-600 transition-colors">Do you offer discounts for non-profits?</span>
                        <i data-lucide="plus" class="w-5 h-5 text-slate-400 transition-transform duration-500" :class="active === 2 ? 'rotate-45 text-emerald-600' : ''"></i>
                    </button>
                    <div x-show="active === 2" x-collapse x-cloak>
                        <div class="px-8 pb-8 text-slate-500 text-sm font-medium leading-relaxed">
                            We are committed to helping community clinics. Contact our sales team to discuss non-profit and educational discount options.
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
                <p class="text-sm text-slate-500 font-medium leading-relaxed max-sm mb-10">
                    The OS for modern practices. High-fidelity clinical management built for the next generation.
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


    <script>
        window.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>

</html>
