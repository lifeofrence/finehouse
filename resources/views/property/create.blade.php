<x-app-layout>
    <div class="max-w-4xl mx-auto pb-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">Register Property</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-2">
                    @if(Auth::user()->role === 'super_admin')
                        Super Admin > Global Portfolio Deployment
                    @else
                        Adding to <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 rounded-md font-black text-[10px] uppercase tracking-widest">{{ Auth::user()->company->name ?? 'Finehouse' }}</span>
                    @endif
                </p>
            </div>
            <a href="{{ route('properties.index') }}" class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden text-[#1b1b18] dark:text-[#EDEDEC]">
            <form method="POST" action="{{ route('properties.store') }}" class="p-8 lg:p-10 space-y-8">
                @csrf

                <!-- Property Details -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Identity & Location</h3>
                    <div class="grid grid-cols-1 gap-6">
                        @if(Auth::user()->role === 'super_admin')
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Target Company</label>
                            <select name="company_id" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                <option value="">Select a company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                        </div>
                        @endif

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Property Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all"
                                   placeholder="e.g. Luxury Heights Apartments">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Physical Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all"
                                   placeholder="Full street address, city, state">
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Descriptive Info -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Operational Summary</h3>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Brief Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all resize-none"
                                  placeholder="Mention number of floors, key amenities, or facility types...">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-indigo-600 dark:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-400 transition-all">
                        Initialize Property
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
