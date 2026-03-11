<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold">Latest Announcements</h3>
                        @if(in_array(Auth::user()->role, ['admin', 'landlord', 'lodge_president', 'caretaker']))
                            <a href="{{ route('announcements.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                Create Announcement
                            </a>
                        @endif
                    </div>

                    @forelse($announcements as $announcement)
                        <div class="mb-4 bg-gray-50 border border-gray-200 rounded p-4 shadow-sm">
                            <h4 class="font-bold text-xl text-indigo-700 mb-1">{{ $announcement->title }}</h4>
                            <div class="text-xs text-gray-500 mb-3 border-b pb-2">
                                For: <strong>{{ $announcement->property->name }}</strong> | 
                                Posted by: {{ $announcement->user->name }} | 
                                {{ $announcement->created_at->diffForHumans() }}
                            </div>
                            <div class="text-gray-800 whitespace-pre-wrap">{{ $announcement->message }}</div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-6 border rounded bg-gray-50">
                            No announcements available right now.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
