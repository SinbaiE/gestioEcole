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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentReservations as $reservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $reservation->guest->full_name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $reservation->guest->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $reservation->roomType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $reservation->check_in_date->format('d/m/Y') }} - {{ $reservation->check_out_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA
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
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Today's Arrivals</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($todayArrivals as $arrival)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $arrival->guest->full_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $arrival->roomType->name }}</p>
                            </div>
                            @if($arrival->room)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Room {{ $arrival->room->room_number }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center">No arrivals today.</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md mt-8">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Today's Departures</h3>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($todayDepartures as $departure)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $departure->guest->full_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Room {{ $departure->room->room_number }}</p>
                            </div>
                            <a href="{{ route('reservations.show', $departure) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                Check-out
                            </a>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center">No departures today.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
