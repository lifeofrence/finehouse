<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Book Interview for Room: ') }} {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('bookings.store', $room->id) }}">
                        @csrf

                        <!-- Interview Date -->
                        <div class="mb-4">
                            <x-input-label for="interview_date" :value="__('Preferred Interview Date & Time')" />
                            <x-text-input id="interview_date" class="block mt-1 w-full" type="datetime-local" name="interview_date" :value="old('interview_date')" required />
                            <x-input-error :messages="$errors->get('interview_date')" class="mt-2" />
                        </div>

                        <!-- Interview Type -->
                        <div class="mb-4">
                            <x-input-label for="interview_type" :value="__('Interview Format')" />
                            <select id="interview_type" name="interview_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required onchange="toggleLocationField()">
                                <option value="online">Online (Meeting Link)</option>
                                <option value="offline">Offline (In-Person)</option>
                            </select>
                            <x-input-error :messages="$errors->get('interview_type')" class="mt-2" />
                        </div>

                        <!-- Offline Location (Optional/Contextual) -->
                        <div id="location-field" class="mb-4" style="display: none;">
                            <x-input-label for="interview_location" :value="__('Preferred Location (If Offline)')" />
                            <x-text-input id="interview_location" class="block mt-1 w-full" type="text" name="interview_location" :value="old('interview_location')" placeholder="e.g. Property Address or Nearby Cafe" />
                            <x-input-error :messages="$errors->get('interview_location')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Submit Booking Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLocationField() {
            var type = document.getElementById('interview_type').value;
            var locationField = document.getElementById('location-field');
            if (type === 'offline') {
                locationField.style.display = 'block';
            } else {
                locationField.style.display = 'none';
            }
        }
    </script>
</x-app-layout>
