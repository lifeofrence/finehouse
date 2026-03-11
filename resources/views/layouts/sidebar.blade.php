<aside class="flex flex-col w-64 h-full px-5 py-8 overflow-y-auto bg-white border-r border-gray-100 dark:bg-[#0f0f0f] dark:border-gray-800 transition-all">
    <a href="{{ route('dashboard') }}" class="flex items-center pl-2 space-x-3 text-indigo-600 dark:text-indigo-400 mb-8">
        <svg class="w-8 h-8 drop-shadow-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <span class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Finehouse</span>
    </a>

    <div class="flex flex-col flex-1">
        <nav class="flex-1 space-y-1.5 text-sm">
            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">
                {{ __('Overview') }}
            </x-sidebar-link>

            @php
                $role = Auth::user()->role;
            @endphp

            @if($role === 'tenant')
                @if(Auth::user()->tenantProfile)
                    <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Tenant Services</div>
                    
                    <x-sidebar-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')" icon="search">
                        {{ __('Browse Rooms') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')" icon="calendar">
                        {{ __('My Bookings') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" icon="credit-card">
                        {{ __('Rent & Payments') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')" icon="wrench">
                        {{ __('Report Incident') }}
                    </x-sidebar-link>
                    
                    <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">Settings</div>
                    
                    <x-sidebar-link :href="route('tenant.profile')" :active="request()->routeIs('tenant.profile')" icon="user">
                        {{ __('Edit Profile') }}
                    </x-sidebar-link>
                @endif

            @elseif(Auth::user()->company_id)
                <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">
                    Management
                </div>

                @if(in_array($role, ['admin', 'landlord', 'caretaker']))
                    <x-sidebar-link :href="route('rooms.index')" :active="request()->routeIs('rooms.*')" icon="office-building">
                        {{ __('Manage Rooms') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')" icon="calendar-check">
                        {{ __('Bookings') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')" icon="clipboard-list">
                        {{ __('Maintenance Log') }}
                    </x-sidebar-link>
                @endif

                @if(in_array($role, ['admin', 'landlord', 'accountant', 'caretaker']))
                    <x-sidebar-link :href="route('payments.index')" :active="request()->routeIs('payments.*')" icon="currency-dollar">
                        {{ __('Payments') }}
                    </x-sidebar-link>
                @endif

                @if(in_array($role, ['admin', 'landlord', 'lodge_president', 'caretaker']))
                    <x-sidebar-link :href="route('announcements.index')" :active="request()->routeIs('announcements.*')" icon="speakerphone">
                        {{ __('Announcements') }}
                    </x-sidebar-link>
                @endif
                
                @if(in_array($role, ['admin', 'landlord']))
                    <div class="pt-6 pb-2 text-xs font-semibold tracking-wider text-gray-400 uppercase">
                        Administration
                    </div>
                    
                    <x-sidebar-link :href="route('properties.create')" :active="request()->routeIs('properties.*')" icon="plus-circle">
                        {{ __('Properties') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('personnel.create')" :active="request()->routeIs('personnel.*')" icon="users">
                        {{ __('Personnel') }}
                    </x-sidebar-link>
                @endif
            @endif
        </nav>
        
        <div class="mt-auto pt-8">
            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl">
                <p class="text-xs font-medium text-indigo-700 dark:text-indigo-400">Finehouse v1.0.0</p>
                <p class="text-[10px] text-gray-500 mt-1 dark:text-gray-400">© 2026 Admin Panel</p>
            </div>
        </div>
    </div>
</aside>
