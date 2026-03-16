<x-app-layout>
    <div class="max-w-4xl mx-auto pb-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">Initialize Unit</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Register a new room or suite to your property
                    inventory.</p>
            </div>
            <a href="{{ route('rooms.index', ['property_id' => $preSelectedPropertyId]) }}"
                class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Inventory
            </a>
        </div>

        <div
            class="bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-white/5 rounded-3xl shadow-sm overflow-hidden text-slate-900 dark:text-slate-100">
            <form method="POST" action="{{ route('rooms.store') }}" enctype="multipart/form-data"
                class="p-8 lg:p-10 space-y-8">
                @csrf

                <!-- Assignment -->
                <div>
                    <h3
                        class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">
                        Facility Assignment</h3>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Target
                            Property</label>
                        <select name="property_id" required
                            class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                            @foreach($properties as $property)
                            <option value="{{ $property->id }}" {{ (old('property_id') ??
                                $preSelectedPropertyId)==$property->id ? 'selected' : '' }}>{{ $property->name }}
                            </option>
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
                            <input type="text" name="room_number" value="{{ old('room_number') }}" required autofocus
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all"
                                placeholder="e.g. Unit 402, Penthouse A">
                            <x-input-error :messages="$errors->get('room_number')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Price
                                per Year (₦)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all"
                                placeholder="0.00">
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Occupancy
                                Capacity (Bed)</label>
                            <input type="number" min="1" name="capacity" value="{{ old('capacity', 1) }}" required
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
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all resize-none"
                                placeholder="List key features like AC, Balcony, Furnished status...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Unit
                                Imagery (Optional)</label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-xl text-slate-500 text-xs font-bold file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-600 dark:file:bg-indigo-900/40 dark:file:text-indigo-400 hover:border-indigo-500 transition-all">
                            <p class="text-[10px] text-slate-400 mt-2 pl-1 italic">Multiple files up to 2MB each are
                                supported.</p>
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit"
                        class="px-10 py-4 bg-indigo-600 dark:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-400 transition-all">
                        Create Unit Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>