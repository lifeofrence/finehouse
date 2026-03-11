<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add a New Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Property -->
                        <div>
                            <x-input-label for="property_id" :value="__('Select Property')" />
                            <select id="property_id" name="property_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                        </div>

                        <!-- Room Number -->
                        <div class="mt-4">
                            <x-input-label for="room_number" :value="__('Room Number/Name')" />
                            <x-text-input id="room_number" class="block mt-1 w-full" type="text" name="room_number" :value="old('room_number')" required />
                            <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Capacity -->
                            <div class="mt-4">
                                <x-input-label for="capacity" :value="__('Capacity (People)')" />
                                <x-text-input id="capacity" class="block mt-1 w-full" type="number" min="1" name="capacity" :value="old('capacity', 1)" required />
                                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                            </div>

                            <!-- Price -->
                            <div class="mt-4">
                                <x-input-label for="price" :value="__('Price per Term/Year')" />
                                <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price')" required />
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Room Description & Features')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" name="description" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Images -->
                        <div class="mt-4">
                            <x-input-label for="images" :value="__('Room Images')" />
                            <input id="images" class="block mt-1 w-full p-2 border border-gray-300 rounded-md" type="file" name="images[]" multiple accept="image/*" />
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">You can upload multiple images (JPEG, PNG, JPG, GIF max 2MB).</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Add Room') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
