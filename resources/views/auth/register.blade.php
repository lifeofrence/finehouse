<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Join FineHouse | Modern Property Management</title>

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
    </style>
</head>
<body class="antialiased bg-white text-slate-900">
    <div class="flex min-h-screen">
        <!-- Left Side: Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-20 bg-slate-50">
            <div class="w-full max-w-md">
                <div class="mb-10 lg:hidden text-center">
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-slate-900 tracking-widest uppercase">FineHouse</span>
                    </a>
                </div>

                <div class="text-center lg:text-left">
                    <h1 class="text-4xl font-black text-slate-900 mb-2">Create Account</h1>
                    <p class="text-slate-500 mb-10">Start your journey with the world's most advanced property management platform.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-[0.15em]">Full Name</label>
                        <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe"
                               class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all outline-none text-slate-900 placeholder:text-slate-400 font-semibold" />
                        @if ($errors->has('name'))
                            <p class="mt-2 text-sm text-pink-600 font-bold tracking-tight">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-[0.15em]">Email Address</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="john@example.com"
                               class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all outline-none text-slate-900 placeholder:text-slate-400 font-semibold" />
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-pink-600 font-bold tracking-tight">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-[0.15em]">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                               class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all outline-none text-slate-900 placeholder:text-slate-400 font-semibold" />
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-pink-600 font-bold tracking-tight">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-black text-slate-500 mb-2 uppercase tracking-[0.15em]">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                               class="w-full px-5 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-600 transition-all outline-none text-slate-900 placeholder:text-slate-400 font-semibold" />
                        @if ($errors->has('password_confirmation'))
                            <p class="mt-2 text-sm text-pink-600 font-bold tracking-tight">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-5 bg-slate-900 hover:bg-black text-white rounded-2xl font-black text-lg transition-all shadow-2xl shadow-slate-200 uppercase tracking-[0.2em] mb-6">
                            Register Now
                        </button>
                    </div>
                </form>

                <div class="pt-8 border-t border-slate-200 text-center lg:text-left">
                    <p class="text-slate-500 font-bold">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors">Sign In &rarr;</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side: Luxury Visuals -->
        <div class="hidden lg:flex lg:w-1/2 relative">
            <img src="/images/register_bg.png" alt="Luxury Entrance" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-indigo-600/10 backdrop-blur-[1px]"></div>
            
            <div class="relative z-10 flex flex-col justify-between p-16 w-full h-full">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-2xl">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span class="text-3xl font-black text-white tracking-widest uppercase">FineHouse</span>
                </a>

                <div class="bg-white/10 backdrop-blur-xl p-10 rounded-[2.5rem] border border-white/20 max-w-lg">
                    <div class="flex gap-1 mb-6">
                        <svg class="w-6 h-6 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-6 h-6 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-6 h-6 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-6 h-6 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-6 h-6 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </div>
                    <p class="text-white text-2xl font-light italic leading-snug mb-6">"FineHouse has completely transformed our operations. We've seen a 30% increase in productivity since moving our portfolio here."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-slate-200 border-2 border-white/20"></div>
                        <div>
                            <p class="text-white font-black text-sm uppercase tracking-widest">Sarah Jenkins</p>
                            <p class="text-white/60 text-xs font-bold uppercase tracking-wider">Apex Properties Ltd.</p>
                        </div>
                    </div>
                </div>

                <div class="text-white/40 text-xs font-black uppercase tracking-[0.3em]">
                    Premium Property Management Suite
                </div>
            </div>
        </div>
    </div>
</body>
</html>
