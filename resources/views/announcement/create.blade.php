<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Announcement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('announcements.store') }}">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="target_type" :value="__('Who is this announcement for?')" />
                            <div class="flex gap-4 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="target_type" value="property" checked onchange="toggleTarget(this.value)" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">Entire Property</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="target_type" value="individual" onchange="toggleTarget(this.value)" class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="text-sm font-bold text-gray-700 group-hover:text-indigo-600 transition-colors">Specific Individual</span>
                                </label>
                            </div>
                        </div>

                        <div id="property-group" class="mb-4">
                            <x-input-label for="property_id" :value="__('Target Property')" />
                            <select id="property_id" name="property_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled selected>Select a property...</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                        </div>

                        <div id="individual-group" class="mb-4 hidden">
                            <x-input-label for="target_user_id" :value="__('Target Individual')" />
                            <select id="target_user_id" name="target_user_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                                <option value="" disabled selected>Select a tenant...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('target_user_id')" class="mt-2" />
                        </div>

                        <script>
                            function toggleTarget(val) {
                                document.getElementById('property-group').classList.toggle('hidden', val !== 'property');
                                document.getElementById('individual-group').classList.toggle('hidden', val !== 'individual');
                                
                                // Manage required state potentially, though backend handles it
                                document.getElementById('property_id').required = (val === 'property');
                                document.getElementById('target_user_id').required = (val === 'individual');
                            }
                        </script>

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Announcement Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="message" :value="__('Message')" />
                            <textarea id="message" name="message" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" rows="5" required>{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4 bg-purple-600 hover:bg-purple-700">
                                {{ __('Publish Announcement') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
