<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Entry Ledger') }}
            </h2>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase rounded-full border border-amber-200">Manual Entry</span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-[32px] border border-slate-100">
                <div class="p-8 md:p-12">
                    
                    <div class="mb-10 text-center md:text-left">
                        <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Record External Payment</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2">Log payments received via bank transfer, USSD or cash</p>
                    </div>

                    <form method="POST" action="{{ route('payments.storeManual') }}" class="space-y-8">
                        @csrf

                        {{-- Room/Tenant Selection --}}
                        <div class="space-y-2">
                            <label for="room_id" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Select Tenant Allocation</label>
                            <div class="relative group">
                                <select id="room_id" name="room_id" class="w-full pl-4 pr-10 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-sm font-bold text-slate-700 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer" required>
                                    <option value="" disabled selected>Choose an occupied unit...</option>
                                    @foreach($rooms as $room)
                                        @php
                                             $tenant = optional($room->bookings->whereIn('status', ['approved', 'confirmed'])->first())->user;
                                         @endphp
                                         @if($tenant)
                                            <option value="{{ $room->id }}">
                                                {{ $room->property->name }} — Rm {{ $room->room_number }} ({{ $tenant->name }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 group-focus-within:rotate-180 transition-transform">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Amount Paid --}}
                            <div class="space-y-2">
                                <label for="amount" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Amount Paid (₦)</label>
                                <div class="relative group">
                                    <input id="amount" type="number" step="0.01" name="amount" placeholder="0.00" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-sm font-black text-slate-800 outline-none focus:border-emerald-500 focus:bg-white transition-all" required>
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 font-black text-lg group-focus-within:text-emerald-500">₦</div>
                                </div>
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>

                            {{-- Payment Year --}}
                            <div class="space-y-2">
                                <label for="payment_year" class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Coverage Year</label>
                                <div class="relative group">
                                    <input id="payment_year" type="number" min="2000" max="2100" name="payment_year" :value="old('payment_year', date('Y'))" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-50 rounded-2xl text-sm font-black text-slate-800 outline-none focus:border-indigo-500 focus:bg-white transition-all" required>
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors group-focus-within:text-indigo-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002-2z" /></svg>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('payment_year')" class="mt-2" />
                            </div>
                        </div>

                        <div class="pt-6 flex flex-col items-center gap-6">
                            <button type="submit" style="background-color: #10b981 !important; color: white !important;" class="w-full py-5 text-white text-xs font-black uppercase rounded-3xl shadow-2xl shadow-emerald-100 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3 border-2 border-white/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                <span>Record Ledger Entry</span>
                            </button>
                            
                            <a href="{{ route('payments.index') }}" class="text-[10px] font-black text-slate-300 uppercase tracking-widest hover:text-slate-500 transition-colors">
                                Cancel & Return to Ledger
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
