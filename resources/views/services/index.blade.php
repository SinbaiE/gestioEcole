<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                {{ __('messages.services') }}
            </h2>
            <a href="{{ route('services.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Service name..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
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
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                        <a href="{{ route('services.show', $service) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View</a>
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
