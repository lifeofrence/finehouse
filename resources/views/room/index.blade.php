<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
            {{ __('Rooms Listing') }}
            @if(in_array(Auth::user()->role, ['admin', 'landlord', 'caretaker']))
                <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Add Room</a>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($rooms as $room)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        @if($room->images->count() > 0)
                            <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" alt="Room Image" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-400">No Image</div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-lg font-bold">Room: {{ $room->room_number }}</h3>
                            <p class="text-gray-600 mb-2">{{ $room->property->name ?? 'Unknown Property' }}</p>
                            
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-indigo-600 font-bold pr-2 border-r border-gray-200">${{ number_format($room->price, 2) }}</span>
                                <span class="text-sm text-gray-500 pl-2">Capacity: {{ $room->capacity }} people</span>
                            </div>

                            <p class="text-xs text-gray-500 mb-4 line-clamp-2">
                                {{ $room->description }}
                            </p>

                            <div class="flex items-center justify-between">
                                @if($room->is_available)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Available</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Occupied</span>
                                @endif

                                <a href="{{ route('rooms.show', $room->id) }}" class="text-sm text-indigo-600 hover:text-indigo-900 border border-indigo-600 px-3 py-1 rounded">View Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white p-6 shadow-sm rounded-lg text-center text-gray-500">
                        No rooms found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
