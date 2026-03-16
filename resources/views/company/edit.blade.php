<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-12 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Modify Identity</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium tracking-wide">Updating corporate details for {{ $company->name }}.</p>
            </div>
            <a href="{{ route('companies.index') }}" class="text-xs font-black text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Registry
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data" class="p-8 md:p-12 space-y-12">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Identity Section -->
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <label for="company_name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Brand Name</label>
                            <input type="text" id="company_name" name="name" value="{{ old('name', $company->name) }}" required
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2">
                            <label for="company_logo" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Current Logo</label>
                            <div class="flex items-center gap-6">
                                <div class="w-24 h-24 rounded-2xl bg-slate-50 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" class="w-full h-full object-contain p-2" alt="{{ $company->name }}">
                                    @else
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <label for="company_logo" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase text-slate-600 dark:text-slate-400 rounded-lg hover:bg-slate-200 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Drop New Image
                                        <input type="file" id="company_logo" name="logo" class="hidden" accept="image/*">
                                    </label>
                                    <p class="text-[8px] font-bold text-slate-400 mt-2 uppercase">JPEG, PNG or SVG. Max 2MB.</p>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('logo')" />
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <label for="contact_email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $company->contact_email) }}" required
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('contact_email')" />
                        </div>

                        <div class="space-y-2">
                            <label for="phone" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Line</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone) }}"
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('phone')" />
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="space-y-2">
                    <label for="address" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Corporate Headquarters</label>
                    <textarea id="address" name="address" rows="3" required
                              class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">{{ old('address', $company->address) }}</textarea>
                    <x-input-error :messages="$errors->get('address')" />
                </div>

                <div class="pt-8 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all hover:scale-[1.02]">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
