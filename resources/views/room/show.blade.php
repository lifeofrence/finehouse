<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Room Details: ') }} {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Image Gallery -->
                        <div class="w-full md:w-1/2">
                            @if($room->images->count() > 0)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" class="w-full h-auto rounded-lg shadow-md" alt="Main Room Image">
                                </div>
                                @if($room->images->count() > 1)
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($room->images->skip(1) as $image)
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-auto rounded shadow-sm" alt="Room thumbnail">
                                    @endforeach
                                </div>
                                @endif
                            @else
                                <div class="w-full h-64 bg-gray-200 flex items-center justify-center rounded-lg text-gray-500">
                                    No images available
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="w-full md:w-1/2">
                            <h3 class="text-3xl font-bold mb-2">{{ $room->room_number }}</h3>
                            <p class="text-gray-500 text-lg mb-4">{{ $room->property->name }}</p>

                            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                <div class="flex justify-between border-b pb-2 mb-2">
                                    <span class="text-gray-600">Price (Term/Year)</span>
                                    <span class="font-bold text-lg text-indigo-600">₦{{ number_format($room->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2 mb-2">
                                    <span class="text-gray-600">Capacity</span>
                                    <span class="font-bold">{{ $room->capacity }} Persons</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status</span>
                                    <span>
                                        @if($room->is_available)
                                            <span class="text-green-600 font-bold">● Available</span>
                                        @else
                                            <span class="text-red-600 border border-red-600 px-2 rounded font-bold">Occupied</span>
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <h4 class="font-bold text-lg mb-2">Description & Features</h4>
                            <p class="text-gray-700 whitespace-pre-line leading-relaxed mb-6">{{ $room->description }}</p>

                            @if($room->is_available && Auth::user()->role === 'tenant')
                                <a href="{{ route('bookings.create', $room->id) }}" class="block text-center w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded hover:bg-indigo-700 transition">
                                    Book Interview for this Room
                                </a>
                            @elseif(in_array(Auth::user()->role, ['super_admin', 'admin', 'landlord', 'caretaker']) && $room->is_available)
                                <a href="{{ route('rooms.assign', $room->id) }}{{ request()->has('tenant_id') ? '?tenant_id=' . request('tenant_id') : '' }}" class="block text-center w-full bg-green-600 text-white font-bold py-3 px-4 rounded hover:bg-green-700 transition">
                                    {{ request()->has('tenant_id') ? 'Confirm Relocation to this Room' : 'Assign Tenant to Room' }}
                                </a>
                            @endif

                            <!-- Current Occupants Section (Admin Only) -->
                            @if(in_array(Auth::user()->role, ['admin', 'landlord', 'caretaker']))
                                <div class="mt-8">
                                    <h4 class="font-bold text-lg mb-4">Current Occupants</h4>
                                    @if($room->bookings->count() > 0)
                                        <div class="space-y-4">
                                            @foreach($room->bookings as $booking)
                                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase">
                                                            {{ substr($booking->user->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-gray-900">{{ $booking->user->name }}</p>
                                                            <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <!-- Move Button -->
                                                        <a href="{{ route('rooms.index', ['move_tenant_id' => $booking->user_id]) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-transparent hover:border-indigo-100" title="Change Room (Move)">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                            </svg>
                                                        </a>
                                                        
                                                        <!-- Remove Button -->
                                                        <form action="{{ route('rooms.unassign', [$room->id, $booking->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this tenant from this room?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-transparent hover:border-rose-100" title="Remove Tenant">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic text-sm">No occupants currently assigned.</p>
                                    @endif
                                    
                                    @if(!$room->is_available)
                                        <div class="mt-4 p-3 bg-amber-50 border border-amber-100 rounded-lg text-amber-700 text-xs flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Room is at full capacity. Remove an occupant to add another.
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
