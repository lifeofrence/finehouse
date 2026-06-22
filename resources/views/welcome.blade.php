<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FineHouse — Modern Property Management</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#f0f4ff',
                            100: '#dbe4ff',
                            200: '#bac8ff',
                            300: '#91a7ff',
                            400: '#748ffc',
                            500: '#5c7cfa',
                            600: '#4c6ef5',
                            700: '#4263eb',
                            800: '#3b5bdb',
                            900: '#364fc7',
                        }
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.92);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-scale.visible {
            opacity: 1;
            transform: scale(1);
        }

        .nav-blur {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: saturate(180%) blur(20px);
            -webkit-backdrop-filter: saturate(180%) blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: fade-in 0.6s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.4); }
        }
        .pulse-dot {
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes slide-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.12);
        }

        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
            background-size: 200% 100%;
            animation: shimmer 2.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .mask-fade-bottom {
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
        }
    </style>
</head>
<body class="bg-white text-[#1a1a2e] antialiased selection:bg-brand-500/20 selection:text-brand-700">

    <!-- NAVIGATION -->
    <nav id="navbar" class="fixed top-0 inset-x-0 z-50 h-16 flex items-center transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 w-full flex items-center justify-between">
            <a href="#" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center shadow-sm transition-transform duration-300 group-hover:scale-105">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-tight text-[#1a1a2e]">Fine<span class="text-brand-600">House</span></span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Features</a>
                <a href="#testimonials" class="text-sm font-medium text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Testimonials</a>
                <a href="#pricing" class="text-sm font-medium text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Pricing</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-1.5 px-5 py-2 bg-[#1a1a2e] text-white text-sm font-semibold rounded-full hover:bg-[#2a2a3e] transition-all shadow-sm">
                            Dashboard
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-5 py-2 bg-[#1a1a2e] text-white text-sm font-semibold rounded-full hover:bg-[#2a2a3e] transition-all shadow-sm">
                                Get Started
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <button id="mobile-menu-btn" class="md:hidden p-2 -mr-2 text-[#4a4a5a]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-[#f8f9fc]">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-1/4 right-1/4 w-96 h-96 bg-brand-200/30 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-1/4 left-1/3 w-80 h-80 bg-purple-200/20 rounded-full blur-[100px]"></div>
            <div class="absolute top-1/3 left-1/4 w-64 h-64 bg-amber-200/15 rounded-full blur-[80px]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 pt-32 pb-24 lg:pt-40 lg:pb-32 w-full">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                <div class="text-center lg:text-left">
                    <!-- <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-brand-50 border border-brand-100 rounded-full text-xs font-semibold text-brand-700 mb-8 animate-in">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75 pulse-dot"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                        </span>
                        Now with Virtual Tours &amp; AI Insights
                    </div> -->

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-black text-[#1a1a2e] leading-[1.08] tracking-tight mb-6 animate-in delay-100">
                        Property management,
                        <br>
                        <span class="text-brand-600">reimagined.</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-[#5a5a6e] leading-relaxed max-w-lg mx-auto lg:mx-0 mb-10 animate-in delay-200">
                        From rent collection to maintenance — one platform for modern landlords who want to grow without the headache.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start animate-in delay-300">
                        <a href="{{ route('register') }}" class="group inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#1a1a2e] text-white font-semibold rounded-xl hover:bg-[#2a2a3e] transition-all shadow-lg">
                            Start free trial
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-[#1a1a2e] font-semibold rounded-xl border border-[#e2e2e8] hover:bg-[#f8f9fc] hover:border-[#d0d0d8] transition-all shadow-sm">
                            See how it works
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                    <div class="flex items-center gap-6 mt-10 justify-center lg:justify-start animate-in delay-400">
                        <div class="flex -space-x-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 border-2 border-white shadow-sm"></div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 border-2 border-white shadow-sm"></div>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 border-2 border-white shadow-sm"></div>
                            <div class="w-9 h-9 rounded-full bg-[#1a1a2e] border-2 border-white shadow-sm flex items-center justify-center text-[9px] text-white font-bold">2k+</div>
                        </div>
                        <div class="text-left">
                            <div class="flex items-center gap-1">
                                {!! str_repeat('<svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>', 5) !!}
                            </div>
                            <p class="text-xs text-[#5a5a6e] font-medium mt-0.5">Trusted by 2,000+ landlords</p>
                        </div>
                    </div>
                </div>

                <div class="relative animate-in delay-300">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="/images/hero_light.png" alt="FineHouse Dashboard Preview" class="w-full h-auto">
                        <div class="absolute inset-0 ring-1 ring-inset ring-black/5 rounded-2xl pointer-events-none"></div>
                    </div>

                    <div class="absolute -bottom-4 -left-4 bg-white rounded-xl shadow-lg border border-[#e2e2e8] p-4 flex items-center gap-3 animate-in delay-500">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <!-- <div>
                            <p class="text-sm font-bold text-[#1a1a2e] leading-none">Rent collected</p>
                            <p class="text-xs text-[#5a5a6e] mt-0.5">&#8358;24,500 this month</p>
                        </div> -->
                    </div>

                    <div class="absolute -top-4 -right-4 bg-white rounded-xl shadow-lg border border-[#e2e2e8] p-4 animate-in delay-500">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <!-- <div>
                                <p class="text-sm font-bold text-[#1a1a2e] leading-none">100% secure</p>
                                <p class="text-xs text-[#5a5a6e] mt-0.5">Bank-grade encryption</p>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-0 inset-x-0 h-32 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
    </section>

    <!-- LOGO BAR -->
    <section class="py-12 border-b border-[#e2e2e8] bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-xs font-semibold text-[#8a8a9a] uppercase tracking-[0.2em] text-center mb-8">Trusted by industry leaders</p>
            <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-6 opacity-50">
                <span class="text-lg font-bold text-[#4a4a5a]">PropertyMax</span>
                <span class="text-lg font-bold text-[#4a4a5a]">RentEase</span>
                <span class="text-lg font-bold text-[#4a4a5a]">LandlordPro</span>
                <span class="text-lg font-bold text-[#4a4a5a]">TenantHub</span>
                <span class="text-lg font-bold text-[#4a4a5a]">RealEstate.io</span>
                <span class="text-lg font-bold text-[#4a4a5a]">HomeBase</span>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-24 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center mb-20 reveal">
                <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em]">Features</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1a1a2e] tracking-tight mt-4 mb-5">
                    Everything you need to run your properties
                </h2>
                <p class="text-lg text-[#5a5a6e] leading-relaxed">
                    A complete toolkit designed for landlords who take their business seriously.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="group relative p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] card-hover cursor-default reveal">
                    <div class="w-12 h-12 bg-brand-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1a1a2e] mb-3">Automated Rent Collection</h3>
                    <p class="text-sm text-[#5a5a6e] leading-relaxed">Set it once. Get paid on time, every time. Automatic reminders and receipts included.</p>
                </div>

                <div class="group relative p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] card-hover cursor-default reveal delay-100">
                    <div class="w-12 h-12 bg-brand-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1a1a2e] mb-3">Tenant Screening</h3>
                    <p class="text-sm text-[#5a5a6e] leading-relaxed">Background checks, credit reports, and rental history — all built in and easy to review.</p>
                </div>

                <div class="group relative p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] card-hover cursor-default reveal delay-200">
                    <div class="w-12 h-12 bg-brand-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1a1a2e] mb-3">Maintenance Tracking</h3>
                    <p class="text-sm text-[#5a5a6e] leading-relaxed">Tenants submit requests. You assign and track. Everyone knows what's happening, in real time.</p>
                </div>

                <div class="group relative p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] card-hover cursor-default reveal">
                    <div class="w-12 h-12 bg-brand-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1a1a2e] mb-3">Smart Analytics</h3>
                    <p class="text-sm text-[#5a5a6e] leading-relaxed">Know your portfolio inside out. Vacancy trends, revenue reports, and expense breakdowns.</p>
                </div>

                <div class="group relative p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] card-hover cursor-default reveal delay-100">
                    <div class="w-12 h-12 bg-brand-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1a1a2e] mb-3">Lease Management</h3>
                    <p class="text-sm text-[#5a5a6e] leading-relaxed">Digital leases, renewals, and documents. No more paper trails or lost files.</p>
                </div>

                <div class="group relative p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] card-hover cursor-default reveal delay-200">
                    <div class="w-12 h-12 bg-brand-600 rounded-xl flex items-center justify-center mb-6 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1a1a2e] mb-3">Multi-User Access</h3>
                    <p class="text-sm text-[#5a5a6e] leading-relaxed">Give your team, co-host, or accountant the right level of access — read-only or full control.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="py-20 bg-[#1a1a2e] text-white reveal">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
                <div>
                    <p class="text-4xl lg:text-5xl font-black mb-2 tracking-tight">50+</p>
                    <p class="text-xs font-medium text-white/50 uppercase tracking-[0.15em]">Active Landlord</p>
                </div>
                <div>
                    <p class="text-4xl lg:text-5xl font-black mb-2 tracking-tight">25k+</p>
                    <p class="text-xs font-medium text-white/50 uppercase tracking-[0.15em]">Active Tenant</p>
                </div>
                <div>
                    <p class="text-4xl lg:text-5xl font-black mb-2 tracking-tight">12</p>
                    <p class="text-xs font-medium text-white/50 uppercase tracking-[0.15em]">Countries</p>
                </div>
                <div>
                    <p class="text-4xl lg:text-5xl font-black mb-2 tracking-tight">4.9/5</p>
                    <p class="text-xs font-medium text-white/50 uppercase tracking-[0.15em]">App rating</p>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS -->
    <section id="testimonials" class="py-24 lg:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center mb-20 reveal">
                <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em]">Testimonials</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1a1a2e] tracking-tight mt-4 mb-5">
                    Loved by landlords everywhere
                </h2>
                <p class="text-lg text-[#5a5a6e] leading-relaxed">
                    See what property managers are saying about FineHouse.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] reveal">
                    <div class="flex items-center gap-1 mb-5">
                        {!! str_repeat('<svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>', 5) !!}
                    </div>
                    <p class="text-sm text-[#4a4a5a] leading-relaxed mb-6">"FineHouse saved us 12 hours a week on rent collection alone. The automated reminders are a game changer."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white text-sm font-bold">SK</div>
                        <div>
                            <p class="text-sm font-bold text-[#1a1a2e]">Sarah K.</p>
                            <p class="text-xs text-[#5a5a6e]">Manages 8 properties</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] reveal delay-100">
                    <div class="flex items-center gap-1 mb-5">
                        {!! str_repeat('<svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>', 5) !!}
                    </div>
                    <p class="text-sm text-[#4a4a5a] leading-relaxed mb-6">"The tenant screening feature alone is worth it. I've found great tenants every time since switching."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-sm font-bold">MJ</div>
                        <div>
                            <p class="text-sm font-bold text-[#1a1a2e]">Michael J.</p>
                            <p class="text-xs text-[#5a5a6e]">Real estate investor</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 rounded-2xl bg-[#f8f9fc] border border-[#e2e2e8] reveal delay-200">
                    <div class="flex items-center gap-1 mb-5">
                        {!! str_repeat('<svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.56 5.82 22 7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>', 5) !!}
                    </div>
                    <p class="text-sm text-[#4a4a5a] leading-relaxed mb-6">"My tenants love the portal. They can pay rent, submit maintenance requests, and message me all in one place."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-sm font-bold">AL</div>
                        <div>
                            <p class="text-sm font-bold text-[#1a1a2e]">Alex L.</p>
                            <p class="text-xs text-[#5a5a6e]">Property manager</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 lg:py-32 bg-[#f8f9fc] reveal">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <span class="text-xs font-semibold text-brand-600 uppercase tracking-[0.2em]">Get started</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#1a1a2e] tracking-tight mt-4 mb-5">
                Ready to simplify your property management?
            </h2>
            <p class="text-lg text-[#5a5a6e] leading-relaxed mb-10 max-w-lg mx-auto">
                Join thousands of landlords who have streamlined their operations with FineHouse.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#1a1a2e] text-white font-semibold rounded-xl hover:bg-[#2a2a3e] transition-all shadow-lg">
                    Start your free trial
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="#" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-[#1a1a2e] font-semibold rounded-xl border border-[#e2e2e8] hover:bg-white hover:border-[#d0d0d8] transition-all shadow-sm">
                    Talk to sales
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="py-16 bg-white border-t border-[#e2e2e8]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-5">
                        <div class="w-7 h-7 bg-brand-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-[#1a1a2e]">FineHouse</span>
                    </div>
                    <p class="text-sm text-[#5a5a6e] leading-relaxed max-w-sm">
                        Modern property management for serious landlords. Built with care since 2020.
                    </p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-[#8a8a9a] uppercase tracking-[0.15em] mb-6">Product</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Features</a></li>
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Pricing</a></li>
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Integrations</a></li>
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Changelog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-[#8a8a9a] uppercase tracking-[0.15em] mb-6">Company</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">About</a></li>
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Blog</a></li>
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Privacy</a></li>
                        <li><a href="#" class="text-sm text-[#4a4a5a] hover:text-[#1a1a2e] transition-colors">Terms</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-[#e2e2e8] flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-[#8a8a9a]">&copy; {{ date('Y') }} FineHouse. All rights reserved.</p>
                <div class="flex items-center gap-5">
                    <a href="#" class="text-[#8a8a9a] hover:text-[#1a1a2e] transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('nav-blur', window.scrollY > 20);
        });

        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

        document.querySelectorAll('.reveal, .reveal-scale').forEach(el => observer.observe(el));

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            alert('Mobile navigation would open here.');
        });
    </script>
</body>
</html>
