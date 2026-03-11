<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report Maintenance Issue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-4 text-red-600 border-b pb-2">New Incident Form</h3>
                    
                    <div class="mb-4 bg-gray-50 p-4 rounded text-gray-600">
                        Reporting format for: <strong>{{ $room->property->name }} - Room {{ $room->room_number }}</strong>
                    </div>

                    <form method="POST" action="{{ route('maintenance.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <div class="mb-4">
                            <x-input-label for="issue_description" :value="__('Describe the Issue/Incident')" />
                            <textarea id="issue_description" name="issue_description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('issue_description') }}</textarea>
                            <x-input-error :messages="$errors->get('issue_description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="image" :value="__('Upload Evidence (Photo)')" />
                            <input id="image" class="block mt-1 w-full border border-gray-300 rounded-md p-2" type="file" name="image" accept="image/*" />
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Please provide a clear image if possible (Accepts JPG, PNG. Max size 2MB).</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4 bg-red-600 hover:bg-red-700">
                                {{ __('Submit Incident Report') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
