<x-app-layout>
    <div class="max-w-4xl mx-auto pb-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">Refine Unit</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Updating details for {{ $room->room_number }} at {{
                    $room->property->name }}.</p>
            </div>
            <a href="{{ route('rooms.index', ['property_id' => $room->property_id]) }}"
                class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Inventory
            </a>
        </div>

        <div class="space-y-8">
            <!-- Main Details Form -->
            <div
                class="bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-white/5 rounded-3xl shadow-sm overflow-hidden text-slate-900 dark:text-slate-100">
                <form method="POST" action="{{ route('rooms.update', $room->id) }}" enctype="multipart/form-data"
                    class="p-8 lg:p-10 space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Assignment -->
                    <div>
                        <h3
                            class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">
                            Facility Assignment</h3>
                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Target
                                Property</label>
                            <select name="property_id" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                @foreach($properties as $property)
                                <option value="{{ $property->id }}" {{ old('property_id', $room->property_id) ==
                                    $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Unit Details -->
                    <div>
                        <h3
                            class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">
                            Unit Identification</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Room
                                    Number / Name</label>
                                <input type="text" name="room_number"
                                    value="{{ old('room_number', $room->room_number) }}" required
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                                <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Price
                                    per Year (₦)</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price', $room->price) }}"
                                    required
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Occupancy
                                    Capacity (Bed)</label>
                                <input type="number" min="1" name="capacity"
                                    value="{{ old('capacity', $room->capacity) }}" required
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Media and Description -->
                    <div>
                        <h3
                            class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">
                            Operational Data</h3>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Features
                                    & Description</label>
                                <textarea name="description" rows="4"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all resize-none">{{ old('description', $room->description) }}</textarea>
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Add
                                    More Imagery</label>
                                <input type="file" name="images[]" multiple accept="image/*"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-xl text-slate-500 text-xs font-bold file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-900/40 dark:file:text-indigo-400 hover:border-indigo-500 transition-all">
                                <p class="text-[10px] text-slate-400 mt-2 pl-1 italic">New uploads will be appended to
                                    the current gallery.</p>
                                <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="submit"
                            class="px-10 py-4 bg-indigo-600 dark:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-400 transition-all">
                            Synchronize Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Existing Images Gallery -->
            <div
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
                <h3
                    class="text-sm font-black text-slate-400 uppercase tracking-widest mb-8 pb-2 border-b border-slate-50 dark:border-slate-800">
                    Current Unit Gallery</h3>

                @if($room->images->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($room->images as $image)
                    <div
                        class="group relative rounded-2xl overflow-hidden aspect-square border border-slate-100 dark:border-slate-800 shadow-sm">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                        <div
                            class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <form action="{{ route('rooms.delete-image', $image->id) }}" method="POST"
                                onsubmit="return confirm('Remove this image permanently?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="p-3 bg-white text-rose-600 rounded-full hover:bg-rose-600 hover:text-white transition-all shadow-xl">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div
                    class="py-12 text-center bg-slate-50 dark:bg-slate-800/30 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Media Library Empty</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>