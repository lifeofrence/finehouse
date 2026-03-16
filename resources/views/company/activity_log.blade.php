<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-12">
            <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight uppercase">Activity Stream</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-medium tracking-wide">Real-time audit log of system operations and modifications.</p>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-white/[0.02]">
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Timestamp</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Operation</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Subject</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Initiator</th>
                            <th class="px-8 py-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Properties</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800">
                        @forelse($activities as $activity)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/[0.01] transition-colors group">
                                <td class="px-8 py-6">
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $activity->created_at->format('M d, Y') }}</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $activity->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[9px] font-black uppercase tracking-widest rounded-lg">
                                        {{ $activity->description }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    @if($activity->subject)
                                        <p class="text-xs font-bold text-slate-900 dark:text-white">{{ class_basename($activity->subject_type) }}</p>
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">ID: #{{ $activity->subject_id }}</p>
                                    @else
                                        <span class="text-[9px] font-black text-slate-300 uppercase italic">System Entity</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    @if($activity->causer)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase">
                                                {{ substr($activity->causer->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $activity->causer->name }}</p>
                                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $activity->causer->role }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[9px] font-black text-slate-300 uppercase italic">Automated</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    @if($activity->properties->count() > 0)
                                        <button class="text-indigo-600 dark:text-indigo-400 text-[9px] font-black uppercase tracking-widest hover:underline whitespace-nowrap">View Changes</button>
                                    @else
                                        <span class="text-[9px] font-black text-slate-300 uppercase italic">No metadata</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs italic">The stream is quiet. No recent activities detected.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($activities->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 dark:bg-white/[0.02] border-t border-slate-50 dark:border-slate-800">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
