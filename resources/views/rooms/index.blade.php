<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                {{ __('messages.rooms') }}
            </h2>
            <a href="{{ route('rooms.create') }}" class="btn">
                New Room
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
            <form method="GET" action="{{ route('rooms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Room number..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                        <option value="">All</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="cleaning" {{ request('status') == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                        <option value="out_of_order" {{ request('status') == 'out_of_order' ? 'selected' : '' }}>Out of Order</option>
                    </select>
                </div>
                <div>
                    <label for="room_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Room Type</label>
                    <select name="room_type" id="room_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                        <option value="">All</option>
                        @foreach($roomTypes as $roomType)
                            <option value="{{ $roomType->id }}" {{ request('room_type') == $roomType->id ? 'selected' : '' }}>
                                {{ $roomType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn w-full justify-center bg-gray-600 hover:bg-gray-700 active:bg-gray-900">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <x-data-table :headers="['Room', 'Type', 'Floor', 'Status', 'Housekeeping']" :items="$rooms">
            @forelse($rooms as $room)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $room->room_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $room->roomType->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($room->roomType->base_price, 0, ',', ' ') }} FCFA/night</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $room->floor }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $roomStatusClasses = [
                                'available' => 'bg-green-100 text-green-800',
                                'occupied' => 'bg-blue-100 text-blue-800',
                                'maintenance' => 'bg-yellow-100 text-yellow-800',
                                'cleaning' => 'bg-purple-100 text-purple-800',
                                'out_of_order' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roomStatusClasses[$room->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $room->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $housekeepingClasses = [
                                'clean' => 'bg-green-100 text-green-800',
                                'dirty' => 'bg-red-100 text-red-800',
                                'inspected' => 'bg-blue-100 text-blue-800',
                                'maintenance' => 'bg-yellow-100 text-yellow-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $housekeepingClasses[$room->housekeeping_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($room->housekeeping_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('rooms.show', $room) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('rooms.edit', $room) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        No rooms found.
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </div>
</x-app-layout>
