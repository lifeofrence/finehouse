<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if($room)
                {{ __('Book Interview for Room: ') }} {{ $room->room_number }}
            @else
                {{ __('Request General Interview') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ $room ? route('bookings.store', $room->id) : route('bookings.store_general') }}">
                        @csrf

                        <!-- Scheduling Note -->
                        <div class="mb-8 p-6 bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800 rounded-[2rem] flex flex-col md:flex-row items-center gap-6">
                            <div class="w-12 h-12 bg-white dark:bg-slate-900 rounded-full flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-indigo-900 dark:text-indigo-400 uppercase tracking-widest">Scheduling Policy</h4>
                                <p class="text-xs font-bold text-indigo-700/60 dark:text-indigo-500/60 mt-1">
                                    You only need to select your preferred format. The <span class="text-indigo-900 dark:text-indigo-300">Property Administrator</span> will pick the best Date & Time for your session.
                                </p>
                            </div>
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
