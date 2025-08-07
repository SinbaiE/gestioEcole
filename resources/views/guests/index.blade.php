<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                {{ __('messages.guests') }}
            </h2>
            <a href="{{ route('guests.create') }}" class="btn">
                New Guest
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
            <form method="GET" action="{{ route('guests.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, email, phone..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="guest_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guest Type</label>
                    <select name="guest_type" id="guest_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                        <option value="">All</option>
                        <option value="individual" {{ request('guest_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="corporate" {{ request('guest_type') == 'corporate' ? 'selected' : '' }}>Corporate</option>
                        <option value="group" {{ request('guest_type') == 'group' ? 'selected' : '' }}>Group</option>
                        <option value="vip" {{ request('guest_type') == 'vip' ? 'selected' : '' }}>VIP</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn w-full justify-center bg-gray-600 hover:bg-gray-700 active:bg-gray-900">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <x-data-table :headers="['Guest', 'Contact', 'Type', 'Nationality', 'Stays', 'Points']" :items="$guests">
            @forelse($guests as $guest)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ strtoupper(substr($guest->first_name, 0, 1) . substr($guest->last_name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $guest->full_name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Guest since {{ $guest->created_at->format('M Y') }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $guest->email }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $guest->phone }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($guest->guest_type == 'vip') bg-purple-100 text-purple-800
                            @elseif($guest->guest_type == 'corporate') bg-blue-100 text-blue-800
                            @elseif($guest->guest_type == 'group') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($guest->guest_type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $guest->nationality ?? 'Not specified' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $guest->reservations_count }} stay(s)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ number_format($guest->loyalty_points) }} pts
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('guests.show', $guest) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('guests.edit', $guest) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
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
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        No guests found.
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </div>
</x-app-layout>
