<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FineHouse | Modern Property Management</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        outfit: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }
        
        .glass-light {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .text-gradient {
            background: linear-gradient(to right, #4f46e5, #7c3aed, #db2777);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .hero-bg {
            background: radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.05) 0%, transparent 40%),
                        radial-gradient(circle at 0% 100%, rgba(236, 72, 153, 0.05) 0%, transparent 40%);
        }

        .card-shadow {
            box-shadow: 0 10px 50px -12px rgba(0, 0, 0, 0.08);
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased overflow-x-hidden hero-bg">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-300 h-20" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex justify-between items-center h-full">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 group-hover:rotate-12 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900">Fine<span class="text-indigo-600">House</span></span>
                </div>
                
                <div class="hidden md:flex items-center space-x-10">
                    <a href="#features" class="text-slate-500 hover:text-indigo-600 font-medium transition-colors">Features</a>
                    <a href="#about" class="text-slate-500 hover:text-indigo-600 font-medium transition-colors">About</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-7 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full font-semibold transition-all shadow-xl shadow-indigo-200">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-slate-500 hover:text-indigo-600 font-medium transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-7 py-2.5 bg-slate-900 text-white hover:bg-slate-800 rounded-full font-semibold transition-all shadow-xl shadow-slate-200">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
                
                <button class="md:hidden text-slate-600">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-sm font-bold mb-8">
                        <span class="flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-indigo-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        New: Virtual Tours Integration
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-slate-900 leading-tight mb-8">
                        Redefining how you <span class="text-gradient">Manage Property.</span>
                    </h1>
                    <p class="text-xl text-slate-500 leading-relaxed mb-12 max-w-xl mx-auto lg:mx-0">
                        The all-in-one platform for modern landlords. Automated rent collection, smart tenant screening, and seamless maintenance tracking.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="px-10 py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold text-lg transition-all shadow-2xl shadow-indigo-200 flex items-center justify-center gap-2">
                            Go Pro Today
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#features" class="px-10 py-5 bg-white text-slate-900 rounded-2xl font-bold text-lg hover:bg-slate-50 transition-all border border-slate-200 flex items-center justify-center shadow-lg shadow-slate-100">
                            Explore Features
                        </a>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="absolute -inset-10 bg-indigo-500/10 blur-[100px] rounded-full"></div>
                    <div class="relative group">
                        <img src="/images/hero_light.png" alt="Luxury Villa" class="rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.15)] animate-float w-full object-cover">
                        
                        <div class="absolute top-10 -right-6 lg:-right-12 bg-white/90 backdrop-blur-md p-5 rounded-2xl shadow-xl border border-white/50 animate-float" style="animation-delay: 1s">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-slate-900 font-bold leading-none">Rent Paid</p>
                                    <p class="text-slate-400 text-xs mt-1">Successfully processed</p>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -bottom-10 -left-6 lg:-left-12 bg-white/90 backdrop-blur-md p-6 rounded-2xl shadow-xl border border-white/50 animate-float" style="animation-delay: 2s">
                            <div class="flex items-center gap-4">
                                <div class="flex -space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 border-2 border-white"></div>
                                    <div class="w-10 h-10 rounded-full bg-purple-100 border-2 border-white"></div>
                                    <div class="w-10 h-10 rounded-full bg-slate-900 border-2 border-white flex items-center justify-center text-[10px] text-white font-bold">99+</div>
                                </div>
                                <p class="text-slate-900 font-bold text-sm">Active Tenants</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Filling / White Space -->
    <section id="features" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-24">
                <span class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-black tracking-widest uppercase mb-4 inline-block">World Class Infrastructure</span>
                <h2 class="text-4xl lg:text-5xl font-black text-slate-900 mb-6 tracking-tight">Built to handle <span class="text-indigo-600">anything.</span></h2>
                <div class="w-24 h-1.5 bg-indigo-600 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid md:grid-cols-3 gap-12">
                <!-- Box 1 -->
                <div class="group p-10 rounded-[2rem] bg-slate-50 hover:bg-indigo-600 transition-all duration-500 card-shadow border border-slate-100">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-8 group-hover:scale-110 group-hover:bg-indigo-500 transition-all">
                        <svg class="w-8 h-8 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-white mb-4">Ultra Performance</h3>
                    <p class="text-slate-500 group-hover:text-indigo-100 leading-relaxed font-medium">Experience lightning fast dashboard speeds with our optimized cloud architecture.</p>
                </div>
                
                <!-- Box 2 -->
                <div class="group p-10 rounded-[2rem] bg-slate-50 hover:bg-indigo-600 transition-all duration-500 card-shadow border border-slate-100">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-8 group-hover:scale-110 group-hover:bg-indigo-500 transition-all">
                        <svg class="w-8 h-8 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-white mb-4">Bulletproof Security</h3>
                    <p class="text-slate-500 group-hover:text-indigo-100 leading-relaxed font-medium">Your data and your tenants' payments are protected with bank-grade encryption.</p>
                </div>
                
                <!-- Box 3 -->
                <div class="group p-10 rounded-[2rem] bg-slate-50 hover:bg-indigo-600 transition-all duration-500 card-shadow border border-slate-100">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-8 group-hover:scale-110 group-hover:bg-indigo-500 transition-all">
                        <svg class="w-8 h-8 text-indigo-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 group-hover:text-white mb-4">Cloud Sync</h3>
                    <p class="text-slate-500 group-hover:text-indigo-100 leading-relaxed font-medium">Access your property files and lease agreements from any device, anywhere in the world.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
                <div>
                    <p class="text-4xl lg:text-6xl font-black mb-2">$500M+</p>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Rent Collected</p>
                </div>
                <div>
                    <p class="text-4xl lg:text-6xl font-black mb-2">25k+</p>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Active Users</p>
                </div>
                <div>
                    <p class="text-4xl lg:text-6xl font-black mb-2">12</p>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Countries</p>
                </div>
                <div>
                    <p class="text-4xl lg:text-6xl font-black mb-2">4.9/5</p>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">App Rating</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-16 mb-20">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-slate-900">FineHouse</span>
                    </div>
                    <p class="text-slate-500 text-lg leading-relaxed max-w-sm">
                        Leading the charge in digital property management since 2020.
                    </p>
                </div>
                <div>
                    <h4 class="text-slate-900 font-black text-xs uppercase tracking-widest mb-8">Navigation</h4>
                    <ul class="space-y-4 font-bold text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Integrations</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Documentation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-slate-900 font-black text-xs uppercase tracking-widest mb-8">Legal</h4>
                    <ul class="space-y-4 font-bold text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-400 font-medium">© 2024 FineHouse Inc. All rights reserved.</p>
                <div class="flex gap-8 text-slate-400">
                    <a href="#" class="hover:text-indigo-600 transition-colors"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('glass-light', 'h-16', 'shadow-sm');
                navbar.classList.remove('h-20');
            } else {
                navbar.classList.remove('glass-light', 'h-16', 'shadow-sm');
                navbar.classList.add('h-20');
            }
        });
    </script>
</body>
</html>
