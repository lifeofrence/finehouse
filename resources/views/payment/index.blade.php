<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payments & Rent Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    @if(Auth::user()->role === 'tenant')
                        <div class="mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold">My Payment History</h3>
                            <a href="{{ route('payments.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Make a Payment
                            </a>
                        </div>
                        @if(Auth::user()->tenantProfile && Auth::user()->tenantProfile->rent_commencement_date)
                            <div class="bg-indigo-50 border border-indigo-200 rounded p-4 mb-6">
                                <h4 class="font-bold text-indigo-800 border-b border-indigo-200 pb-2 mb-2">Rent Tracking</h4>
                                <div class="flex justify-between">
                                    <span><strong>Start Date:</strong> {{ \Carbon\Carbon::parse(Auth::user()->tenantProfile->rent_commencement_date)->format('M d, Y') }}</span>
                                    <span><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse(Auth::user()->tenantProfile->rent_expiry_date)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold mb-4">All Payments</h3>
                            <a href="{{ route('payments.manual') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                Log Manual Payment
                            </a>
                        </div>
                    @endif

                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2">Date</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Tenant</th>
                                @endif
                                <th class="pb-2">Room/Property</th>
                                <th class="pb-2">Amount</th>
                                <th class="pb-2">Method/Proof</th>
                                <th class="pb-2">Status</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                                <tr class="border-b">
                                    <td class="py-3">{{ $payment->created_at->format('M d, Y') }}</td>
                                    
                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">{{ $payment->user->name }}</td>
                                    @endif

                                    <td class="py-3">{{ $payment->room->room_number ?? 'N/A' }} <br><span class="text-sm text-gray-500">{{ $payment->property->name }}</span></td>
                                    <td class="py-3 font-bold text-indigo-600">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="py-3">
                                        @if($payment->is_external)
                                            External 
                                            @if($payment->external_proof_path)
                                                (<a href="{{ asset('storage/' . $payment->external_proof_path) }}" target="_blank" class="text-blue-500 underline text-sm">View Proof</a>)
                                            @endif
                                        @else
                                            Online (Paystack)
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded text-xs text-white 
                                            {{ $payment->status === 'verified' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    
                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">
                                            @if($payment->status === 'pending')
                                                <form method="POST" action="{{ route('payments.verify', $payment->id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-green-600 hover:text-green-900 text-sm font-bold bg-green-100 px-2 py-1 rounded border border-green-200">Verify Payment</button>
                                                </form>
                                            @elseif($payment->verified_by)
                                                <span class="text-xs text-gray-500">Verified by {{ $payment->verifier->name ?? 'Admin' }}</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">No payments found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
