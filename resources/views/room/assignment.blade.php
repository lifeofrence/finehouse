<x-app-layout>
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Room Assignment</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Directly manage unit occupancy and tenant allocations.</p>
        </div>

        <div class="flex items-center gap-4">
            <form action="{{ route('rooms.assignment') }}" method="GET" class="flex items-center gap-3">
                @if($properties->count() > 0)
                    <select name="property_id" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm min-w-[200px] cursor-pointer">
                        <option value="">All Properties</option>
                        @foreach($properties as $prop)
                            <option value="{{ $prop->id }}" {{ $propertyId == $prop->id ? 'selected' : '' }}>
                                {{ $prop->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-bold flex items-center gap-3 shadow-sm shadow-emerald-100 dark:shadow-none animate-fade-in-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/20 border-b border-slate-100 dark:border-slate-800">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Unit Number</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Property</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Occupancy Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Current Occupants</th>
                        <th class="px-8 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Quick Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                    @forelse($rooms as $room)
                        @php
                            $taken = $room->bookings->count();
                            $atCapacity = $taken >= $room->capacity;
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-sm font-black text-slate-900 dark:text-white group-hover:text-indigo-600 transition-colors uppercase">
                                    {{ $room->room_number }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    {{ $room->property->name ?? '---' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <div class="h-1.5 w-16 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        @php 
                                            $width = $room->capacity > 0 ? ($taken / $room->capacity) * 100 : 0;
                                            $barColor = $atCapacity ? 'bg-rose-500' : 'bg-emerald-500';
                                        @endphp
                                        <div class="h-full {{ $barColor }}" style="width:{{ $width }}%"></div>
                                    </div>
                                    <span class="text-[10px] font-black {{ $atCapacity ? 'text-rose-500' : 'text-emerald-500' }} uppercase tracking-widest">
                                        {{ $taken }}/{{ $room->capacity }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex -space-x-2 overflow-hidden">
                                    @foreach($room->bookings as $booking)
                                        <div class="inline-block h-8 w-8 rounded-full ring-2 ring-white dark:ring-slate-900 bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase truncate px-1 shadow-sm" title="{{ $booking->user->name }}">
                                            {{ substr($booking->user->name, 0, 1) }}
                                        </div>
                                    @endforeach
                                    @if($taken == 0)
                                        <span class="text-[10px] font-black text-slate-300 uppercase italic">Vacant</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$atCapacity)
                                        <a href="{{ route('rooms.assign', $room->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-indigo-100 dark:shadow-none">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Assign
                                        </a>
                                    @endif
                                    <a href="{{ route('rooms.show', $room->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                                        View Log
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-bold italic">
                                No units available for assignment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
