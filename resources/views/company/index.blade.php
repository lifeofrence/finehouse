<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Company Profile</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium tracking-wide">Manage your corporate identity and operational settings.</p>
            </div>
            @if(Auth::user()->role === 'super_admin')
                <a href="{{ route('companies.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Register New Company
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-8">
            @forelse($companies as $company)
                <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 p-8 md:p-12 shadow-sm relative overflow-hidden group">
                    <!-- Background Decor -->
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-3xl opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="relative flex flex-col md:flex-row gap-12 items-start">
                        <!-- Logo Section -->
                        <div class="flex-shrink-0">
                            <div class="w-32 h-32 md:w-48 md:h-48 rounded-[2rem] bg-slate-50 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 flex items-center justify-center overflow-hidden shadow-inner">
                                @if($company->logo)
                                    <img src="{{ asset('storage/' . $company->logo) }}" class="w-full h-full object-contain p-4" alt="{{ $company->name }}">
                                @else
                                    <div class="text-center p-4">
                                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No Logo</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Info Section -->
                        <div class="flex-grow space-y-8">
                            <div>
                                <h2 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">{{ $company->name }}</h2>
                                <div class="flex flex-wrap gap-3">
                                    <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-full">Primary Account</span>
                                    <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest rounded-full">Verified</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email Address</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $company->contact_email }}</p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Phone Number</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $company->phone ?: 'Not provided' }}</p>
                                </div>
                                <div class="space-y-1 lg:col-span-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Corporate Address</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $company->address }}</p>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-50 dark:border-slate-800 flex flex-wrap gap-4">
                                <a href="{{ route('companies.edit', $company) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Modify Identity
                                </a>
                                <a href="{{ route('activity-log.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    System Activity
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-slate-50 dark:bg-slate-900/50 rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-xs italic">No company profile found in your registry.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
