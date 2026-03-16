<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Resident Registry</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Management of all synchronized resident profiles and leases.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.tenants.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Bulk Onboard
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="p-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm">
            <form action="{{ route('admin.tenants.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all dark:text-white">
                </div>
                <select name="property" class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition-all dark:text-white">
                    <option value="">All Properties</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}" {{ request('property') == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2.5 bg-gray-800 dark:bg-gray-700 text-white rounded-xl text-sm font-semibold hover:bg-gray-900 transition-all">Filter</button>
            </form>
        </div>

        <!-- Registry Table (TailAdmin Style) -->
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-25 dark:bg-gray-800/20">
                            <th class="px-5 py-4 text-left sm:px-6">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Resident Profile</p>
                            </th>
                            <th class="px-5 py-4 text-left sm:px-6">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Allocation / Unit</p>
                            </th>
                            <th class="px-5 py-4 text-left sm:px-6">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Rent Cycle</p>
                            </th>
                            <th class="px-5 py-4 text-left sm:px-6">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Days Remaining</p>
                            </th>
                            <th class="px-5 py-4 text-right sm:px-6">
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Actions</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($tenants as $tenant)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors group">
                                <td class="px-5 py-5 sm:px-6">
                                    <div class="flex items-center gap-4">
                                        <div style="width: 40px; height: 40px; min-width: 40px; min-height: 40px;" class="flex-none overflow-hidden rounded-full ring-2 ring-gray-100 dark:ring-gray-800">
                                            @if($tenant->tenantProfile && $tenant->tenantProfile->passport)
                                                <img src="{{ asset('storage/' . $tenant->tenantProfile->passport) }}" alt="" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-indigo-50 text-xs font-bold uppercase text-indigo-500 dark:bg-indigo-900/20">
                                                    {{ substr($tenant->name, 0, 1) ?? 'U' }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.tenants.show', $tenant) }}" class="block font-bold text-gray-900 dark:text-white/90 hover:text-indigo-600 transition-colors uppercase leading-tight">{{ $tenant->name }}</a>
                                            <span class="block text-[10px] text-gray-400 font-medium tracking-tight mt-1">{{ $tenant->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 sm:px-6">
                                    @php $booking = $tenant->bookings->where('status', 'confirmed')->first(); @endphp
                                    @if($booking)
                                        <div class="space-y-1">
                                            <p class="text-sm font-bold text-gray-800 dark:text-gray-300 leading-none capitalize">{{ $booking->room->property->name }}</p>
                                            <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider">Unit {{ $booking->room->room_number }}</p>
                                        </div>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-5 py-5 sm:px-6">
                                    <div class="space-y-1">
                                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-300">
                                            {{ $tenant->tenantProfile && $tenant->tenantProfile->rent_commencement_date ? \Carbon\Carbon::parse($tenant->tenantProfile->rent_commencement_date)->format('M d, Y') : '---' }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">To 
                                            {{ $tenant->tenantProfile && $tenant->tenantProfile->rent_expiry_date ? \Carbon\Carbon::parse($tenant->tenantProfile->rent_expiry_date)->format('M d, Y') : '---' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-5 py-5 sm:px-6">
                                    @if($tenant->tenantProfile && $tenant->tenantProfile->rent_expiry_date)
                                        @php
                                            $expiry = \Carbon\Carbon::parse($tenant->tenantProfile->rent_expiry_date);
                                            $diff = now()->copy()->startOfDay()->diffInDays($expiry->copy()->startOfDay(), false);
                                            $isExpired = $diff < 0;
                                        @endphp
                                        <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $isExpired ? 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                                            {{ $isExpired ? 'Expired ' . abs($diff) . ' days ago' : $diff . ' Days Left' }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-gray-300 italic uppercase tracking-wider">Not Set</span>
                                    @endif
                                </td>
                                <td class="px-5 py-5 sm:px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" title="View Profile">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="p-2 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors" title="Edit Profile">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </a>
                                        <form action="{{ route('admin.tenants.reset-password', $tenant) }}" method="POST" onsubmit="return confirm('Reset this user\'s password to \'password123\'?')" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors" title="Reset Password">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.tenants.destroy', $tenant) }}" method="POST" onsubmit="return confirm('Archive this resident profile?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-400 italic font-medium uppercase text-xs tracking-widest">
                                    No records matching your search criteria were found in the registry.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($tenants->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 bg-gray-25 dark:bg-gray-800/20">
                    {{ $tenants->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
