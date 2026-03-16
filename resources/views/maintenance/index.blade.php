<x-app-layout>
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Maintenance Log</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Track and manage facility incidents and repair requests.</p>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <form action="{{ route('maintenance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <!-- Search -->
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search issues..." 
                           class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl pl-10 pr-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm w-48 transition-all">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm min-w-[120px] cursor-pointer">
                    <option value="">All Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ $status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>

                <!-- Sort -->
                <select name="sort" onchange="this.form.submit()" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm min-w-[120px] cursor-pointer">
                    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Latest First</option>
                    <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="pending" {{ $sort == 'pending' ? 'selected' : '' }}>Priority (Pending)</option>
                </select>

                @if($search || $status || $sort !== 'latest')
                    <a href="{{ route('maintenance.index') }}" class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg hover:bg-slate-200 transition-colors shadow-sm" title="Clear All">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </form>

            @if(Auth::user()->role === 'tenant')
                <a href="{{ route('maintenance.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Report Incident
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-bold flex items-center gap-3 animate-fade-in-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                        <th class="px-8 py-5">Reported</th>
                        @if(Auth::user()->role !== 'tenant')
                            <th class="px-8 py-5">Tenant</th>
                        @endif
                        <th class="px-8 py-5">Property / Unit</th>
                        <th class="px-8 py-5">Description</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-center">Media</th>
                        <th class="px-8 py-5">Maintenance Cost</th>
                        @if(Auth::user()->role !== 'tenant')
                            <th class="px-8 py-5 text-right">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($requests as $req)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $req->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">{{ $req->created_at->format('h:i A') }}</div>
                            </td>
                            
                            @if(Auth::user()->role !== 'tenant')
                                <td class="px-8 py-6">
                                    <div class="font-black text-slate-900 dark:text-white">{{ $req->user->name }}</div>
                                    <div class="text-[10px] text-indigo-600 font-black uppercase tracking-widest mt-0.5">Tenant</div>
                                </td>
                            @endif

                            <td class="px-8 py-6">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $req->property->name }}</div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Unit {{ $req->room->room_number ?? 'N/A' }}</div>
                            </td>

                            <td class="px-8 py-6">
                                <p class="text-xs text-slate-600 dark:text-slate-400 max-w-xs line-clamp-2 leading-relaxed" title="{{ $req->issue_description }}">
                                    {{ $req->issue_description }}
                                </p>
                            </td>
                            
                            <td class="px-8 py-6">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        'in_progress' => 'bg-amber-50 text-amber-600 border-amber-100',
                                        'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    ];
                                    $currentClass = $statusClasses[$req->status] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                                @endphp
                                <span class="px-3 py-1 rounded-lg border {{ $currentClass }} text-[10px] font-black uppercase tracking-widest">
                                    {{ str_replace('_', ' ', $req->status) }}
                                </span>
                            </td>

                            <td class="px-8 py-6 text-center">
                                @if($req->image_path)
                                    <a href="{{ asset('storage/' . $req->image_path) }}" target="_blank" class="inline-flex p-2 bg-slate-100 dark:bg-slate-800 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">None</span>
                                @endif
                            </td>
                            
                            <td class="px-8 py-6">
                                <span class="text-xs font-black {{ $req->cost ? 'text-slate-900 dark:text-white' : 'text-slate-300' }}">
                                    {{ $req->cost ? '₦' . number_format($req->cost, 2) : '---' }}
                                </span>
                            </td>
                            
                            @if(Auth::user()->role !== 'tenant')
                                <td class="px-8 py-6 text-right">
                                    <button 
                                        data-id="{{ $req->id }}" 
                                        data-status="{{ $req->status }}" 
                                        data-cost="{{ $req->cost ?? 0 }}"
                                        onclick="openUpdateModal(this)"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 rounded-xl hover:bg-indigo-600 hover:text-white transition-all transform active:scale-95 shadow-sm"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Update
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center text-slate-400 font-bold italic">
                                No incident logs found in the system.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="px-8 py-6 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                {{ $requests->links() }}
            </div>
        @endif
    </div>

    <!-- Update Modal -->
    <div id="update-modal" onclick="closeUpdateModal(event)" class="fixed inset-0 z-[999] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 transition-all duration-300">
        <div id="modal-content" class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-800 transform scale-90 opacity-0 transition-all duration-300">
            <div class="px-8 py-6 border-b border-slate-50 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/10">
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Log Maintenance Fix</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Update status and record expense</p>
                </div>
                <button onclick="closeUpdateModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="update-form" method="POST" action="" class="p-10 space-y-8">
                @csrf
                @method('PATCH')
                
                <div class="space-y-4">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Progress Status</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Pending -->
                        <button type="button" id="btn-status-pending" onclick="selectStatus('pending')"
                                class="status-btn relative flex flex-col items-center justify-center gap-2 py-6 px-4 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300 group">
                            <input type="radio" name="status" value="pending" id="status-pending" class="hidden" required>
                            <div class="status-icon p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0" /></svg>
                            </div>
                            <span class="status-text text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-600 transition-colors">Pending</span>
                        </button>

                        <!-- In Progress -->
                        <button type="button" id="btn-status-in_progress" onclick="selectStatus('in_progress')"
                                class="status-btn relative flex flex-col items-center justify-center gap-2 py-6 px-4 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300 group">
                            <input type="radio" name="status" value="in_progress" id="status-in_progress" class="hidden" required>
                            <div class="status-icon p-2 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <span class="status-text text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-600 transition-colors">In Progress</span>
                        </button>

                        <!-- Resolved -->
                        <button type="button" id="btn-status-resolved" onclick="selectStatus('resolved')"
                                class="status-btn relative flex flex-col items-center justify-center gap-2 py-6 px-4 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300 group">
                            <input type="radio" name="status" value="resolved" id="status-resolved" class="hidden" required>
                            <div class="status-icon p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <span class="status-text text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-600 transition-colors">Resolved</span>
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <label for="modal-cost" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-2">Total Expense (₦)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <span class="text-xl font-black text-slate-300 group-focus-within:text-indigo-500 transition-colors">₦</span>
                        </div>
                        <input type="number" name="cost" id="modal-cost" step="0.01" min="0" 
                               class="block w-full pl-14 pr-6 py-5 bg-slate-50 dark:bg-slate-800/20 border-2 border-transparent focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-800/40 rounded-[1.5rem] text-xl font-black text-slate-900 dark:text-white outline-none transition-all shadow-inner" placeholder="0.00">
                    </div>
                    <p class="text-[9px] font-bold text-slate-400 italic ml-2">Verified maintenance cost from facility inventory.</p>
                </div>

                <div class="pt-6 grid grid-cols-2 gap-4">
                    <button type="button" onclick="closeUpdateModal()" class="w-full py-5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                        Discard
                    </button>
                    <button type="submit" class="w-full py-5 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 hover:-translate-y-1 transition-all active:scale-95">
                        Log Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectStatus(status) {
            // Define active themes
            const themes = {
                'pending': { bg: 'bg-rose-600', border: 'border-rose-600', ring: 'ring-rose-200', text: 'text-white' },
                'in_progress': { bg: 'bg-amber-500', border: 'border-amber-500', ring: 'ring-amber-100', text: 'text-white' },
                'resolved': { bg: 'bg-emerald-600', border: 'border-emerald-600', ring: 'ring-emerald-100', text: 'text-white' }
            };

            // Reset all buttons
            document.querySelectorAll('.status-btn').forEach(btn => {
                btn.className = "status-btn relative flex flex-col items-center justify-center gap-2 py-6 px-4 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all duration-300 group";
                const icon = btn.querySelector('.status-icon');
                const text = btn.querySelector('.status-text');
                
                // Reset icon colors based on status
                const btnStatus = btn.id.replace('btn-status-', '');
                if (btnStatus === 'pending') {
                    icon.className = "status-icon p-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 transition-colors";
                } else if (btnStatus === 'in_progress') {
                    icon.className = "status-icon p-2 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 transition-colors";
                } else if (btnStatus === 'resolved') {
                    icon.className = "status-icon p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 transition-colors";
                }
                text.className = "status-text text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-600 transition-colors";
            });

            // Apply active theme
            const activeBtn = document.getElementById(`btn-status-${status}`);
            const activeIcon = activeBtn.querySelector('.status-icon');
            const activeText = activeBtn.querySelector('.status-text');
            const radio = document.getElementById(`status-${status}`);
            const theme = themes[status];

            activeBtn.classList.remove('bg-white', 'dark:bg-slate-900', 'border-slate-100', 'dark:border-slate-800');
            activeBtn.classList.add(theme.bg, theme.border, 'ring-4', theme.ring, 'shadow-xl', 'scale-[1.02]');
            
            activeIcon.className = "status-icon p-2 rounded-xl bg-white/20 text-white transition-colors";
            activeText.className = `status-text text-[10px] font-black uppercase tracking-widest ${theme.text} transition-colors`;
            
            radio.checked = true;
        }

        function openUpdateModal(btn) {
            const id = btn.dataset.id;
            const status = btn.dataset.status;
            const cost = btn.dataset.cost;

            const modal = document.getElementById('update-modal');
            const content = document.getElementById('modal-content');
            const form = document.getElementById('update-form');
            const costInput = document.getElementById('modal-cost');
            
            form.action = `/maintenance/${id}`;
            selectStatus(status);
            
            costInput.value = (cost && cost !== '0') ? cost : '';
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0', 'translate-y-4');
                content.classList.add('scale-100', 'opacity-100', 'translate-y-0');
            }, 50);
        }

        function closeUpdateModal(event = null) {
            const modal = document.getElementById('update-modal');
            const content = document.getElementById('modal-content');
            
            if (event && event.target !== modal && event.currentTarget === modal) return;

            content.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
            content.classList.add('scale-95', 'opacity-0', 'translate-y-4');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
    </script>
</x-app-layout>
