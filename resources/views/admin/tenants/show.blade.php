<x-app-layout>
    <!-- Page Breadcrumb (TailAdmin Pattern) -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Resident Profile</h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-sm text-gray-500 hover:text-indigo-600 transition-colors" href="{{ route('dashboard') }}">Dashboard /</a>
                </li>
                <li class="font-medium text-sm text-indigo-600">Profile</li>
            </ol>
        </nav>
    </div>

    <!-- Main Profile Container (TailAdmin sequential card style) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6 space-y-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Profile Specifications</h3>

        <!-- 1. Profile Summary Card (Matches ProfileCard.vue) -->
        <div class="p-5 border border-gray-200 rounded-2xl bg-white dark:bg-gray-900 dark:border-gray-800 lg:p-6">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                <div class="flex flex-col items-center w-full gap-6 xl:flex-row">
                    <!-- Identity Image -->
                    <div class="w-20 h-20 overflow-hidden border border-gray-200 rounded-full dark:border-gray-800 shrink-0">
                        @if($tenant->tenantProfile && $tenant->tenantProfile->passport)
                            <img src="{{ asset('storage/' . $tenant->tenantProfile->passport) }}" alt="{{ $tenant->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-500 font-bold text-xl uppercase">
                                {{ substr($tenant->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Basic Specifications -->
                    <div class="order-3 xl:order-2 text-center xl:text-left">
                        <h4 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">
                            {{ $tenant->name }}
                        </h4>
                        <div class="flex flex-col items-center gap-1 xl:flex-row xl:gap-3">
                            <p class="text-sm text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $tenant->role) }}</p>
                            <div class="hidden h-3.5 w-px bg-gray-300 dark:bg-gray-700 xl:block"></div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $tenant->tenantProfile->state_of_origin ?? 'International' }}, Nigeria
                            </p>
                        </div>
                    </div>

                    <!-- Ref Badge -->
                    <div class="flex items-center order-2 gap-2 grow xl:order-3 xl:justify-end">
                        <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-bold uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/50">
                            #FH-{{ str_pad($tenant->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>
                
                <div class="flex flex-col gap-3 sm:flex-row xl:items-center border-t border-gray-100 dark:border-gray-800 pt-5 xl:border-none xl:pt-0">
                    <button onclick="document.getElementById('edit-lease-modal').classList.toggle('hidden')" class="flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-all">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="fill-current"><path d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z" fill=""></path></svg>
                        Adjust Lease
                    </button>
                    {{-- 
                    <button onclick="document.getElementById('wallet-modal').classList.toggle('hidden')" class="flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] transition-all">
                        Financial Ops
                    </button>
                    --}}
                </div>
            </div>
        </div>

        <!-- 2. Personal Information (Matches PersonalInfoCard.vue) -->
        <div class="p-5 border border-gray-200 rounded-2xl bg-white dark:bg-gray-900 dark:border-gray-800 lg:p-6">
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5 lg:mb-7">Personal Information</h4>
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                <div>
                    <p class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email Registry</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90 leading-tight">{{ $tenant->email }}</p>
                </div>
                <div>
                    <p class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Direct Contact</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90 leading-tight">{{ $tenant->tenantProfile->phone_number ?? '---' }}</p>
                </div>
                <div>
                    <p class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Birth Profile</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90 leading-tight">{{ $tenant->tenantProfile && $tenant->tenantProfile->dob ? \Carbon\Carbon::parse($tenant->tenantProfile->dob)->format('d F, Y') : '---' }}</p>
                </div>
                <div>
                    <p class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gender Specification</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90 leading-tight capitalize">{{ $tenant->tenantProfile->gender ?? '---' }}</p>
                </div>
                <div class="lg:col-span-2">
                    <p class="mb-2 block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">LGA / Home State</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white/90 leading-tight capitalize">{{ $tenant->tenantProfile->lga ?? 'N/A' }}, {{ $tenant->tenantProfile->state_of_origin ?? 'N/A' }} State</p>
                </div>
            </div>
        </div>

        <!-- 3. Academic & Financial Specifications (Customized TailAdmin Style) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Academic Card -->
            @if($tenant->tenantProfile && $tenant->tenantProfile->type === 'student')
            <div class="p-5 border border-gray-200 rounded-2xl bg-white dark:bg-gray-900 dark:border-gray-800 lg:p-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5 flex items-center gap-2">
                    <span class="text-indigo-500">🎓</span> Academic Profile
                </h4>
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <p class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Institutional Hub</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $tenant->tenantProfile->university }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 dark:border-gray-800 pt-4">
                        <div>
                            <p class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Matric Index</p>
                            <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400 font-mono">{{ $tenant->tenantProfile->matric_number }}</p>
                        </div>
                        <div>
                            <p class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Registry Year</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white/90">{{ $tenant->tenantProfile->level }} Level</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Residency Info Card (Matches AddressCard.vue structure) -->
            <div class="p-5 border border-gray-200 rounded-2xl bg-white dark:bg-gray-900 dark:border-gray-800 lg:p-6 grow">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-5">Residency Specifications</h4>
                <div class="grid grid-cols-1 gap-5">
                    @php $booking = $tenant->bookings->where('status', 'confirmed')->first(); @endphp
                    @if($booking)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/30 rounded-xl border border-gray-100 dark:border-gray-800">
                            <div class="w-12 h-12 bg-white dark:bg-gray-900 rounded-xl flex items-center justify-center text-indigo-500 shadow-sm border border-gray-100 dark:border-gray-700 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div class="grow">
                                <h5 class="text-sm font-bold text-gray-900 dark:text-white capitalize">{{ $booking->room->property->name }}</h5>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-black tracking-widest mt-0.5">Room #{{ $booking->room->room_number }} • Standard Unit</p>
                            </div>
                        </div>
                        {{-- 
                        <div class="grid grid-cols-2 gap-4 border-t border-gray-100 dark:border-gray-800 pt-4">
                            <div>
                                <p class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Total Credit</p>
                                <p class="text-base font-black text-gray-900 dark:text-white tracking-widest leading-none">₦{{ number_format($tenant->tenantProfile->wallet_balance ?? 0, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Cycle Status</p>
                                @if($tenant->tenantProfile->rent_expiry_date)
                                    @php $isExpired = \Carbon\Carbon::parse($tenant->tenantProfile->rent_expiry_date)->isPast(); @endphp
                                    <p class="text-xs font-bold uppercase {{ $isExpired ? 'text-error-600' : 'text-success-600' }}">{{ $isExpired ? 'Expired' : 'Active Registry' }}</p>
                                @else
                                    <p class="text-xs font-bold text-gray-400 uppercase italic">Not Set</p>
                                @endif
                            </div>
                        </div>
                        --}}
                    @else
                        <div class="py-10 text-center bg-gray-50 dark:bg-gray-800/20 rounded-xl border border-dashed border-gray-200 dark:border-gray-800">
                            <p class="text-xs font-bold italic text-gray-300 uppercase tracking-widest">No Active Residency Found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Manual Implementation for TailAdmin accuracy) -->
    <!-- Edit Lease Modal -->
    <div id="edit-lease-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="w-full max-w-[500px] rounded-3xl bg-white p-8 dark:bg-gray-900 animate-fade-in-up">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-xl font-bold text-gray-800 dark:text-white">Adjust Contractual Timeline</h4>
                <button onclick="document.getElementById('edit-lease-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.tenants.update-dates', $tenant) }}" method="POST" class="space-y-5">
                @csrf @method('PATCH')
                <div class="space-y-1.5">
                    <label for="rent_commencement_date" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rent Commencement</label>
                    <input type="date" name="rent_commencement_date" id="rent_commencement_date" value="{{ $tenant->tenantProfile->rent_commencement_date ?? '' }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-indigo-500 dark:focus:border-indigo-600 rounded-xl text-sm outline-none transition-all dark:text-white">
                </div>
                <div class="space-y-1.5">
                    <label for="rent_expiry_date" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Rent Expiry</label>
                    <input type="date" name="rent_expiry_date" id="rent_expiry_date" value="{{ $tenant->tenantProfile->rent_expiry_date ?? '' }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-800 border border-transparent focus:border-indigo-500 dark:focus:border-indigo-600 rounded-xl text-sm outline-none transition-all dark:text-white">
                </div>
                <div class="pt-4 flex gap-4">
                    <button type="button" onclick="document.getElementById('edit-lease-modal').classList.add('hidden')" class="flex-1 px-6 py-3 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg hover:bg-indigo-700 transition-all">Save Timeline</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Wallet Modal Commented Out 
    <div id="wallet-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        ...
    </div>
    --}}
</x-app-layout>
