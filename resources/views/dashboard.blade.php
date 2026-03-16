<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-medium">{{ __("Welcome to Finehouse Property Management!") }}</h3>
                    
                    @if(Auth::user()->role === 'tenant')
                        <div class="mt-4">
                            @if(!Auth::user()->tenantProfile)
                                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                                    <p class="font-bold">Profile Incomplete!</p>
                                    <p>Please complete your profile configuration.</p>
                                </div>
                                <a href="{{ route('tenant.profile') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Setup Profile') }}
                                </a>
                            @else
                                <!-- My Rented Room Section -->
                                @php
                                    $activeBooking = Auth::user()->bookings()
                                        ->whereIn('status', ['confirmed', 'approved'])
                                        ->with('room.property', 'room.images')
                                        ->latest()
                                        ->first();
                                @endphp

                                @if($activeBooking)
                                    <div class="mb-8 overflow-hidden rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                                        <div class="flex flex-col md:flex-row">
                                            <div class="md:w-1/3 relative h-48 md:h-auto overflow-hidden">
                                                @if($activeBooking->room->images->count() > 0)
                                                    <img src="{{ asset('storage/' . $activeBooking->room->images->first()->image_path) }}" class="h-full w-full object-cover" alt="My Room">
                                                @else
                                                    <div class="h-full w-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-300">
                                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                                    </div>
                                                @endif
                                                <div class="absolute top-4 left-4">
                                                    <span class="px-3 py-1 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">Active Unit</span>
                                                </div>
                                            </div>
                                            <div class="p-8 md:w-2/3">
                                                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                                                    <div>
                                                        <h4 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">{{ $activeBooking->room->room_number }}</h4>
                                                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">{{ $activeBooking->room->property->name }}</p>
                                                    </div>
                                                    <div class="lg:text-right">
                                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lease Rate</p>
                                                        <p class="text-xl font-black text-indigo-600">₦{{ number_format($activeBooking->room->price) }}</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8 pt-6 border-t border-slate-100 dark:border-slate-800">
                                                    <div>
                                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                                                        <div class="flex items-center gap-1.5">
                                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase">Occupying</p>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Roommates</p>
                                                        @php
                                                            $roommates = $activeBooking->room->bookings()
                                                                ->whereIn('status', ['confirmed', 'approved'])
                                                                ->where('user_id', '!=', Auth::id())
                                                                ->count();
                                                        @endphp
                                                        <p class="text-xs font-black text-slate-900 dark:text-white uppercase">{{ $roommates }} Others</p>
                                                    </div>
                                                    @if(Auth::user()->tenantProfile->rent_commencement_date)
                                                        <div>
                                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Lease Start</p>
                                                            <p class="text-xs font-black text-slate-900 dark:text-white uppercase">{{ \Carbon\Carbon::parse(Auth::user()->tenantProfile->rent_commencement_date)->format('M d, Y') }}</p>
                                                        </div>
                                                    @endif
                                                    @if(Auth::user()->tenantProfile->rent_expiry_date)
                                                        <div>
                                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Lease Ends</p>
                                                            <div class="flex items-center gap-2">
                                                                <p class="text-xs font-black text-slate-900 dark:text-white uppercase">{{ \Carbon\Carbon::parse(Auth::user()->tenantProfile->rent_expiry_date)->format('M d, Y') }}</p>
                                                                @php
                                                                    $daysRemaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse(Auth::user()->tenantProfile->rent_expiry_date), false);
                                                                @endphp
                                                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tighter {{ $daysRemaining > 30 ? 'bg-emerald-50 text-emerald-600' : ($daysRemaining > 0 ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600') }}">
                                                                    {{ $daysRemaining > 0 ? (int)$daysRemaining . ' Days Left' : 'Expired' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="mt-4 flex gap-4">
                                                    <a href="{{ route('rooms.show', $activeBooking->room->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-md">
                                                        <span>View Room Details</span>
                                                    </a>
                                                    <a href="{{ route('payments.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-md">
                                                        <span>Pay Rent</span>
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <p class="text-gray-600 mb-4">You are registered as a <strong>{{ ucfirst(Auth::user()->tenantProfile->type) }}</strong>.</p>
                                <!-- Modern Bento Grid Action Layout -->
                                <div class="grid grid-cols-1 md:grid-cols-6 grid-rows-2 gap-4 mt-10 h-auto md:h-[450px]">
                                    
                                    <!-- Request Interview (Large Primary) -->
                                    <a href="{{ route('bookings.create_general') }}" class="md:col-span-3 md:row-span-2 group relative overflow-hidden bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white flex flex-col justify-end shadow-2xl shadow-indigo-200 dark:shadow-none hover:-translate-y-2 transition-all duration-500">
                                        <div class="absolute top-0 right-0 p-8 opacity-20 group-hover:rotate-12 transition-transform duration-700">
                                            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                                        </div>
                                        <div class="relative z-10">
                                            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                                            </div>
                                            <h4 class="text-3xl font-black uppercase tracking-tight mb-2">Request Interview</h4>
                                            <p class="text-xs font-bold text-indigo-100 uppercase tracking-widest leading-relaxed">Schedule your session & get assigned a room.</p>
                                        </div>
                                    </a>

                                    <!-- My Bookings (Medium) -->
                                    <a href="{{ route('bookings.index') }}" class="md:col-span-3 group relative overflow-hidden bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-[2rem] p-8 flex items-center justify-between shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                        <div>
                                            <h5 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mb-1">My Bookings</h5>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Track your schedules</p>
                                        </div>
                                        <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z" /></svg>
                                        </div>
                                    </a>

                                    <!-- Rent & Payments (Small) -->
                                    <a href="{{ route('payments.index') }}" class="md:col-span-2 group relative overflow-hidden bg-emerald-500 rounded-[2rem] p-8 text-white flex flex-col justify-between shadow-lg shadow-emerald-100 dark:shadow-none hover:-translate-y-1 transition-all">
                                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-black uppercase tracking-tight">Payments</h5>
                                            <p class="text-[9px] font-bold text-emerald-100 uppercase tracking-widest mt-0.5">Wallet & Receipts</p>
                                        </div>
                                    </a>

                                    <!-- Report Incident & Announcements (Small) -->
                                    <div class="md:col-span-1 grid grid-rows-2 gap-4">
                                        <a href="{{ route('maintenance.index') }}" class="group relative overflow-hidden bg-slate-100 dark:bg-slate-800/50 rounded-[1.5rem] p-4 flex flex-col items-center justify-center text-center hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all">
                                            <svg class="w-5 h-5 text-slate-400 group-hover:text-rose-500 transition-colors mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            <span class="text-[8px] font-black text-slate-500 uppercase tracking-widest group-hover:text-rose-600 transition-colors">Support</span>
                                        </a>
                                        <a href="{{ route('announcements.index') }}" class="group relative overflow-hidden bg-purple-600 rounded-[1.5rem] p-4 flex flex-col items-center justify-center text-center hover:bg-purple-700 transition-all shadow-lg shadow-purple-100 dark:shadow-none">
                                            <svg class="w-5 h-5 text-purple-100 group-hover:scale-110 transition-transform mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                                            <span class="text-[8px] font-black text-white uppercase tracking-widest">News</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Secondary Settings Row -->
                                <div class="mt-8 flex flex-col md:flex-row gap-4">
                                    <a href="{{ route('profile.edit') }}" class="flex-1 group flex items-center justify-between p-6 bg-slate-900 rounded-3xl text-white hover:bg-slate-800 transition-all">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            </div>
                                            <div>
                                                <h6 class="text-sm font-black uppercase tracking-tight tracking-widest">Update Security</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Change account password</p>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-600 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>

                                    <a href="{{ route('tenant.profile') }}" class="flex-1 group flex items-center justify-between p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl hover:border-indigo-500/30 transition-all">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center text-slate-500">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <div>
                                                <h6 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight tracking-widest">Edit Profile</h6>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Modify your details</p>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @elseif(!Auth::user()->company_id)
                        <div class="mt-4">
                            <p class="text-gray-600 mb-4">You currently haven't registered a company.</p>
                            <a href="{{ route('companies.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Register a Company') }}
                            </a>
                        </div>
                    @else
                        <div class="mt-4">
                            <p class="text-gray-600 mb-4">Company ID: {{ Auth::user()->company->name ?? Auth::user()->company_id }}</p>
                            
                            @if(in_array(Auth::user()->role, ['admin', 'landlord', 'caretaker']))
                                <a href="{{ route('admin.tenants.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-black active:bg-black focus:outline-none focus:border-black focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 mb-2">
                                    {{ __('Manage Tenants') }}
                                </a>
                            @endif

                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'landlord')
                                <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 mb-2">
                                    {{ __('Add a New Property') }}
                                </a>

                                <a href="{{ route('personnel.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2 mb-2">
                                    {{ __('Add Personnel') }}
                                </a>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin', 'landlord', 'caretaker']))
                                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2 mr-2">
                                    {{ __('Manage Rooms') }}
                                </a>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin', 'landlord', 'accountant', 'caretaker']))
                                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2 mr-2">
                                    {{ __('Monitor Payments') }}
                                </a>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin', 'landlord', 'caretaker']))
                                <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2 mr-2">
                                    {{ __('Manage Bookings') }}
                                </a>
                                <a href="{{ route('maintenance.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2 mr-2">
                                    {{ __('Maintenance Log') }}
                                </a>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin', 'landlord', 'lodge_president', 'caretaker']))
                                <a href="{{ route('announcements.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:border-purple-900 focus:ring ring-purple-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2">
                                    {{ __('Announcements') }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
