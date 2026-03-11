<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Interview Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2">Room</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Tenant</th>
                                @endif
                                <th class="pb-2">Date & Time</th>
                                <th class="pb-2">Format/Location</th>
                                <th class="pb-2">Status</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr class="border-b">
                                    <td class="py-3">Room {{ $booking->room->room_number }} <br><span class="text-sm text-gray-500">{{ $booking->room->property->name }}</span></td>
                                    
                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">{{ $booking->user->name }}</td>
                                    @endif

                                    <td class="py-3">{{ \Carbon\Carbon::parse($booking->interview_date)->format('M d, Y - h:i A') }}</td>
                                    <td class="py-3">
                                        @if($booking->interview_location)
                                            Offline: {{ $booking->interview_location }}
                                        @else
                                            Online: {!! $booking->interview_link ? '<a href="'.$booking->interview_link.'" class="text-blue-500 underline" target="_blank">Link</a>' : 'Pending Link' !!}
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded text-xs text-white 
                                            {{ $booking->status === 'approved' ? 'bg-green-500' : ($booking->status === 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    
                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">
                                            @if($booking->status === 'pending')
                                                <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 text-sm font-bold mr-2">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-bold">Reject</button>
                                                </form>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">No bookings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
