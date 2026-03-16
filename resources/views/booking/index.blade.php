<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Interview Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Booking History</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Track your interview status and schedules</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <form action="{{ route('bookings.index') }}" method="GET" class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, status or unit..." 
                                    class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all w-[240px] outline-none">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                @if(request('search'))
                                    <a href="{{ route('bookings.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-rose-500 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </a>
                                @endif
                            </form>
                            @if(Auth::user()->role === 'tenant')
                                <a href="{{ route('bookings.create_general') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 dark:bg-indigo-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Request Interview
                                </a>
                            @endif
                        </div>
                    </div>

                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2">Room</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Tenant</th>
                                @endif
                                <th class="pb-2">Date & Time</th>
                                <th class="pb-2">Format/Location</th>
                                <th class="pb-2">Status</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr class="border-b">
                                    <td class="py-3">
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-0.5">
                                            {{ $booking->room ? ($booking->room->property->name ?? 'Facility') : 'Unit Assignment Pending' }}
                                        </p>
                                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-tight">
                                            {{ $booking->room ? 'Room ' . $booking->room->room_number : 'General Request' }}
                                        </h3>
                                    </td>
                                    
                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">{{ $booking->user->name }}</td>
                                    @endif

                                    <td class="py-3">
                                        @if($booking->interview_date)
                                            {{ \Carbon\Carbon::parse($booking->interview_date)->format('M d, Y - h:i A') }}
                                        @else
                                            <span class="text-xs font-bold text-slate-400 italic">Awaiting Schedule</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-2">
                                            @if($booking->interview_type === 'online')
                                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-black uppercase text-blue-600 leading-none">Online</span>
                                                    @if($booking->interview_link)
                                                        <a href="{{ $booking->interview_link }}" class="text-[9px] text-slate-400 underline truncate max-w-[100px]" target="_blank">Meeting Link</a>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] font-black uppercase text-amber-600 leading-none">Offline</span>
                                                    @if($booking->interview_location)
                                                        <span class="text-[9px] text-slate-400 truncate max-w-[100px]">{{ $booking->interview_location }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $statusConfig = [
                                                'pending' => ['bg' => 'bg-amber-100 text-amber-700', 'dot' => 'bg-amber-500', 'label' => 'New Request'],
                                                'scheduled' => ['bg' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-500', 'label' => 'Interview Set'],
                                                'granted' => ['bg' => 'bg-indigo-100 text-indigo-700', 'dot' => 'bg-indigo-500', 'label' => 'Passed'],
                                                'confirmed' => ['bg' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Approved'],
                                                'rejected' => ['bg' => 'bg-rose-100 text-rose-700', 'dot' => 'bg-rose-500', 'label' => 'Rejected'],
                                            ];
                                            $config = $statusConfig[$booking->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full {{ $config['bg'] }} border border-current/10 shadow-sm transition-all hover:scale-105">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }} animate-pulse"></span>
                                            <span class="text-[11px] font-black uppercase tracking-wider">{{ $config['label'] }}</span>
                                        </div>
                                    </td>

                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">
                                            <div class="flex flex-col gap-2 min-w-[200px]">
                                                @if($booking->status === 'pending')
                                                    <!-- Step 1: Schedule -->
                                                    <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="flex flex-col gap-3 p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="scheduled">
                                                        <div class="flex flex-col gap-1">
                                                            <label for="date-{{ $booking->id }}" class="text-[9px] font-black uppercase text-indigo-500 tracking-widest leading-none">Step 1: Set Schedule</label>
                                                            <input id="date-{{ $booking->id }}" type="datetime-local" name="interview_date" required class="text-[10px] p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500/50">
                                                        </div>

                                                        @if($booking->interview_type === 'online')
                                                            <div class="flex flex-col gap-1">
                                                                <label for="link-{{ $booking->id }}" class="text-[9px] font-black uppercase text-blue-500 tracking-widest leading-none">Meeting Link (Online)</label>
                                                                <input id="link-{{ $booking->id }}" type="text" name="interview_link" placeholder="https://zoom.us/... or https://teams.microsoft.com/... or https://googlemeet.com/..." required class="text-[10px] p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-blue-500/50">
                                                            </div>
                                                        @else
                                                            <div class="flex flex-col gap-1">
                                                                <label for="loc-{{ $booking->id }}" class="text-[9px] font-black uppercase text-amber-500 tracking-widest leading-none">Physical Location (Offline)</label>
                                                                <input id="loc-{{ $booking->id }}" type="text" name="interview_location" value="{{ $booking->interview_location }}" placeholder="Property Address..." required class="text-[10px] p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-amber-500/50">
                                                            </div>
                                                        @endif

                                                        <button type="submit" style="background-color: #4f46e5 !important; color: white !important;" class="w-full py-4 text-white text-xs font-black uppercase rounded-2xl shadow-lg transition-all flex items-center justify-center gap-3 border-2 border-white/20">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z" /></svg>
                                                            <span>Set Schedule</span>
                                                        </button>
                                                    </form>

                                                @elseif($booking->status === 'scheduled')
                                                    <!-- Step 2: Grant -->
                                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-900/30">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                                            <span class="text-[9px] font-black uppercase text-blue-600 tracking-widest leading-none">Step 2: Conclusion</span>
                                                        </div>
                                                        <div class="text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-3 bg-white/50 dark:bg-slate-900/50 p-3 rounded-lg border border-blue-50 dark:border-blue-900/30 flex flex-col gap-1">
                                                            <p class="uppercase text-[8px] opacity-70">Scheduled For:</p>
                                                            <p>{{ \Carbon\Carbon::parse($booking->interview_date)->format('M d, Y @ H:i') }}</p>
                                                            <p class="mt-1 flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                                @if($booking->interview_type === 'online') <a href="{{ $booking->interview_link }}" target="_blank" class="text-blue-500 underline truncate">Join Link</a> @else {{ $booking->interview_location }} @endif
                                                            </p>
                                                        </div>
                                                        <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="granted">
                                                            <button type="submit" style="background-color: #2563eb !important; color: white !important;" class="w-full py-4 text-white text-xs font-black uppercase rounded-2xl shadow-lg transition-all flex items-center justify-center gap-3 border-2 border-white/20">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                                <span>Grant Interview</span>
                                                            </button>
                                                        </form>
                                                    </div>

                                                @elseif($booking->status === 'granted')
                                                    <!-- Step 3: Assign -->
                                                    <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="flex flex-col gap-2 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-100 dark:border-emerald-900/30">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="confirmed">
                                                        <div class="flex flex-col gap-1">
                                                            <span class="text-[9px] font-black uppercase text-emerald-600 tracking-widest leading-none">Step 3: Assign Unit</span>
                                                            <select id="room-{{ $booking->id }}" name="room_id" required class="text-[10px] p-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg outline-none focus:ring-2 focus:ring-emerald-500/50">
                                                                <option value="">Choose Room...</option>
                                                                @foreach($rooms as $room)
                                                                    <option value="{{ $room->id }}">Room {{ $room->room_number }} - {{ $room->property->name ?? 'N/A' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="submit" style="background-color: #10b981 !important; color: white !important;" class="w-full py-4 text-white text-xs font-black uppercase rounded-2xl shadow-lg shadow-emerald-200 dark:shadow-none transition-all flex items-center justify-center gap-3 border-2 border-white/20">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                            <span>Confirm & Finalize</span>
                                                        </button>
                                                    </form>

                                                @elseif($booking->status === 'confirmed')
                                                    <div class="flex items-center gap-2 p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                                                        <div class="w-6 h-6 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 rounded-full flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                                        </div>
                                                        <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest italic leading-none">Processed</span>
                                                    </div>
                                                @elseif($booking->status === 'rejected')
                                                    <div class="flex items-center gap-2 p-3 bg-rose-50 dark:bg-rose-900/10 rounded-xl border border-rose-100 dark:border-rose-900/20">
                                                        <span class="text-[10px] font-black uppercase text-rose-500 tracking-widest italic leading-none">Rejected</span>
                                                    </div>
                                                @endif

                                                @if($booking->status !== 'confirmed' && $booking->status !== 'rejected')
                                                    <form method="POST" action="{{ route('bookings.update', $booking->id) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="w-full py-1 text-rose-400 text-[9px] font-black uppercase hover:text-rose-600 transition-colors tracking-widest leading-none">Dismiss Request</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">No bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-8 border-t border-slate-50 pt-6">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
