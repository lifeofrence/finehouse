<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Log Manual Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-xl font-bold mb-4 w-full border-b pb-2">Record External Payment</h3>

                    <form method="POST" action="{{ route('payments.storeManual') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="room_id" :value="__('Select Tenant/Room')" />
                            <select id="room_id" name="room_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Select an occupied room...</option>
                                @foreach($rooms as $room)
                                    @php
                                        // Retrieve the tenant name associated with the approved booking
                                        $tenant = optional($room->bookings->where('status', 'approved')->first())->user;
                                    @endphp
                                    @if($tenant)
                                        <option value="{{ $room->id }}">
                                            {{ $room->property->name }} - Rm {{ $room->room_number }} ({{ $tenant->name }}) - Rate: ${{ $room->price }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                        </div>
                        
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Amount Paid')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4 bg-green-600 hover:bg-green-700">
                                {{ __('Record Payment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
