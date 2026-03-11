<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Room: ') }} {{ $room->room_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Assign Tenant to "{{ $room->room_number }}"</h3>
                    <p class="mb-4 text-gray-600">This action will mark the room as occupied and assign it to the selected tenant.</p>

                    <form method="POST" action="{{ route('rooms.store_assign', $room->id) }}">
                        @csrf

                        <!-- Tenant -->
                        <div>
                            <x-input-label for="tenant_id" :value="__('Select Tenant')" />
                            <select id="tenant_id" name="tenant_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>-- Choose a registered tenant --</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}">{{ $tenant->name }} ({{ $tenant->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('tenant_id')" class="mt-2" />
                            
                            @if($tenants->isEmpty())
                                <p class="text-red-500 text-sm mt-2">There are no tenants registered in the system yet. Tenants must register an account first.</p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4" :disabled="$tenants->isEmpty()">
                                {{ __('Confirm Assignment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
