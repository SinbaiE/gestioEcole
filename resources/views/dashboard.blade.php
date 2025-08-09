<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
            {{ __('messages.dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card-stat
            title="Total Rooms"
            :value="$stats['total_rooms']"
            icon="reservations"
            color="blue"
        />
        <x-card-stat
            title="Occupied Rooms"
            :value="$stats['occupied_rooms']"
            icon="occupancy"
            color="green"
        />
        <x-card-stat
            title="Occupancy Rate"
            :value="$stats['occupancy_rate'] . '%'"
            icon="occupancy"
            color="yellow"
        />
        <x-card-stat
            title="Monthly Revenue"
            :value="number_format($monthlyRevenue, 0, ',', ' ') . ' FCFA'"
            icon="revenue"
            color="purple"
        />
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <x-line-chart :data="$revenueChart" title="Revenue Last 30 Days" />
        </div>
        <div>
            <x-bar-chart :data="$occupancyChart" title="Occupancy Rate Last 30 Days (%)" />
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Recent Reservations</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Guest</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Room Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentReservations as $reservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reservation->guest->full_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $reservation->roomType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $reservation->check_in_date->format('d/m/Y') }} - {{ $reservation->check_out_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'confirmed' => 'bg-blue-100 text-blue-800',
                                                'checked_in' => 'bg-green-100 text-green-800',
                                                'checked_out' => 'bg-gray-100 text-gray-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                'no_show' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Today's Arrivals & Departures</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-200">Arrivals ({{ $todayArrivals->count() }})</h4>
                        @forelse($todayArrivals as $arrival)
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-sm text-gray-900 dark:text-white">{{ $arrival->guest->full_name }}</p>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $arrival->roomType->name }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No arrivals today.</p>
                        @endforelse
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-md font-semibold text-gray-700 dark:text-gray-200">Departures ({{ $todayDepartures->count() }})</h4>
                        @forelse($todayDepartures as $departure)
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-sm text-gray-900 dark:text-white">{{ $departure->guest->full_name }}</p>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Room {{ $departure->room->room_number }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">No departures today.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
