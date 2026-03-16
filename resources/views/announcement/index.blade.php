<x-app-layout>
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Bulletin Board</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Stay updated with the latest property news and targeted alerts.</p>
        </div>

        @if(in_array(Auth::user()->role, ['admin', 'landlord', 'lodge_president', 'caretaker']))
            <a href="{{ route('announcements.create') }}" class="px-6 py-3 bg-purple-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-purple-100 dark:shadow-none hover:bg-purple-700 transition-all flex items-center gap-2 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Publish News
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-bold flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto space-y-6">
        @forelse($announcements as $announcement)
            <div class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="flex items-start gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-400 group-hover:bg-purple-50 dark:group-hover:bg-purple-900/20 group-hover:text-purple-600 transition-all duration-500 shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-4">
                            <div>
                                <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight leading-none mb-1 group-hover:text-purple-600 transition-colors">{{ $announcement->title }}</h4>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-[9px] font-black px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 rounded uppercase tracking-widest border border-slate-200 dark:border-slate-700">
                                        @if($announcement->property_id)
                                            Property: {{ $announcement->property->name }}
                                        @elseif($announcement->target_user_id)
                                            Targeted: Personal Alert
                                        @else
                                            Global Update
                                        @endif
                                    </span>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">• Posted {{ $announcement->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/50 px-3 py-1.5 rounded-full border border-slate-100 dark:border-slate-800">
                                <div class="w-6 h-6 rounded-full bg-purple-600 flex items-center justify-center text-[9px] font-black text-white uppercase">
                                    {{ substr($announcement->user->name, 0, 1) }}
                                </div>
                                <span class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-widest truncate max-w-[100px]">{{ $announcement->user->name }}</span>
                            </div>
                        </div>
                        
                        <div class="text-sm font-medium text-slate-600 dark:text-slate-400 leading-relaxed whitespace-pre-wrap border-l-2 border-slate-100 dark:border-slate-800 pl-6 py-1">
                            {{ $announcement->message }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[3rem] p-16 text-center shadow-sm">
                <div class="w-24 h-24 bg-purple-50 dark:bg-purple-900/20 rounded-full flex items-center justify-center text-purple-600 mx-auto mb-8 shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11l-8.45 8.45a3.5 3.5 0 11-4.95-4.95l1.405-1.405m0 0l-5.657-5.657m5.657 5.657l-1.405 1.405M19 11l-1.405 1.405m0 0l-2.122-2.122m3.527-3.527L19 11z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">Quiet on the front</h3>
                <p class="text-slate-500 max-w-sm mx-auto text-sm font-medium">There are no new announcements at the moment. We'll alert you as soon as something important lands.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
