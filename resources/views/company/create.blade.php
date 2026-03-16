<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-12">
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Corporate Onboarding</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium tracking-wide">Register your property management firm and establish your brand.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data" class="p-8 md:p-12 space-y-12">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Identity Section -->
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <label for="company_name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Company Name</label>
                            <input type="text" id="company_name" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="space-y-2">
                            <label for="company_logo" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Company Brand Logo</label>
                            <label for="company_logo" class="w-full cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-2xl py-8 px-4 bg-slate-50 dark:bg-slate-800/30 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                                <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest pt-2">Upload Profile Image</span>
                                <input type="file" id="company_logo" name="logo" class="hidden" accept="image/*">
                            </label>
                            <x-input-error :messages="$errors->get('logo')" />
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="space-y-8">
                        <div class="space-y-2">
                            <label for="contact_email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Operational Email</label>
                            <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}" required
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('contact_email')" />
                        </div>

                        <div class="space-y-2">
                            <label for="phone" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Business Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('phone')" />
                        </div>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="space-y-2">
                    <label for="address" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Corporate Office Address</label>
                    <textarea id="address" name="address" rows="3" required
                              class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">{{ old('address') }}</textarea>
                    <x-input-error :messages="$errors->get('address')" />
                </div>

                <div class="pt-8 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all">
                        Onboard Company
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
