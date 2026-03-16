<aside class="flex flex-col w-64 h-full px-5 py-8 overflow-y-auto bg-white border-r border-gray-100 dark:bg-[#0f0f0f] dark:border-gray-800 transition-all custom-scrollbar">
    <div class="flex flex-col h-full">
        <!-- Branding Header -->
        <a href="{{ route('dashboard') }}" class="flex items-center pl-2 space-x-4 mb-10 group">
            @if(Auth::user()->company && Auth::user()->company->logo)
                <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-800 flex items-center justify-center p-1.5 shadow-sm group-hover:shadow-md transition-all">
                    <img src="{{ asset('storage/' . Auth::user()->company->logo) }}" class="w-full h-full object-contain" alt="{{ Auth::user()->company->name }}">
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white leading-none mb-0.5 group-hover:text-indigo-600 transition-colors">Finehouse</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight truncate max-w-[120px]">{{ Auth::user()->company->name }}</span>
                </div>
            @else
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200 dark:shadow-none transition-transform group-hover:scale-105">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-2xl font-black tracking-tighter text-slate-900 dark:text-white brand-font group-hover:text-indigo-600 transition-colors">Finehouse</span>
            @endif
        </a>

        <nav class="flex-grow space-y-1.5 overflow-y-auto custom-scrollbar">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                {{ __('Overview') }}
            </x-sidebar-link>

            @php
                $role = Auth::user()->role;
            @endphp

            @if($role === 'tenant')
                @if(Auth::user()->tenantProfile)
                    <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Tenant Services</div>
                    
                    {{-- 
                    <x-sidebar-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')" icon="search">
                        {{ __('Browse Rooms') }}
                    </x-sidebar-link>
                    --}}

                    <x-sidebar-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')" icon="calendar">
                        {{ __('My Bookings') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('bookings.create_general')" :active="request()->routeIs('bookings.create_general')" icon="plus-circle">
                        {{ __('Request Interview') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" icon="credit-card">
                        {{ __('Rent & Payments') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')" icon="wrench">
                        {{ __('Report Incident') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')" icon="speakerphone">
                        {{ __('Announcements') }}
                    </x-sidebar-link>
                    
                    <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Settings</div>
                    
                    <x-sidebar-link :href="route('tenant.profile')" :active="request()->routeIs('tenant.profile')" icon="user">
                        {{ __('Edit Profile') }}
                    </x-sidebar-link>
                @endif

            @elseif(Auth::user()->company_id || in_array($role, ['admin', 'super_admin']))
                <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">
                    Management
                </div>

                @if(in_array($role, ['super_admin', 'admin', 'landlord', 'caretaker']))
                    <x-sidebar-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index')" icon="office-building">
                        {{ __('Unit Inventory') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('rooms.assignment')" :active="request()->routeIs('rooms.assignment')" icon="user-group">
                        {{ __('Room Assignment') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')" icon="calendar-check">
                        {{ __('Bookings') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')" icon="clipboard-list">
                        {{ __('Maintenance Log') }}
                    </x-sidebar-link>
                @endif

                @if(in_array($role, ['super_admin', 'admin', 'landlord', 'accountant', 'caretaker']))
                    <x-sidebar-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" icon="currency-dollar">
                        {{ __('Payments') }}
                    </x-sidebar-link>
                @endif

                @if(in_array($role, ['super_admin', 'admin', 'landlord', 'lodge_president', 'caretaker']))
                    <x-sidebar-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')" icon="speakerphone">
                        {{ __('Announcements') }}
                    </x-sidebar-link>
                @endif
                
                @if(in_array($role, ['super_admin', 'admin', 'landlord', 'caretaker']))
                    <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">
                        Administration
                    </div>
                @endif
                    
                @if(in_array($role, ['super_admin', 'admin', 'landlord']))
                    <x-sidebar-link :href="route('companies.index')" :active="request()->routeIs('companies.*')" icon="office-building">
                        {{ __('Company Profile') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('properties.index')" :active="request()->routeIs('properties.*')" icon="home">
                        {{ __('Properties') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('activity-log.index')" :active="request()->routeIs('activity-log.*')" icon="clock">
                        {{ __('Activity Stream') }}
                    </x-sidebar-link>
                @endif

                @if(in_array($role, ['super_admin', 'admin', 'landlord', 'caretaker']))
                    <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">
                        User Management
                    </div>

                    <x-sidebar-link :href="route('admin.tenants.index')" :active="request()->routeIs('admin.tenants.index')" icon="users">
                        {{ __('Tenant Registry') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('admin.tenants.create')" :active="request()->routeIs('admin.tenants.create')" icon="user-add">
                        {{ __('Onboard Tenant') }}
                    </x-sidebar-link>

                    @if(in_array($role, ['super_admin', 'admin', 'landlord']))
                        <x-sidebar-link :href="route('personnel.index')" :active="request()->routeIs('personnel.*')" icon="identification">
                            {{ __('Staff Management') }}
                        </x-sidebar-link>
                    @endif
                @endif
            @endif

            <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Account</div>
            
            <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" icon="lock-closed">
                {{ __('Change Password') }}
            </x-sidebar-link>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-sidebar-link :href="route('logout')"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    icon="logout"
                    class="text-red-500 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20">
                    {{ __('Log Out') }}
                </x-sidebar-link>
            </form>
        </nav>
        
        <div class="mt-auto pt-8">
            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl">
                <p class="text-xs font-medium text-indigo-700 dark:text-indigo-400">Finehouse v1.0.0</p>
                <p class="text-[10px] text-gray-500 mt-1 dark:text-gray-400">© 2026 Admin Panel</p>
            </div>
        </div>
    </div>
</aside>
