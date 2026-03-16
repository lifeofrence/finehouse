<x-app-layout>
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                @if($selectedProperty)
                    Units at {{ $selectedProperty->name }}
                @else
                    Global Inventory
                @endif
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage unit availability, pricing, and occupant details.</p>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <!-- Search & Filter Form -->
            <form action="{{ route('rooms.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search room # or desc..." 
                           class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl pl-10 pr-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm w-48 lg:w-64 transition-all">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                @if($properties->count() > 0)
                    <select name="property_id" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm min-w-[150px] cursor-pointer">
                        <option value="">All Properties</option>
                        @foreach($properties as $prop)
                            <option value="{{ $prop->id }}" {{ $propertyId == $prop->id ? 'selected' : '' }}>
                                {{ $prop->name }}
                            </option>
                        @endforeach
                    </select>
                @endif

                @if($propertyId || $search)
                    <a href="{{ route('rooms.index') }}" class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg hover:bg-slate-200 transition-colors shadow-sm" title="Clear All">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </form>

            @if(in_array(Auth::user()->role, ['super_admin', 'admin', 'landlord', 'caretaker']))
                <a href="{{ route('rooms.create', ['property_id' => $propertyId]) }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Unit
                </a>
            @endif
        </div>
    </div>

    @if($movingTenant)
        <div class="mb-8 p-6 bg-indigo-600 rounded-[2rem] text-white shadow-xl shadow-indigo-200 dark:shadow-none flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-lg">Relocating {{ $movingTenant->name }}</h3>
                    <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider">Select a new room to assign this tenant.</p>
                </div>
            </div>
            <a href="{{ route('rooms.index') }}" class="px-6 py-3 bg-white text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-50 transition-colors">
                Cancel Relocation
            </a>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($rooms as $room)
            <div class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col relative">
                <!-- Status Badge Overlay -->
                <div class="absolute top-5 right-5 z-10">
                    @if($room->is_available)
                        <span class="inline-flex px-3 py-1 bg-emerald-500/90 backdrop-blur-sm shadow-xl shadow-emerald-500/20 text-white text-[9px] font-black uppercase tracking-widest bg-indigo-600 rounded-full border border-emerald-400/50">Available</span>
                    @else
                        <span class="inline-flex px-3 py-1 bg-rose-500/90 backdrop-blur-sm shadow-xl shadow-rose-500/20 text-white text-[9px] font-black uppercase tracking-widest bg-red-600 rounded-full border border-rose-400/50">Occupied</span>
                    @endif
                </div>

                <!-- Thumbnail Container -->
                <div class="relative h-56 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 flex items-center justify-center overflow-hidden">
                    @if($room->images->count() > 0)
                        <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" alt="Room" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 to-transparent"></div>
                    @else
                        <div class="flex flex-col items-center gap-2 opacity-20">
                            <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Property Name Over Image -->
                    <div class="absolute bottom-4 left-6">
                        <p class="text-[10px] font-black text-white/90 uppercase tracking-[0.2em] leading-none drop-shadow-sm">{{ $room->property->name ?? 'Facility' }}</p>
                    </div>
                </div>

                <div class="p-8 flex-1 flex flex-col">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter leading-none mb-1">{{ $room->room_number }}</h3>
                            <div class="flex items-center gap-1.5">
                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 shadow-sm shadow-indigo-500/50"></div>
                                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $room->room_type ?? 'Unit' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Nightly Rate</p>
                            <p class="text-xl font-black text-slate-900 dark:text-white tracking-tight">₦{{ number_format($room->price) }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 flex-1">
                        @if(!$room->is_available)
                            @php $currentOccupant = $room->bookings->whereIn('status', ['confirmed', 'approved'])->first()->user ?? null; @endphp
                            @if($currentOccupant)
                                <div class="bg-indigo-50/50 dark:bg-indigo-500/5 p-4 rounded-2xl border border-indigo-100 dark:border-indigo-500/10 group/occupant transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-[10px] font-black uppercase ring-4 ring-indigo-50 dark:ring-indigo-500/10">
                                            {{ substr($currentOccupant->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest leading-none mb-1">Primary Occupant</p>
                                            <p class="text-[11px] font-bold text-slate-900 dark:text-gray-300 truncate">{{ $currentOccupant->name }}</p>
                                        </div>
                                    </div>
                                    @if($currentOccupant->tenantProfile && $currentOccupant->tenantProfile->rent_expiry_date)
                                        @php
                                            $expiry = \Carbon\Carbon::parse($currentOccupant->tenantProfile->rent_expiry_date);
                                            $daysLeft = (int) now()->startOfDay()->diffInDays($expiry->copy()->startOfDay(), false);
                                        @endphp
                                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-indigo-100/50 dark:border-indigo-500/10">
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Lease Expiry</p>
                                            <p class="text-[10px] font-black {{ $daysLeft < 30 ? 'text-rose-500 animate-pulse' : 'text-emerald-500' }}">
                                                {{ $daysLeft }} DAYS REMAINING
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif

                        @php
                            $taken = $room->bookings->whereIn('status', ['confirmed', 'approved'])->count();
                            $available = $room->capacity - $taken;
                            $percent = $room->capacity > 0 ? ($taken / $room->capacity) * 100 : 0;
                        @endphp

                        <div class="space-y-3 pt-2">
                             <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-widest">
                                    <span class="text-slate-400">Inventory Status</span>
                                    <span class="text-slate-900 dark:text-slate-300">{{ $taken }} / {{ $room->capacity }} BEDS</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden p-0.5">
                                    <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r {{ $percent > 80 ? 'from-rose-500 to-rose-600' : 'from-indigo-500 to-indigo-600' }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed italic line-clamp-2">"{{ $room->description }}"</p>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <a href="{{ route('rooms.show', $room->id . (isset($movingTenant) && $movingTenant ? '?tenant_id=' . $movingTenant->id : '')) }}" class="flex-1 px-6 py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] text-center hover:bg-indigo-700 shadow-lg shadow-indigo-100 dark:shadow-none hover:shadow-xl active:scale-[0.98] transition-all">
                            {{ isset($movingTenant) && $movingTenant ? 'Confirm Select' : 'Explore Unit' }}
                        </a>
                        @if(in_array(Auth::user()->role, ['super_admin', 'admin', 'landlord', 'caretaker']))
                            <a href="{{ route('rooms.edit', $room->id) }}" class="p-4 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl hover:text-indigo-600 hover:bg-white dark:hover:bg-slate-700 shadow-sm transition-all group/edit">
                                <svg class="w-5 h-5 transition-transform group-hover/edit:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-16 text-center shadow-sm">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center text-slate-300 mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1-4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">No Units Found</h3>
                <p class="text-slate-500 max-w-xs mx-auto text-sm mb-8">Try adjusting your search filters or add a new unit to the inventory.</p>
                <a href="{{ route('rooms.create', ['property_id' => $propertyId]) }}" class="inline-flex px-8 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all">
                    Register First Unit
                </a>
            </div>
        @endforelse
    </div>
</x-app-layout>
