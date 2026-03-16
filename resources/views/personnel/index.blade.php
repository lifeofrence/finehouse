<x-app-layout>
    <div class="mb-8 flex flex-col xl:flex-row justify-between xl:items-end gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Staff Management</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage accountants, caretakers, and lodge presidents assigned to facilities.</p>
        </div>

        <div class="flex flex-col md:flex-row items-end xl:items-center gap-4">
            <form action="{{ route('personnel.index') }}" method="GET" class="flex items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search name or email..."
                        class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl pl-10 pr-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm w-64 transition-all">
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <select name="property_id" onchange="this.form.submit()"
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm min-w-[180px]">
                    <option value="">All Facilities</option>
                    @foreach($properties as $property)
                    <option value="{{ $property->id }}" {{ $propertyId==$property->id ? 'selected' : '' }}>
                        {{ $property->name }}
                    </option>
                    @endforeach
                </select>

                @if($propertyId || $search)
                <a href="{{ route('personnel.index') }}"
                    class="p-2 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg hover:bg-slate-200 transition-colors shadow-sm"
                    title="Clear All">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
                @endif
            </form>

            <div class="h-8 w-px bg-slate-100 dark:bg-slate-800 hidden md:block"></div>

            <div class="flex items-center gap-2 shadow-lg shadow-indigo-100">
                <a href="{{ route('personnel.template') }}" style="background-color: #f1f5f9 !important; color: #475569 !important;"
                    class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all flex items-center gap-2 border border-slate-200"
                    title="Download CSV Template">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Template
                </a>

                <form action="{{ route('personnel.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center gap-2">
                    @csrf
                    <input type="file" name="csv_file" id="personnel_csv" class="hidden" onchange="this.form.submit()">
                    <label for="personnel_csv" style="background-color: #10b981 !important; color: white !important;"
                        class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-emerald-700 transition-all flex items-center gap-2 cursor-pointer border border-emerald-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Bulk Import
                    </label>
                </form>

                <a href="{{ route('personnel.create') }}" style="background-color: #4f46e5 !important; color: white !important;"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 transition-all flex items-center gap-2 border border-indigo-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New Staff
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-8 p-6 bg-rose-50 border border-rose-100 rounded-[2rem] shadow-sm animate-fade-in-up">
        <div class="flex items-center gap-3 mb-4 text-rose-600">
            <svg class="w-6 h-6 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="font-black text-sm uppercase tracking-widest">Import Notice / Issues</h3>
        </div>
        <ul class="space-y-2">
            @foreach ($errors->all() as $error)
            <li class="text-xs font-bold text-rose-500 flex items-center gap-2">
                <div class="w-1 h-1 rounded-full bg-rose-400"></div>
                {{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div
        class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 text-sm font-bold shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <div
        class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800 text-[10px] font-black uppercase tracking-widest text-slate-400">
                        <th class="px-8 py-5">Personnel Detail</th>
                        <th class="px-8 py-5">Role</th>
                        <th class="px-8 py-5">Assigned Facility</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($personnel as $p)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center font-black text-sm">
                                    {{ substr($p->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $p->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400">{{ $p->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span
                                class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-[9px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 rounded-lg">
                                {{ str_replace('_', ' ', $p->role) }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            @if($p->property)
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-700 dark:text-slate-300">{{
                                    $p->property->name }}</span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Main
                                    Facility</span>
                            </div>
                            @else
                            <span class="text-xs font-bold text-slate-400 italic">All Access</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('personnel.edit', $p) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all" title="Edit Profile">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('personnel.reset-password', $p) }}" method="POST"
                                    onsubmit="return confirm('Reset this personnel\'s password to \'password123\'?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 hover:bg-amber-600 hover:text-white transition-all" title="Reset Password">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </button>
                                </form>
                                <form action="{{ route('personnel.destroy', $p) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to remove this personnel?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 hover:bg-rose-600 hover:text-white transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-base font-black italic uppercase tracking-widest">Registry Empty</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Property ID Reference for Import -->
    <div
        class="mt-12 p-8 bg-slate-50 dark:bg-slate-800/20 rounded-[2.5rem] border border-slate-100 dark:border-slate-800">
        <div class="mb-6">
            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Facility ID Reference
            </h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1">Use these IDs in your CSV
                for "Property ID" column.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($properties as $property)
            <div
                class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <span class="text-xs font-black text-slate-700 dark:text-slate-300 truncate mr-4">{{ $property->name
                    }}</span>
                <span
                    class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-lg text-[10px] font-black tracking-widest">ID:
                    {{ $property->id }}</span>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>