<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Finehouse') }} - Premium Property Management</title>

        <!-- Fonts (Using Google Fonts Outfit and Inter for Premium Aesthetics) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Inter', sans-serif; }
            h1, h2, h3, h4, h5, h6, .brand-font { font-family: 'Outfit', sans-serif; letter-spacing: -0.02em; }
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            .dark ::-webkit-scrollbar-thumb { background: #334155; }
            .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }
        </style>

        <script>
            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F8FAFC] dark:bg-[#050505] text-[#1b1b18] dark:text-[#EDEDEC] text-sm selection:bg-indigo-500 selection:text-white">
        <!-- Main Layout Container -->
        <div class="flex h-screen overflow-hidden">
            
            <!-- Sidebar Navigation -->
            <div class="hidden md:flex md:flex-shrink-0 relative z-20 shadow-xl dark:shadow-none">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="flex flex-col flex-1 w-full overflow-hidden relative z-10">
                <!-- Topbar -->
                @include('layouts.topbar')

                <!-- Dynamic Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50/30 dark:bg-black/20">
                    <div class="p-6 md:p-8 mx-auto max-w-7xl animate-fade-in-up">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <!-- Fade in animation -->
        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.4s ease-out forwards;
            }
        </style>
    </body>
</html>
