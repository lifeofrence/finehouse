<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Financial Center') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase rounded-full border border-emerald-200">Live Billing</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-100">
                <div class="p-8">
                    
                    {{-- Header Section --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Payments & Ledger</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Track collections, verifications and revenue</p>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-4">
                            {{-- Search --}}
                            <form action="{{ route('payments.index') }}" method="GET" class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference, name or status..." 
                                    class="pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all w-[280px] outline-none">
                                <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </form>

                            @if(Auth::user()->role === 'tenant')
                                <a href="{{ route('payments.create') }}" style="background-color: #4f46e5 !important; color: white !important;" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                    Execute Payment
                                </a>
                            @else
                                <a href="{{ route('payments.manual') }}" style="background-color: #10b981 !important; color: white !important;" class="inline-flex items-center px-6 py-3 bg-emerald-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                                    Log Manual Payment
                                </a>
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->role === 'tenant')
                        {{-- Tenant Rent Stats --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-3xl p-6 text-white shadow-xl shadow-indigo-100">
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-80 mb-4">Rent Validity</p>
                                @if(Auth::user()->tenantProfile && Auth::user()->tenantProfile->rent_commencement_date)
                                    <div class="flex items-end justify-between">
                                        <div>
                                            <p class="text-xs opacity-70 mb-1">Expires On</p>
                                            <p class="text-xl font-black">{{ \Carbon\Carbon::parse(Auth::user()->tenantProfile->rent_expiry_date)->format('M d, Y') }}</p>
                                        </div>
                                        <div class="h-10 w-10 bg-white/20 rounded-2xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z" /></svg>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm italic opacity-70 leading-relaxed font-bold">No active rent tracking recorded yet.</p>
                                @endif
                            </div>

                            <div class="bg-white border-2 border-slate-50 rounded-3xl p-6 flex flex-col justify-between">
                                <!-- <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Wallet Balance</p> -->
                                <div class="flex items-center justify-between">
                                    <!-- <p class="text-3xl font-black text-slate-800 tracking-tighter">₦{{ number_format(Auth::user()->tenantProfile->wallet_balance ?? 0, 2) }}</p> -->
                                    <!-- <div class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-lg">CREDIT</div> -->
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Table Section --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-50">
                                    <th class="pb-4 pr-4">Transaction</th>
                                    @if(Auth::user()->role !== 'tenant')
                                        <th class="pb-4 px-4">Entity</th>
                                    @endif
                                    <th class="pb-4 px-4">Allocation</th>
                                    <th class="pb-4 px-4 text-right">Value</th>
                                    <th class="pb-4 px-4 text-center">Status</th>
                                    @if(Auth::user()->role !== 'tenant')
                                        <th class="pb-4 pl-4 text-right">Verification</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($payments as $payment)
                                    <tr class="group hover:bg-slate-50/50 transition-colors">
                                        <td class="py-5 pr-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-800">{{ $payment->created_at->format('M d, Y') }}</span>
                                                <span class="text-[10px] text-slate-400 font-bold font-mono">#{{ $payment->reference ?: 'MANUAL-ENTRY' }}</span>
                                            </div>
                                        </td>
                                        
                                        @if(Auth::user()->role !== 'tenant')
                                            <td class="py-5 px-4 font-black text-xs text-slate-700 uppercase tracking-tight">
                                                {{ $payment->user->name }}
                                            </td>
                                        @endif

                                        <td class="py-5 px-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-slate-700 uppercase">Unit {{ $payment->room->room_number ?? '?' }}</span>
                                                <span class="text-[10px] text-slate-400 uppercase tracking-tighter font-black">{{ $payment->property->name }}</span>
                                            </div>
                                        </td>

                                        <td class="py-5 px-4 text-right">
                                            <span class="text-sm font-black text-indigo-600 tracking-tighter">₦{{ number_format($payment->amount, 2) }}</span>
                                        </td>

                                        <td class="py-5 px-4">
                                            <div class="flex justify-center">
                                                @php
                                                    $statusStyles = [
                                                        'verified' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                                        'failed' => 'bg-rose-100 text-rose-700 border-rose-200'
                                                    ];
                                                    $style = $statusStyles[$payment->status] ?? $statusStyles['pending'];
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $style }}">
                                                    {{ $payment->status }}
                                                </span>
                                            </div>
                                        </td>
                                        
                                        @if(Auth::user()->role !== 'tenant')
                                            <td class="py-5 pl-4 text-right">
                                                @if($payment->status === 'pending')
                                                    @if($payment->is_external)
                                                        {{-- Manual Proof Verification --}}
                                                        <div class="flex flex-col items-end gap-2">
                                                            @if($payment->external_proof_path)
                                                                <a href="{{ asset('storage/' . $payment->external_proof_path) }}" target="_blank" class="text-[9px] font-black uppercase text-indigo-500 hover:text-indigo-700 flex items-center gap-1 group/link">
                                                                    Analyze Proof
                                                                    <svg class="w-3 h-3 transition-transform group-hover/link:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                                                </a>
                                                            @endif
                                                            <form method="POST" action="{{ route('payments.verify', $payment->id) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white text-[9px] font-black uppercase rounded-lg shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all border-b-2 border-indigo-800 active:border-b-0 active:translate-y-0.5">
                                                                    Verify Now
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @else
                                                        {{-- Online Payment Awaiting Callback --}}
                                                        <div class="flex items-center justify-end gap-2 text-slate-300">
                                                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                            <span class="text-[9px] font-black uppercase tracking-widest italic">Gateway Auto-Verification</span>
                                                        </div>
                                                    @endif
                                                @elseif($payment->verified_by)
                                                    <div class="flex flex-col items-end">
                                                        <span class="text-[10px] font-black text-slate-400 uppercase leading-none">Verified By</span>
                                                        <span class="text-xs font-bold text-slate-600 tracking-tight">{{ $payment->verifier->name ?? 'System' }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-[10px] font-black text-emerald-500 uppercase italic">Finalized</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center">
                                            <div class="flex flex-col items-center gap-2">
                                                <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                                </div>
                                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">No transactions logged</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12 pt-8 border-t border-slate-50">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
