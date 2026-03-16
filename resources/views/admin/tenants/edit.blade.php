<x-app-layout>
    <div class="max-w-5xl mx-auto pb-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white">Resident Modification</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Refining profile data for {{ $tenant->name }}.</p>
            </div>
            <a href="{{ route('admin.tenants.index') }}" class="text-sm font-bold text-slate-400 hover:text-indigo-600 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Registry
            </a>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] shadow-sm overflow-hidden">
            <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}" class="p-8 lg:p-12 space-y-12">
                @csrf
                @method('PUT')

                <!-- Primary Identity Section -->
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 pb-3 border-b border-slate-50 dark:border-slate-800 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                        Primary Identity
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="tenant_name" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Legal Name</label>
                            <input type="text" id="tenant_name" name="name" value="{{ old('name', $tenant->name) }}" required placeholder="e.g. John Doe"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label for="tenant_email" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Email Address</label>
                            <input type="email" id="tenant_email" name="email" value="{{ old('email', $tenant->email) }}" required placeholder="john@example.com"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Bio-Data Section -->
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 pb-3 border-b border-slate-50 dark:border-slate-800 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Bio-Data Details
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label for="phone_number" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $tenant->tenantProfile->phone_number ?? '') }}" placeholder="+234..."
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="gender" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Gender</label>
                            <select id="gender" name="gender" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                <option value="">Select Gender</option>
                                <option value="male" {{ (old('gender', $tenant->tenantProfile->gender ?? '') == 'male') ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ (old('gender', $tenant->tenantProfile->gender ?? '') == 'female') ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="dob" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="{{ old('dob', $tenant->tenantProfile->dob ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="religion" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Religion</label>
                            <input type="text" id="religion" name="religion" value="{{ old('religion', $tenant->tenantProfile->religion ?? '') }}" placeholder="Optional"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="state_of_origin" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">State of Origin</label>
                            <input type="text" id="state_of_origin" name="state_of_origin" value="{{ old('state_of_origin', $tenant->tenantProfile->state_of_origin ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="lga" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">LGA</label>
                            <input type="text" id="lga" name="lga" value="{{ old('lga', $tenant->tenantProfile->lga ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                    </div>
                </div>

                <!-- Occupation Specifics Section -->
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 pb-3 border-b border-slate-50 dark:border-slate-800 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Occupation Specifics
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="type" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Resident Type</label>
                            <select id="type" name="type" required class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                <option value="student" {{ (old('type', $tenant->tenantProfile->type ?? '') == 'student') ? 'selected' : '' }}>Student</option>
                                <option value="individual" {{ (old('type', $tenant->tenantProfile->type ?? '') == 'individual') ? 'selected' : '' }}>Individual / Pro</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-8">
                        <div class="space-y-2">
                            <label for="university" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">University</label>
                            <input type="text" id="university" name="university" value="{{ old('university', $tenant->tenantProfile->university ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="faculty" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Faculty</label>
                            <input type="text" id="faculty" name="faculty" value="{{ old('faculty', $tenant->tenantProfile->faculty ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="department" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Department</label>
                            <input type="text" id="department" name="department" value="{{ old('department', $tenant->tenantProfile->department ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="matric_number" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Matric Number</label>
                            <input type="text" id="matric_number" name="matric_number" value="{{ old('matric_number', $tenant->tenantProfile->matric_number ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="level" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Level</label>
                            <input type="text" id="level" name="level" value="{{ old('level', $tenant->tenantProfile->level ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label for="course" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Course</label>
                            <input type="text" id="course" name="course" value="{{ old('course', $tenant->tenantProfile->course ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                    </div>
                </div>

                <!-- Contact & Next of Kin -->
                <div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 pb-3 border-b border-slate-50 dark:border-slate-800 flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Emergency & Address
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2 md:col-span-2">
                            <label for="address" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Resident Address</label>
                            <textarea id="address" name="address" rows="3" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">{{ old('address', $tenant->tenantProfile->address ?? '') }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label for="next_of_kin" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Next of Kin Name</label>
                            <input type="text" id="next_of_kin" name="next_of_kin" value="{{ old('next_of_kin', $tenant->tenantProfile->next_of_kin ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>

                        <div class="space-y-2">
                            <label for="next_of_kin_phone" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Next of Kin Phone</label>
                            <input type="text" id="next_of_kin_phone" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', $tenant->tenantProfile->next_of_kin_phone ?? '') }}"
                                   class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-50 dark:border-slate-800 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white rounded-[1.25rem] text-xs font-black uppercase tracking-widest shadow-xl shadow-indigo-100 dark:shadow-none hover:bg-slate-900 transition-all flex items-center gap-3 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Commit Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
