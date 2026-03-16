<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | FineHouse</title>

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
        }
        .login-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-900">
    <div class="flex min-h-screen">
        <!-- Left Side: Image & Branding -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <img src="/images/login_bg.png" alt="Luxury Interior" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-slate-900/20 backdrop-blur-[2px]"></div>
            
            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white tracking-widest uppercase">FineHouse</span>
                </a>

                <div class="max-w-md">
                    <h2 class="text-4xl font-bold text-white mb-4">Welcome back to your Property Empire.</h2>
                    <p class="text-white/80 text-lg">Manage your assets, track payments, and communicate with tenants in one beautiful interface.</p>
                </div>

                <div class="text-white/60 text-sm">
                    &copy; 2024 FineHouse. All rights reserved.
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-slate-50">
            <div class="w-full max-w-md">
                <div class="mb-10 lg:hidden">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-slate-900 tracking-widest uppercase">FineHouse</span>
                    </a>
                </div>

                <h1 class="text-3xl font-black text-slate-900 mb-2">Member Login</h1>
                <p class="text-slate-500 mb-8">Please enter your credentials to access your account.</p>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-4 rounded-xl border border-green-100">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wider">Email Address</label>
                        <div class="relative">
                            <input id="email" 
                                   type="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   placeholder="name@company.com"
                                   class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all outline-none text-slate-900 placeholder:text-slate-400 font-medium" />
                        </div>
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-pink-600 font-semibold">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700 uppercase tracking-wider">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Forgot?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••"
                                   class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all outline-none text-slate-900 placeholder:text-slate-400 font-medium" />
                        </div>
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-pink-600 font-semibold">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <label for="remember_me" class="flex items-center cursor-pointer group">
                            <div class="relative">
                                <input id="remember_me" type="checkbox" name="remember" class="sr-only">
                                <div class="w-6 h-6 bg-white border-2 border-slate-200 rounded-lg group-hover:border-indigo-400 transition-all peer-checked:bg-indigo-600 peer-checked:border-indigo-600"></div>
                                <svg class="absolute top-1 left-1 w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-all pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="ms-3 text-sm font-bold text-slate-600 group-hover:text-slate-900 transition-colors">Keep me signed in</span>
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="w-full py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-lg transition-all shadow-xl shadow-indigo-100 hover:shadow-2xl hover:shadow-indigo-200 uppercase tracking-widest">
                            Log In
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-10 border-t border-slate-200 text-center">
                    <p class="text-slate-500 font-medium">
                        Don't have an account yet? 
                        <a href="{{ route('register') }}" class="text-indigo-600 font-bold hover:text-indigo-700 transition-colors">Join FineHouse &rarr;</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
