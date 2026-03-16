<x-app-layout>
    <div class="mb-10">
        <div class="flex items-center gap-4 mb-2">
            <a href="{{ route('rooms.assignment') }}" class="p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-slate-400 hover:text-indigo-600 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Manual Allocation</h1>
        </div>
        <p class="text-slate-500 dark:text-slate-400 ml-12">Assigning tenant to unit <span class="font-black text-indigo-600 dark:text-indigo-400">{{ $room->room_number }}</span> at {{ $room->property->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
                <form method="POST" action="{{ route('rooms.store_assign', $room->id) }}" class="space-y-8">
                    @csrf

                    <div class="space-y-4">
                        <label for="tenant_id" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Verified Tenant Selection</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <select id="tenant_id" name="tenant_id" 
                                    class="block w-full pl-14 pr-12 py-5 bg-slate-50 dark:bg-slate-800/20 border-2 border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800/40 rounded-[1.5rem] text-lg font-black text-slate-900 dark:text-white outline-none transition-all appearance-none cursor-pointer" required>
                                <option value="" disabled {{ !isset($preselectedTenantId) ? 'selected' : '' }}>-- Select a tenant from company registry --</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" {{ (isset($preselectedTenantId) && $preselectedTenantId == $tenant->id) ? 'selected' : '' }}>
                                        {{ $tenant->name }} ({{ $tenant->email }})
                                    </option>
                                @endforeach
                            </select>
                            <!-- <div class="absolute inset-y-0 right-0 pr-6 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                            </div> -->
                        </div>
                        <x-input-error :messages="$errors->get('tenant_id')" class="mt-2 ml-2" />
                        
                        @if($tenants->isEmpty())
                            <div class="p-6 bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-800 rounded-3xl flex items-start gap-4 animate-pulse">
                                <svg class="w-6 h-6 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <div>
                                    <p class="text-xs font-black text-rose-600 uppercase tracking-widest leading-none mb-1">Company Restriction</p>
                                    <p class="text-[11px] font-bold text-rose-500">No unassigned tenants found belonging to this company. Please ensure tenants have their organization ID set correctly.</p>
                                </div>
                            </div>
                        @else
                            <!-- <p class="text-[10px] font-bold text-slate-400 italic ml-2">Note: This list only displays users belonging to the same organization.</p> -->
                        @endif
                    </div>

                    <div class="pt-8 border-t border-slate-50 dark:border-slate-800">
                        <button type="submit" class="w-full py-5 bg-indigo-600 text-white text-[12px] font-black uppercase tracking-[0.3em] rounded-[1.5rem] shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95 disabled:opacity-50 disabled:translate-y-0" {{ $tenants->isEmpty() ? 'disabled' : '' }}>
                            Confirm Occupancy Allocation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Room Specs Sidebar -->
        <div class="space-y-6">
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-100 dark:shadow-none relative overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-125 transition-transform duration-700"></div>
                
                <h3 class="text-xs font-black uppercase tracking-[0.2em] opacity-60 mb-6">Unit Summary</h3>
                
                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-[10px] font-black uppercase opacity-40 mb-1">Room Reference</p>
                        <p class="text-2xl font-black uppercase tracking-tight">{{ $room->room_number }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-white/10 rounded-[1.5rem] backdrop-blur-md">
                            <p class="text-[9px] font-black uppercase opacity-60 mb-1">Capacity</p>
                            <p class="text-lg font-black">{{ $room->capacity }} Bed(s)</p>
                        </div>
                        <div class="p-4 bg-white/10 rounded-[1.5rem] backdrop-blur-md">
                            <p class="text-[9px] font-black uppercase opacity-60 mb-1">Current</p>
                            <p class="text-lg font-black">{{ $occupancyCount }} Space(s)</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-white/10">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase opacity-40">Status</span>
                            @if($occupancyCount < $room->capacity)
                                <span class="px-3 py-1 bg-emerald-400/20 text-emerald-300 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-400/30">Selectable</span>
                            @else
                                <span class="px-3 py-1 bg-rose-400/20 text-rose-300 text-[10px] font-black uppercase tracking-widest rounded-full border border-rose-400/30">At Capacity</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Security Notice</h3>
                <p class="text-[11px] font-bold text-slate-500 leading-relaxed italic">
                    Manual allocations bypass the standard booking interview flow. Use this for confirmed renewals or direct management overrides.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
