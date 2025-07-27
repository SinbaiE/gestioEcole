<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $guest->full_name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('guests.edit', $guest) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Modifier
                </a>
                <a href="{{ route('guests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations du client -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 h-20 w-20">
                                <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-2xl font-medium text-white">
                                        {{ strtoupper(substr($guest->first_name, 0, 1) . substr($guest->last_name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">{{ $guest->full_name }}</h3>
                                @php
                                    $guestTypeClasses = [
                                        'vip' => 'bg-purple-100 text-purple-800',
                                        'corporate' => 'bg-blue-100 text-blue-800',
                                        'group' => 'bg-green-100 text-green-800',
                                        'individual' => 'bg-gray-100 text-gray-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $guestTypeClasses[$guest->guest_type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($guest->guest_type) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $guest->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $guest->phone }}</dd>
                            </div>
                            @if($guest->nationality)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nationalité</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $guest->nationality }}</dd>
                            </div>
                            @endif
                            @if($guest->date_of_birth)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de naissance</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $guest->date_of_birth->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                            @if($guest->address)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $guest->address }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Points de fidélité</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($guest->loyalty_points) }} points</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de séjours</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $guest->total_stays }} séjour(s)</dd>
                            </div>
                            @if($guest->last_stay)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dernier séjour</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $guest->last_stay->format('d/m/Y') }}</dd>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des réservations -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Historique des Réservations</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réservation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chambre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($guest->reservations as $reservation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $reservation->reservation_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $reservation->roomType->name }}</div>
                                            @if($reservation->room)
                                                <div class="text-sm text-gray-500">Chambre {{ $reservation->room->room_number }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $reservation->check_in_date->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation->check_out_date->format('d/m/Y') }} ({{ $reservation->nights }} nuits)</div>
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
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Aucune réservation trouvée
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
