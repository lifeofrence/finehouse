<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Account Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Profile Information Section -->
            <div class="p-8 bg-white dark:bg-[#0f0f0f] border border-gray-100 dark:border-gray-800 shadow-2xl sm:rounded-2xl overflow-hidden">
                <div class="max-w-xl">
                    @if(Auth::user()->role === 'tenant')
                        <section>
                            <header class="mb-6">
                                <h2 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                                    {{ __('Profile Information') }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Your account's basic identification details.") }}
                                </p>
                            </header>

                            <div class="space-y-6">
                                <div class="p-4 bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30 rounded-xl">
                                    <p class="text-sm text-indigo-800 dark:text-indigo-300 font-medium">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ __('To update your name or email, please contact your landlord or caretaker.') }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Full Name') }}</label>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">{{ __('Email Address') }}</label>
                                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @else
                        @include('profile.partials.update-profile-information-form')
                    @endif
                </div>
            </div>

            <!-- Password Update Section -->
            <div class="p-8 bg-white dark:bg-[#0f0f0f] border border-gray-100 dark:border-gray-800 shadow-2xl sm:rounded-2xl overflow-hidden">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account Section (Hidden for Tenants) -->
            @if(Auth::user()->role !== 'tenant')
                <div class="p-8 bg-white dark:bg-[#0f0f0f] border border-gray-100 dark:border-gray-800 shadow-2xl sm:rounded-2xl overflow-hidden">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @else
                {{-- 
                <div class="p-8 bg-red-50/30 dark:bg-red-900/10 border border-red-100 dark:border-red-900/20 shadow-sm sm:rounded-2xl overflow-hidden">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-red-900 dark:text-red-300">{{ __('Account Management') }}</h3>
                            <p class="text-sm text-red-700 dark:text-red-400/80">{{ __('If you wish to terminate your tenancy and delete your account, please contact the property management office.') }}</p>
                        </div>
                    </div>
                </div>
                --}}
            @endif
        </div>
    </div>
</x-app-layout>
