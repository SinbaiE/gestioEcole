<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Chambres</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_rooms'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Chambres Occupées</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['occupied_rooms'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Taux d'Occupation</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['occupancy_rate'] }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Revenus du Mois</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ number_format($monthlyRevenue, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Arrivées du jour -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Arrivées du Jour ({{ $todayArrivals->count() }})</h3>
                </div>
                <div class="p-6">
                    @if($todayArrivals->count() > 0)
                        <div class="space-y-4">
                            @foreach($todayArrivals as $arrival)
                                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $arrival->guest->full_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $arrival->roomType->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $arrival->nights }} nuit(s)</p>
                                    </div>
                                    <div class="text-right">
                                        @if($arrival->room)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Chambre {{ $arrival->room->room_number }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                En attente d'assignation
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Aucune arrivée prévue aujourd'hui</p>
                    @endif
                </div>
            </div>

            <!-- Départs du jour -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Départs du Jour ({{ $todayDepartures->count() }})</h3>
                </div>
                <div class="p-6">
                    @if($todayDepartures->count() > 0)
                        <div class="space-y-4">
                            @foreach($todayDepartures as $departure)
                                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $departure->guest->full_name }}</p>
                                        <p class="text-sm text-gray-500">Chambre {{ $departure->room->room_number }}</p>
                                        <p class="text-sm text-gray-500">{{ number_format($departure->total_amount, 0, ',', ' ') }} FCFA</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('reservations.show', $departure) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                            Check-out
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Aucun départ prévu aujourd'hui</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Réservations récentes -->
        <div class="mt-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Réservations Récentes</h3>
                        <a href="{{ route('reservations.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type de Chambre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentReservations as $reservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $reservation->guest->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $reservation->guest->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reservation->roomType->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reservation->check_in_date->format('d/m/Y') }} - {{ $reservation->check_out_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
    </div>
</x-app-layout>
