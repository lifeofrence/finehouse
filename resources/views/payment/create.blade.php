<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pay Rent: ') }} {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg shadow-2xl shadow-indigo-100/50">
                <div class="p-8 text-gray-900">
                    
                    <!-- Status/Error messages -->
                    @if(session('status'))
                        <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 text-sm font-bold rounded-r-xl">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 text-sm rounded-r-xl">
                            <p class="font-black uppercase tracking-widest text-[10px] mb-2">Something went wrong</p>
                            <ul class="list-disc list-inside font-bold">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <!-- Room Summary -->
                    <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                        <div class="flex items-center">
                            <div class="w-1/3 bg-indigo-600 p-8 text-white">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-1">Assigned Unit</p>
                                <h3 class="text-3xl font-black">{{ $room->room_number }}</h3>
                            </div>
                            <div class="w-2/3 p-6 flex justify-between items-center bg-slate-50/50">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $room->property->name }}</p>
                                    <p class="text-lg font-black text-slate-800 uppercase tracking-tight">Annual Rent Amount</p>
                                    
                                    @if(Auth::user()->tenantProfile && Auth::user()->tenantProfile->rent_expiry_date)
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[9px] font-black uppercase rounded-md">Current Expiry</span>
                                            <span class="text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse(Auth::user()->tenantProfile->rent_expiry_date)->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[9px] font-black uppercase rounded-md">Usage</span>
                                            <span class="text-xs font-bold text-slate-400 italic">No active stay recorded</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <h4 class="text-2xl font-black text-indigo-600">₦{{ number_format($room->price, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Left Column: Payment Details -->
                            <div>
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Payment Configuration</h3>
                                
                                <div class="mb-6">
                                    <x-input-label for="amount" :value="__('Amount to Pay (Upfront)')" />
                                    <div class="relative mt-2">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 font-bold italic">₦</span>
                                        <x-text-input id="amount" class="block pl-10 w-full border-slate-200 focus:ring-indigo-500 rounded-xl font-bold py-3" type="number" step="0.01" name="amount" :value="old('amount', $room->price)" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                    <p class="text-[9px] text-slate-400 mt-2 font-bold uppercase">Defaults to full annual rent amount.</p>
                                </div>
                            </div>

                            <!-- Right Column: Method -->
                            <div>
                                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Settlement Method</h3>

                                <div class="mb-6">
                                    <x-input-label for="is_external" :value="__('How would you like to pay?')" />
                                    <select id="is_external" name="is_external" class="block mt-2 w-full border-slate-200 focus:border-indigo-500 rounded-xl shadow-sm text-sm font-bold py-3" required onchange="toggleProofField()">
                                        <option value="0">Secure Online Pay (Paystack)</option>
                                        <option value="1">Manual Settlement (Transfer/Cash)</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('is_external')" class="mt-2" />
                                </div>

                                <div id="proof-field" class="mb-4 space-y-4 p-5 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/30" style="display: none;">
                                    <div>
                                        <p class="text-[10px] font-black text-slate-800 uppercase tracking-widest mb-1">Verify Manual Payment</p>
                                        <p class="text-[9px] text-slate-400 uppercase font-bold">Please upload your receipts for verification.</p>
                                    </div>
                                    <input id="proof_image" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer" type="file" name="proof_image" accept="image/*" />
                                    <x-input-error :messages="$errors->get('proof_image')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-between gap-8">
                            <div class="flex items-start gap-3 opacity-60">
                                <div class="mt-1">
                                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight max-w-xs">Transactions are encrypted and secured. Upfront rent payments are immediately logged for administrative verification.</p>
                            </div>
                            <x-primary-button class="px-10 py-5 bg-indigo-600 hover:bg-black rounded-2xl transition-all shadow-xl shadow-indigo-200">
                                {{ __('Proceed to Complete') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleProofField() {
            var method = document.getElementById('is_external').value;
            var proofField = document.getElementById('proof-field');
            if (method === '1') {
                proofField.style.display = 'block';
                document.getElementById('proof_image').required = true;
            } else {
                proofField.style.display = 'none';
                document.getElementById('proof_image').required = false;
            }
        }
    </script>
</x-app-layout>
