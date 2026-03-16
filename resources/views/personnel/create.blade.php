<x-app-layout>
    <div class="max-w-4xl mx-auto pb-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">Staff Registration</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Register a new management user or facility staff member.</p>
            </div>
            <a href="{{ route('personnel.index') }}" class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('personnel.store') }}" class="p-8 lg:p-10 space-y-8">
                @csrf

                <!-- Basic Info Section -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Account Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Role & Assignment Section -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Job Assignment</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Assigned Role</label>
                            <select name="role" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                <option value="">Select a role</option>
                                @if(Auth::user()->role === 'super_admin')
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    <option value="landlord" {{ old('role') == 'landlord' ? 'selected' : '' }}>Landlord</option>
                                @endif
                                <option value="accountant" {{ old('role') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                                <option value="caretaker" {{ old('role') == 'caretaker' ? 'selected' : '' }}>Caretaker</option>
                                <option value="lodge_president" {{ old('role') == 'lodge_president' ? 'selected' : '' }}>Lodge President</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Property Assignment (Conditional)</label>
                            <select name="property_id" class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                <option value="">Global / All Access</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('property_id')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Security</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-indigo-600 dark:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-400 transition-all">
                        Register Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
