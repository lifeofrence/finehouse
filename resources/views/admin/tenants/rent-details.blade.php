<x-app-layout>
    <!-- Page Breadcrumb (TailAdmin Pattern) -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Financial Ledger</h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-sm text-gray-500 hover:text-indigo-600 transition-colors" href="{{ route('dashboard') }}">Dashboard /</a>
                </li>
                <li>
                    <a class="font-medium text-sm text-gray-500 hover:text-indigo-600 transition-colors" href="{{ route('admin.tenants.show', $tenant) }}">Registry /</a>
                </li>
                <li class="font-medium text-sm text-indigo-600">Rent Ledger</li>
            </ol>
        </nav>
    </div>

    <!-- Header Summary Section (TailAdmin High-Level Cards) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Annual Rate -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-theme-xs">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Annual Rate</p>
            <h4 class="text-2xl font-bold text-gray-800 dark:text-white">₦{{ number_format($annualRent, 2) }}</h4>
            <div class="mt-2 flex items-center gap-1">
                <span class="text-[10px] font-medium text-gray-400 capitalize">Subscription per year</span>
            </div>
        </div>

        <!-- Total Lease Value -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-theme-xs">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Total Lease value</p>
            <h4 class="text-2xl font-bold text-gray-800 dark:text-white">₦{{ number_format($totalLeaseValue, 2) }}</h4>
            <div class="mt-2 text-[10px] font-medium text-indigo-500 uppercase tracking-widest">
                {{ $leaseMonths }} Months Duration
            </div>
        </div>

        <!-- Verified Collections -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-theme-xs border-l-4 border-l-success-500">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Verified Collections</p>
            <h4 class="text-2xl font-bold text-success-600 dark:text-success-400">₦{{ number_format($totalPaid, 2) }}</h4>
            <div class="mt-2 flex items-center gap-1">
                <svg class="w-3 h-3 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                <span class="text-[10px] font-bold text-success-500 uppercase tracking-tighter">Fully Verified</span>
            </div>
        </div>

        <!-- Outstanding Ledger -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-theme-xs border-l-4 {{ $balance <= 0 ? 'border-l-indigo-500' : 'border-l-error-500' }}">
            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Outstanding Ledger</p>
            <h4 class="text-2xl font-bold {{ $balance <= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-error-600 dark:text-error-400' }}">
                ₦{{ number_format($balance, 2) }}
            </h4>
            <div class="mt-2 text-[10px] font-medium text-gray-400 uppercase tracking-widest">
                {{ $balance <= 0 ? 'Account Settled' : 'Balance Due' }}
            </div>
        </div>
    </div>

    {{-- Wallet feature commented out manually below --}}
    {{-- 
    <!-- Wallet Management Module (Commented Out) -->
    <div class="mb-6 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-6 dark:border-indigo-900/50 dark:bg-indigo-500/5">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h4 class="text-lg font-bold text-indigo-900 dark:text-indigo-400 tracking-tight">Financial Wallet</h4>
                <p class="text-[11px] font-bold text-indigo-500/70 uppercase tracking-widest">Current Active Credit: ₦{{ number_format($walletBalance, 2) }}</p>
            </div>
            <form action="{{ route('admin.tenants.update-wallet', $tenant) }}" method="POST" class="flex gap-2">
                @csrf @method('PATCH')
                <input type="number" name="wallet_balance" value="{{ $walletBalance }}" step="0.01" class="h-11 w-32 rounded-lg border border-indigo-200 bg-white px-4 text-sm font-bold text-gray-800 outline-none focus:ring-2 focus:ring-indigo-500">
                <button type="submit" class="h-11 rounded-lg bg-indigo-600 px-6 text-[10px] font-black uppercase tracking-widest text-white shadow-lg shadow-indigo-200/50 hover:bg-indigo-700 transition-all">Update</button>
            </form>
        </div>
    </div>
    --}}

    <!-- Detailed Transaction Registry -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden shadow-theme-sm">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white/90 uppercase tracking-tighter">Verified Payment Registry</h3>
            <span class="px-3 py-1 bg-gray-50 dark:bg-gray-800 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 rounded-lg">Official Ledger</span>
        </div>
        
        <div class="max-w-full overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-25 dark:bg-gray-800/20 border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-4 text-left">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Timestamp & Ref</p>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Verification Agent</p>
                        </th>
                        <th class="px-6 py-4 text-left">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Payment Protocol</p>
                        </th>
                        <th class="px-6 py-4 text-right">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Credit amount</p>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($tenant->payments->where('status', 'verified') as $payment)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-5">
                                <span class="block font-bold text-gray-900 dark:text-white text-sm">{{ $payment->created_at->format('M d, Y') }}</span>
                                <span class="block text-[10px] font-medium text-gray-400 tracking-tighter mt-0.5 uppercase">TX-{{ str_pad($payment->id, 8, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-[10px] font-black text-gray-500 uppercase">
                                        {{ substr($payment->verifier->name ?? 'SYS', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-700 dark:text-gray-300">{{ $payment->verifier->name ?? 'System Ledger' }}</span>
                                        <span class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Authenticated</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex px-3 py-1 rounded-full bg-gray-50 dark:bg-gray-800 text-[9px] font-black uppercase text-gray-500 border border-gray-100 dark:border-gray-700">
                                    {{ $payment->payment_method ?? 'Direct Deposit' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right font-black text-success-600 dark:text-success-400 text-base">
                                + ₦{{ number_format($payment->amount, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <p class="text-xs font-bold text-gray-300 uppercase italic tracking-widest">No verified financial movements recorded</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-900 text-white">
                    <tr>
                        <td colspan="3" class="px-6 py-6 text-xs font-black uppercase tracking-[0.3em] text-right opacity-60">Total Cumulative Credit:</td>
                        <td class="px-6 py-6 text-right text-2xl font-black italic">₦{{ number_format($totalPaid, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-app-layout>
