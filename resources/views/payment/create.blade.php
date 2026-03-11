<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pay Rent for Room: ') }} {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-xl font-bold mb-4 w-full border-b pb-2">Rent Details</h3>
                    <div class="mb-6 bg-gray-50 p-4 rounded-md">
                        <p><strong>Property:</strong> {{ $room->property->name }}</p>
                        <p><strong>Room:</strong> {{ $room->room_number }}</p>
                        <p><strong>Amount Due:</strong> ${{ number_format($room->price, 2) }}</p>
                    </div>

                    <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        
                        <div class="mb-4">
                            <x-input-label for="amount" :value="__('Amount Paying')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount', $room->price)" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="is_external" :value="__('Payment Method')" />
                            <select id="is_external" name="is_external" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required onchange="toggleProofField()">
                                <option value="0">Pay Online via Paystack (Coming Soon)</option>
                                <option value="1">I have paid externally (Bank Transfer, Cash)</option>
                            </select>
                            <x-input-error :messages="$errors->get('is_external')" class="mt-2" />
                        </div>

                        <div id="proof-field" class="mb-4" style="display: none;">
                            <x-input-label for="proof_image" :value="__('Upload Proof of Payment (Image/Screenshot)')" />
                            <input id="proof_image" class="block mt-1 w-full border border-gray-300 rounded-md p-2" type="file" name="proof_image" accept="image/*" />
                            <x-input-error :messages="$errors->get('proof_image')" class="mt-2" />
                            <p class="text-xs text-gray-500 mt-1">Accepts JPG, PNG. Max size 2MB.</p>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Submit Payment') }}
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
