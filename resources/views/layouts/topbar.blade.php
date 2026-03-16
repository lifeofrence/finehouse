<header class="flex items-center justify-between px-8 py-5 bg-white border-b border-gray-100 shadow-sm dark:bg-[#0f0f0f] dark:border-gray-800 transition-all">
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Hamburger -->
        <button class="mr-4 text-gray-500 focus:outline-none md:hidden hover:text-indigo-600 transition-colors">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        
        <!-- Search bar styling (optional for premium feel) -->
        <div class="hidden lg:flex items-center bg-gray-50 dark:bg-gray-800 rounded-full px-4 py-2 border border-transparent focus-within:border-indigo-300 focus-within:ring-2 focus-within:ring-indigo-100 dark:focus-within:ring-indigo-900 transition-all">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Search..." class="bg-transparent border-none focus:ring-0 text-sm ml-2 w-48 dark:text-gray-300 dark:placeholder-gray-500 outline-none">
        </div>

        @if(Auth::user()->company)
            <div class="hidden lg:flex items-center gap-3 ml-2 group transition-all duration-300">
                <div class="h-6 w-px bg-slate-200 dark:bg-slate-700"></div>
                <div class="flex flex-col">
                    <!-- <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] leading-none mb-1">Authenticated Firm</span> -->
                    <span class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white leading-none mb-0.5 group-hover:text-indigo-600 transition-colors">{{ Auth::user()->company->name }}</span>
                </div>
            </div>
        @endif
    </div>

    <div class="flex items-center space-x-4">
        
        <!-- Dark Mode Toggle Button -->
        <button id="theme-toggle" type="button" class="relative p-2 text-gray-500 hover:text-indigo-500 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-full text-sm hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
        </button>

        <!-- Notification Bell (Fake UI for premium aesthetics) -->
        <button class="relative p-2 text-gray-400 hover:text-indigo-500 transition-colors rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-900"></span>
        </button>

        <div class="h-6 w-px bg-gray-200 dark:bg-gray-700 mx-2"></div>

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center space-x-3 text-sm text-gray-700 bg-white border border-gray-200 shadow-sm rounded-full dark:text-gray-200 dark:bg-gray-800 dark:border-gray-700 focus:outline-none pl-1 pr-4 py-1 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all group">
                    <!-- Profile Avatar -->
                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs shadow-inner">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    
                    <div class="flex flex-col items-start translate-y-0.5">
                        <span class="font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors leading-none">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 capitalize pt-1">{{ str_replace('_', ' ', Auth::user()->role) }}</span>
                    </div>

                    <svg class="w-4 h-4 ml-2 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')" class="!flex items-center px-4 py-2 hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ __('View Profile') }}
                </x-dropdown-link>
                
                <div class="border-t border-gray-100 dark:border-gray-700"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="!flex items-center px-4 py-2 text-red-600 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/20">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        {{ __('Sign Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
<!-- Page Title if passed inside layout slot -> Extracting from layout header -->
@if (isset($header))
<div class="px-8 py-5 bg-white/50 backdrop-blur-xl border-b border-gray-100 shadow-sm dark:bg-[#0f0f0f]/80 dark:border-gray-800 sticky top-0 z-10">
    <div class="flex items-center justify-between">
        {{ $header }}
    </div>
</div>
@endif

<script>
    var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    // Change the icons inside the button based on previous settings
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }

    var themeToggleBtn = document.getElementById('theme-toggle');

    themeToggleBtn.addEventListener('click', function() {
        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // if set via local storage previously
        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }

        // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    });
</script>
