<x-app-layout>
    <div class="max-w-4xl mx-auto pb-12">
        <div class="mb-8 block">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">Identity Configuration</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Complete your profile to finalize room access.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-800 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <p class="text-[10px] font-black uppercase text-rose-600 tracking-widest">Incomplete Information</p>
                    <p class="text-xs font-bold text-rose-800/60 dark:text-rose-400/60 leading-none mt-0.5">Please check the fields marked in red below.</p>
                </div>
            </div>
        @endif

        <div class="bg-white dark:bg-[#0a0a0a] border border-slate-200 dark:border-white/5 rounded-3xl shadow-sm overflow-hidden text-slate-900 dark:text-slate-100">
            <form method="POST" action="{{ route('tenant.profile.store') }}" enctype="multipart/form-data" class="p-8 lg:p-10 space-y-10">
                @csrf

                <!-- Status Selector -->
                <div class="bg-slate-50 dark:bg-white/5 p-6 rounded-2xl border border-transparent dark:border-white/5">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-4">Are you an actively enrolled student?</label>
                    <div class="flex items-center gap-8">
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="type" value="student" class="w-5 h-5 text-indigo-600 border-slate-300 dark:border-slate-600 focus:ring-indigo-500 dark:bg-slate-950" onchange="toggleFields()" {{ old('type', optional($profile)->type) !== 'ordinary' ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Yes, I am a Student</span>
                        </label>
                        <label class="flex items-center cursor-pointer group">
                            <input type="radio" name="type" value="ordinary" class="w-5 h-5 text-indigo-600 border-slate-300 dark:border-slate-600 focus:ring-indigo-500 dark:bg-slate-950" onchange="toggleFields()" {{ old('type', optional($profile)->type) === 'ordinary' ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">No, Professional/Ordinary</span>
                        </label>
                    </div>
                </div>

                <!-- Personal Info Section -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', optional($profile)->phone_number) }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border {{ $errors->has('phone_number') ? 'border-rose-400' : 'border-transparent dark:border-slate-700/50' }} rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('phone_number')" class="mt-1" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob', optional($profile)->dob) }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border {{ $errors->has('dob') ? 'border-rose-400' : 'border-transparent dark:border-slate-700/50' }} rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('dob')" class="mt-1" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Gender</label>
                            <select name="gender" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border {{ $errors->has('gender') ? 'border-rose-400' : 'border-transparent dark:border-slate-700/50' }} rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="Male" {{ old('gender', optional($profile)->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', optional($profile)->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-1" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Religion</label>
                            <select name="religion" required class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all cursor-pointer">
                                <option value="" disabled selected>Select Religion</option>
                                <option value="Christianity" {{ old('religion', optional($profile)->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                <option value="Islam" {{ old('religion', optional($profile)->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Other" {{ old('religion', optional($profile)->religion) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">State of Origin</label>
                            <input type="text" name="state_of_origin" value="{{ old('state_of_origin', optional($profile)->state_of_origin) }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">LGA</label>
                            <input type="text" name="lga" value="{{ old('lga', optional($profile)->lga) }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Next of Kin</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Full Name</label>
                            <input type="text" name="next_of_kin" value="{{ old('next_of_kin', optional($profile)->next_of_kin) }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Phone Number</label>
                            <input type="text" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', optional($profile)->next_of_kin_phone) }}" required
                                   class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                    </div>
                </div>

                <!-- Academic Info (Dynamic) -->
                <div id="student_fields">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Academic Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-1 md:col-span-2 space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">University / Institution</label>
                            <input type="text" name="university" value="{{ old('university', optional($profile)->university) }}"
                                   class="student-input w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Matric Number</label>
                            <input type="text" name="matric_number" value="{{ old('matric_number', optional($profile)->matric_number) }}"
                                   class="student-input w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Level</label>
                            <select name="level" class="student-input w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                                <option value="" disabled selected>Select Level</option>
                                @foreach(['100 Level', '200 Level', '300 Level', '400 Level', '500 Level', 'Postgraduate'] as $lvl)
                                    <option value="{{ $lvl }}" {{ old('level', optional($profile)->level) == $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Faculty</label>
                            <input type="text" name="faculty" value="{{ old('faculty', optional($profile)->faculty) }}"
                                   class="student-input w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Department</label>
                             <input type="text" name="department" value="{{ old('department', optional($profile)->department) }}"
                                   class="student-input w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border {{ $errors->has('department') ? 'border-rose-400' : 'border-transparent dark:border-slate-700/50' }} rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('department')" class="mt-1" />
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Course of Study</label>
                            <input type="text" name="course" value="{{ old('course', optional($profile)->course) }}"
                                   class="student-input w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border {{ $errors->has('course') ? 'border-rose-400' : 'border-transparent dark:border-slate-700/50' }} rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all">
                            <x-input-error :messages="$errors->get('course')" class="mt-1" />
                        </div>
                    </div>
                </div>

                <div id="ordinary_fields" class="hidden">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Residency Info</h3>
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Current Home Address</label>
                        <textarea name="address" rows="3" class="ordinary-input w-full px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-transparent dark:border-slate-700/50 rounded-xl focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 outline-none text-slate-900 dark:text-white font-bold transition-all resize-none">{{ old('address', optional($profile)->address) }}</textarea>
                    </div>
                </div>

                <!-- Passport Section -->
                <div>
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 pb-2 border-b border-slate-50 dark:border-slate-800">Identity Document</h3>
                    <div class="flex items-center gap-6">
                        @if(optional($profile)->passport)
                            <img src="{{ Storage::url($profile->passport) }}" class="w-20 h-20 rounded-2xl object-cover ring-4 ring-slate-50 dark:ring-slate-800/50 shadow-sm">
                        @endif
                        <div class="flex-1">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1 mb-2">Passport Photograph</label>
                            <input type="file" name="passport_image" accept="image/*" {{ optional($profile)->passport ? '' : 'required' }}
                                   class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 dark:file:bg-white/10 file:text-indigo-600 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-white/20 transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-50 dark:border-slate-800 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-indigo-600 dark:bg-indigo-500 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-indigo-100 dark:shadow-none hover:bg-indigo-700 dark:hover:bg-indigo-400 transition-all">
                        Save Identity Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            toggleFields();
        });

        function toggleFields() {
            var isStudent = document.querySelector('input[name="type"][value="student"]').checked;
            var studentDiv = document.getElementById('student_fields');
            var ordinaryDiv = document.getElementById('ordinary_fields');
            var studentInputs = document.querySelectorAll('.student-input');
            var ordinaryInputs = document.querySelectorAll('.ordinary-input');

            if (isStudent) {
                studentDiv.classList.remove('hidden');
                ordinaryDiv.classList.add('hidden');
                studentInputs.forEach(el => el.required = true);
                ordinaryInputs.forEach(el => el.required = false);
            } else {
                studentDiv.classList.add('hidden');
                ordinaryDiv.classList.remove('hidden');
                studentInputs.forEach(el => el.required = false);
                ordinaryInputs.forEach(el => el.required = true);
            }
        }
    </script>
</x-app-layout>
