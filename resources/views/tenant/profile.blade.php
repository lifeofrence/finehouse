<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight dark:text-gray-200">
            {{ __('Tenant Profile Configuration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl dark:bg-[#0f0f0f] dark:border-gray-800 border border-gray-100">
                
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Complete Your Profile</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Please provide accurate details to finalize your registration and access rooms.</p>
                </div>

                <div class="p-8 pt-6">
                    <form method="POST" action="{{ route('tenant.profile.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Role Selector -->
                        <div class="mb-8 p-6 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                            <label class="block font-semibold text-sm text-indigo-900 dark:text-indigo-400 mb-3">
                                Are you an actively enrolled Student?
                            </label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="type" value="student" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600" onchange="toggleFields()" {{ old('type', optional($profile)->type) !== 'ordinary' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium tracking-wide">Yes, I am a Student</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="type" value="ordinary" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500 dark:bg-gray-800 dark:border-gray-600" onchange="toggleFields()" {{ old('type', optional($profile)->type) === 'ordinary' ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium tracking-wide">No, Ordinary Person</span>
                                </label>
                            </div>
                        </div>

                        <!-- Form Grid Layout -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Shared Fields -->
                            <div class="col-span-1 md:col-span-2 text-xl font-extrabold text-gray-900 dark:text-white border-b-2 border-indigo-100 dark:border-indigo-900/40 pb-3 mt-8 mb-2 tracking-tight">
                                Basic Information
                            </div>

                            <div>
                                <x-input-label for="phone_number" :value="__('Phone Number')" class="dark:text-gray-300" />
                                <x-text-input id="phone_number" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" type="text" name="phone_number" :value="old('phone_number', optional($profile)->phone_number)" required />
                            </div>

                            <div>
                                <x-input-label for="dob" :value="__('Date of Birth')" class="dark:text-gray-300"/>
                                <x-text-input id="dob" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" type="date" name="dob" :value="old('dob', optional($profile)->dob)" required />
                            </div>

                            <div>
                                <x-input-label for="gender" :value="__('Gender')" class="dark:text-gray-300"/>
                                <select id="gender" name="gender" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:text-gray-300" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male" {{ old('gender', optional($profile)->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', optional($profile)->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="religion" :value="__('Religion')" class="dark:text-gray-300"/>
                                <select id="religion" name="religion" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:text-gray-300" required>
                                    <option value="" disabled selected>Select Religion</option>
                                    <option value="Christianity" {{ old('religion', optional($profile)->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                    <option value="Islam" {{ old('religion', optional($profile)->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Other" {{ old('religion', optional($profile)->religion) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="state_of_origin" :value="__('State of Origin')" class="dark:text-gray-300"/>
                                <x-text-input id="state_of_origin" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" type="text" name="state_of_origin" :value="old('state_of_origin', optional($profile)->state_of_origin)" required />
                            </div>

                            <div>
                                <x-input-label for="lga" :value="__('LGA')" class="dark:text-gray-300"/>
                                <x-text-input id="lga" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" type="text" name="lga" :value="old('lga', optional($profile)->lga)" required />
                            </div>

                            <div class="col-span-1 md:col-span-2 text-xl font-extrabold text-gray-900 dark:text-white border-b-2 border-indigo-100 dark:border-indigo-900/40 pb-3 mt-8 mb-2 tracking-tight">
                                Emergency Contact
                            </div>

                            <div>
                                <x-input-label for="next_of_kin" :value="__('Next of Kin (Full Name)')" class="dark:text-gray-300"/>
                                <x-text-input id="next_of_kin" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" type="text" name="next_of_kin" :value="old('next_of_kin', optional($profile)->next_of_kin)" required />
                            </div>

                            <div>
                                <x-input-label for="next_of_kin_phone" :value="__('Next of Kin Phone No.')" class="dark:text-gray-300"/>
                                <x-text-input id="next_of_kin_phone" class="block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm" type="text" name="next_of_kin_phone" :value="old('next_of_kin_phone', optional($profile)->next_of_kin_phone)" required />
                            </div>

                            <div class="col-span-1 md:col-span-2 text-xl font-extrabold text-gray-900 dark:text-white border-b-2 border-indigo-100 dark:border-indigo-900/40 pb-3 mt-8 mb-2 tracking-tight">
                                Identity Details
                            </div>

                            <div class="col-span-1 md:col-span-2">
                                <x-input-label for="passport_image" :value="__('Passport Photograph')" class="dark:text-gray-300"/>
                                @if(optional($profile)->passport)
                                    <div class="mb-2 mt-2">
                                        <img src="{{ Storage::url($profile->passport) }}" alt="Passport" class="w-16 h-16 rounded-full object-cover border-2 border-indigo-200 shadow-sm">
                                    </div>
                                @endif
                                <input id="passport_image" class="block mt-1 w-full p-2 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-lg text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all dark:file:bg-indigo-900/30 dark:hover:file:bg-indigo-900/50" type="file" name="passport_image" accept="image/*" {{ optional($profile)->passport ? '' : 'required' }}/>
                            </div>

                            <!-- Student Fields -->
                            <div id="student_fields" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 transition-all duration-300">
                                <div class="col-span-1 md:col-span-2 text-xl font-extrabold text-gray-900 dark:text-white border-b-2 border-indigo-100 dark:border-indigo-900/40 pb-3 mt-8 mb-2 tracking-tight">
                                    Academic Information
                                </div>
                                
                                <div>
                                    <x-input-label for="university" :value="__('University / Institution')" class="dark:text-gray-300"/>
                                    <x-text-input id="university" class="student-input block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 shadow-sm" type="text" name="university" :value="old('university', optional($profile)->university)" />
                                </div>
                                <div>
                                    <x-input-label for="matric_number" :value="__('Matriculation Number')" class="dark:text-gray-300"/>
                                    <x-text-input id="matric_number" class="student-input block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 shadow-sm" type="text" name="matric_number" :value="old('matric_number', optional($profile)->matric_number)" />
                                </div>
                                <div>
                                    <x-input-label for="faculty" :value="__('Faculty')" class="dark:text-gray-300"/>
                                    <x-text-input id="faculty" class="student-input block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 shadow-sm" type="text" name="faculty" :value="old('faculty', optional($profile)->faculty)" />
                                </div>
                                <div>
                                    <x-input-label for="department" :value="__('Department')" class="dark:text-gray-300"/>
                                    <x-text-input id="department" class="student-input block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 shadow-sm" type="text" name="department" :value="old('department', optional($profile)->department)" />
                                </div>
                                <div>
                                    <x-input-label for="course" :value="__('Course of Study')" class="dark:text-gray-300"/>
                                    <x-text-input id="course" class="student-input block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 shadow-sm" type="text" name="course" :value="old('course', optional($profile)->course)" />
                                </div>
                                <div>
                                    <x-input-label for="level" :value="__('Current Level')" class="dark:text-gray-300"/>
                                    <select id="level" name="level" class="student-input block mt-1 w-full bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:text-gray-300">
                                        <option value="" disabled selected>Select Level</option>
                                        <option value="100 Level" {{ old('level', optional($profile)->level) == '100 Level' ? 'selected' : '' }}>100 Level</option>
                                        <option value="200 Level" {{ old('level', optional($profile)->level) == '200 Level' ? 'selected' : '' }}>200 Level</option>
                                        <option value="300 Level" {{ old('level', optional($profile)->level) == '300 Level' ? 'selected' : '' }}>300 Level</option>
                                        <option value="400 Level" {{ old('level', optional($profile)->level) == '400 Level' ? 'selected' : '' }}>400 Level</option>
                                        <option value="500 Level" {{ old('level', optional($profile)->level) == '500 Level' ? 'selected' : '' }}>500 Level</option>
                                        <option value="Postgraduate" {{ old('level', optional($profile)->level) == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Ordinary Fields -->
                            <div id="ordinary_fields" class="col-span-1 md:col-span-2 mt-4 hidden transition-all duration-300">
                                <div class="col-span-1 md:col-span-2 text-xl font-extrabold text-gray-900 dark:text-white border-b-2 border-indigo-100 dark:border-indigo-900/40 pb-3 mt-8 mb-2 tracking-tight">
                                    Residential Information
                                </div>
                                <div>
                                    <x-input-label for="address" :value="__('Current Home Address')" class="dark:text-gray-300"/>
                                    <textarea id="address" class="ordinary-input block mt-1 w-full p-3 bg-gray-50 dark:bg-gray-900 border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 rounded-md shadow-sm dark:text-gray-300" name="address" rows="3">{{ old('address', optional($profile)->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-10 pt-6 border-t border-gray-100 dark:border-gray-800">
                            <x-primary-button class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 shadow-md">
                                {{ __('Save Profile Configuration') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to toggle fields -->
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
                
                // Add required attributes to student fields
                studentInputs.forEach(el => el.setAttribute('required', 'true'));
                
                // Remove required attributes from ordinary fields
                ordinaryInputs.forEach(el => el.removeAttribute('required'));
            } else {
                studentDiv.classList.add('hidden');
                ordinaryDiv.classList.remove('hidden');
                
                // Add required attributes to ordinary fields
                ordinaryInputs.forEach(el => el.setAttribute('required', 'true'));
                
                // Remove required attributes from student fields
                studentInputs.forEach(el => el.removeAttribute('required'));
            }
        }
    </script>
</x-app-layout>
