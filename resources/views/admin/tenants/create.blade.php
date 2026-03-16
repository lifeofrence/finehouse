<x-app-layout>
    <!-- Page Breadcrumb -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Onboarding Hub</h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-sm text-gray-500 hover:text-indigo-600 transition-colors" href="{{ route('dashboard') }}">Dashboard /</a>
                </li>
                <li class="font-medium text-sm text-indigo-600">Onboard Resident</li>
            </ol>
        </nav>
    </div>

    <!-- Registration Hero (TailAdmin ComponentCard style container) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-xl">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Resident Onboarding</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Initialize new lease agreements by registering residents individually or importing them in bulk via CSV synchronization.</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl border border-indigo-100 dark:border-indigo-900/50 shrink-0">
                <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Active Registry Cycle</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 1. Individual Registration Form -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-500 border border-indigo-100 dark:border-indigo-900/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">Manual Registration</h4>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">Single Index Entry</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.tenants.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Legal Full Name</label>
                    <input type="text" name="name" placeholder="Resident name" required 
                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-indigo-300 focus:outline-hidden focus:ring-3 focus:ring-indigo-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-indigo-800 transition-all outline-none">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Official Email</label>
                    <input type="email" name="email" placeholder="example@finehouse.com" required 
                        class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-indigo-300 focus:outline-hidden focus:ring-3 focus:ring-indigo-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-indigo-800 transition-all outline-none">
                </div>

                <div class="pt-4">
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 active:scale-[0.98] transition-all">
                        Commit Registration
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Bulk Synchronization Hub -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6 flex flex-col">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-500 border border-purple-100 dark:border-purple-900/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">Bulk Synchronization</h4>
                    <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">CSV Import Module</p>
                </div>
            </div>

            <div class="space-y-6 flex-1 flex flex-col">
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
                    <p class="text-[11px] font-bold text-gray-500 dark:text-gray-400 leading-relaxed uppercase tracking-wider">
                        Use the standard Finehouse protocol. New: You can now include 'Property ID' to assign residents during import.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <a href="{{ route('admin.tenants.template_simple') }}" class="flex flex-col items-center justify-center gap-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-6 text-center hover:border-indigo-500 hover:shadow-lg transition-all shadow-theme-xs group">
                            <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white">Simple Template</span>
                        </a>
                        <p class="text-[9px] text-center text-gray-400 font-medium leading-relaxed uppercase tracking-widest px-2">Essential: Name, Email, & Phone only.</p>
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('admin.tenants.template') }}" class="flex flex-col items-center justify-center gap-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-6 text-center hover:border-purple-500 hover:shadow-lg transition-all shadow-theme-xs group">
                            <div class="w-10 h-10 rounded-full bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-500 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke_width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-900 dark:text-white">Full Dataset</span>
                        </a>
                        <p class="text-[9px] text-center text-gray-400 font-medium leading-relaxed uppercase tracking-widest px-2">Comprehensive: Academic info & Next of Kin.</p>
                    </div>
                </div>

                <!-- Property IDs Reference -->
                <div class="mt-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                    <h5 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Property ID Reference</h5>
                    <div class="max-h-32 overflow-y-auto space-y-1 pr-2">
                        @foreach($properties as $property)
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="text-gray-600 dark:text-gray-400 font-medium">{{ $property->name }}</span>
                                <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded font-bold text-indigo-600">{{ $property->id }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-auto border-t border-gray-100 dark:border-gray-800 pt-6">
                    <form action="{{ route('admin.tenants.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="relative group cursor-pointer">
                            <input type="file" name="csv_file" class="hidden" id="csv_import" onchange="this.form.submit()" accept=".csv">
                            <label for="csv_import" class="flex flex-col items-center justify-center py-8 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/20 hover:border-indigo-500 dark:hover:border-indigo-400 transition-all cursor-pointer group">
                                <div class="w-10 h-10 bg-white dark:bg-gray-900 rounded-full flex items-center justify-center text-gray-400 group-hover:text-indigo-500 shadow-sm transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                                <span class="mt-3 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] group-hover:text-indigo-600 transition-all">Upload Synchronization CSV</span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
