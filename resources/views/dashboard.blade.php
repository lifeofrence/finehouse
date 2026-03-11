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
                                    <p>Please complete your profile configuration before browsing rooms.</p>
                                </div>
                                <a href="{{ route('tenant.profile') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Setup Profile') }}
                                </a>
                            @else
                                <p class="text-gray-600 mb-4">You are registered as a <strong>{{ ucfirst(Auth::user()->tenantProfile->type) }}</strong>.</p>
                                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                    {{ __('Browse Available Rooms') }}
                                </a>
                                <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2 mr-2">
                                    {{ __('My Bookings') }}
                                </a>
                                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2 mr-2">
                                    {{ __('My Rent & Payments') }}
                                </a>
                                <a href="{{ route('maintenance.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2 mr-2">
                                    {{ __('My Incidents') }}
                                </a>
                                <a href="{{ route('tenant.profile') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mb-2">
                                    {{ __('Edit Profile') }}
                                </a>
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
                            
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'landlord')
                                <a href="{{ route('properties.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                    {{ __('Add a New Property') }}
                                </a>

                                <a href="{{ route('personnel.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
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
