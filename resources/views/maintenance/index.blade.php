<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Maintenance Log') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    @if(Auth::user()->role === 'tenant')
                        <div class="mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold">My Reported Issues</h3>
                            <a href="{{ route('maintenance.create') }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                Report Incident
                            </a>
                        </div>
                    @else
                        <h3 class="text-lg font-bold mb-4">Maintenance Requests Log</h3>
                    @endif

                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-2">Date</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Tenant</th>
                                @endif
                                <th class="pb-2">Room/Property</th>
                                <th class="pb-2">Issue Description</th>
                                <th class="pb-2">Status</th>
                                <th class="pb-2">Photos/Docs</th>
                                @if(Auth::user()->role !== 'tenant')
                                    <th class="pb-2">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr class="border-b">
                                    <td class="py-3">{{ $req->created_at->format('M d, Y') }}</td>
                                    
                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">{{ $req->user->name }}</td>
                                    @endif

                                    <td class="py-3 text-sm text-gray-700">
                                        {{ $req->property->name }} <br>
                                        <span class="font-bold">Rm {{ $req->room->room_number ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-3 text-sm max-w-xs truncate" title="{{ $req->issue_description }}">{{ $req->issue_description }}</td>
                                    
                                    <td class="py-3">
                                        <span class="px-2 py-1 rounded text-xs text-white 
                                            {{ $req->status === 'resolved' ? 'bg-green-500' : ($req->status === 'in_progress' ? 'bg-blue-500' : 'bg-red-500') }}">
                                            {{ str_replace('_', ' ', ucfirst($req->status)) }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @if($req->image_path)
                                            <a href="{{ asset('storage/' . $req->image_path) }}" target="_blank" class="text-blue-500 underline text-sm">View Photo</a>
                                        @else
                                            <span class="text-gray-400 text-sm">None</span>
                                        @endif
                                    </td>
                                    
                                    @if(Auth::user()->role !== 'tenant')
                                        <td class="py-3">
                                            @if($req->status !== 'resolved')
                                                <form method="POST" action="{{ route('maintenance.update', $req->id) }}" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" onchange="this.form.submit()" class="border-gray-300 rounded text-sm py-1 px-2 pr-6">
                                                        <option value="pending" {{ $req->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="in_progress" {{ $req->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="resolved" {{ $req->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    </select>
                                                </form>
                                            @else
                                                 <span class="text-green-600 font-bold text-sm">✓ Done</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 text-center text-gray-500">No incident logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
