<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                {{ __('messages.services') }}
            </h2>
            <a href="{{ route('services.create') }}" class="btn">
                New Service
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
            <form method="GET" action="{{ route('services.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Service name..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus-ring dark:bg-gray-800 dark:text-white">
                        <option value="">All</option>
                        <option value="spa" {{ request('category') == 'spa' ? 'selected' : '' }}>Spa & Wellness</option>
                        <option value="restaurant" {{ request('category') == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                        <option value="bar" {{ request('category') == 'bar' ? 'selected' : '' }}>Bar</option>
                        <option value="laundry" {{ request('category') == 'laundry' ? 'selected' : '' }}>Laundry</option>
                        <option value="transport" {{ request('category') == 'transport' ? 'selected' : '' }}>Transport</option>
                        <option value="business_center" {{ request('category') == 'business_center' ? 'selected' : '' }}>Business Center</option>
                        <option value="fitness" {{ request('category') == 'fitness' ? 'selected' : '' }}>Fitness</option>
                        <option value="room_service" {{ request('category') == 'room_service' ? 'selected' : '' }}>Room Service</option>
                        <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn w-full justify-center bg-gray-600 hover:bg-gray-700 active:bg-gray-900">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <x-data-table :headers="['Service', 'Category', 'Price', 'Capacity', 'Status']" :items="$services">
            @forelse($services as $service)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</div>
                        @if($service->description)
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($service->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $service->category_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ number_format($service->price, 0, ',', ' ') }} FCFA</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $service->pricing_type)) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                        {{ $service->max_capacity ? $service->max_capacity . ' people' : 'Unlimited' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('services.edit', $service) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('services.destroy', $service) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this service?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        No services found.
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </div>
</x-app-layout>
