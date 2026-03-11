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
                                    <span class="font-bold text-lg text-indigo-600">${{ number_format($room->price, 2) }}</span>
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
                            @elseif(in_array(Auth::user()->role, ['admin', 'landlord', 'caretaker']) && $room->is_available)
                                <a href="{{ route('rooms.assign', $room->id) }}" class="block text-center w-full bg-green-600 text-white font-bold py-3 px-4 rounded hover:bg-green-700 transition">
                                    Assign Tenant to Room
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
