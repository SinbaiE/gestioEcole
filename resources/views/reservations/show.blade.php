<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Réservation {{ $reservation->reservation_number }}
            </h2>
            <div class="flex space-x-2">
                @if($reservation->status == 'confirmed')
                    <form method="POST" action="{{ route('reservations.check-in', $reservation) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Check-in
                        </button>
                    </form>
                @endif
                @if($reservation->status == 'checked_in')
                    <form method="POST" action="{{ route('reservations.check-out', $reservation) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
                            Check-out
                        </button>
                    </form>
                @endif
                @if(in_array($reservation->status, ['pending', 'confirmed']))
                    <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Annuler
                        </button>
                    </form>
                @endif
                <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Détails de la réservation -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Détails de la Réservation</h3>
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClasses[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $reservation->status)) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro de réservation</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->reservation_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date d'arrivée</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->check_in_date->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date de départ</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->check_out_date->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de nuits</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->nights }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de personnes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->adults }} adulte(s), {{ $reservation->children }} enfant(s)</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de chambre</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->roomType->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Chambre assignée</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($reservation->room)
                                        Chambre {{ $reservation->room->room_number }} (Étage {{ $reservation->room->floor }})
                                    @else
                                        <span class="text-yellow-600">Non assignée</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Source</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($reservation->source) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut de paiement</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($reservation->payment_status == 'paid') bg-green-100 text-green-800
                                        @elseif($reservation->payment_status == 'partial') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($reservation->payment_status) }}
                                    </span>
                                </dd>
                            </div>
                            @if($reservation->checked_in_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Check-in effectué</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->checked_in_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @endif
                            @if($reservation->checked_out_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Check-out effectué</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->checked_out_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @endif
                        </dl>

                        @if($reservation->special_requests)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Demandes spéciales</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->special_requests }}</dd>
                        </div>
                        @endif

                        @if($reservation->notes)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Notes internes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $reservation->notes }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Factures associées -->
                @if($reservation->invoices->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Factures</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reservation->invoices as $invoice)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $invoice->invoice_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ number_format($invoice->total_amount, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $invoice->status_color }}-100 text-{{ $invoice->status_color }}-800">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Informations client -->
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations Client</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-lg font-medium text-white">
                                        {{ strtoupper(substr($reservation->guest->first_name, 0, 1) . substr($reservation->guest->last_name, 0, 1)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">{{ $reservation->guest->full_name }}</h4>
                                @php
                                    $guestTypeClasses = [
                                        'vip' => 'bg-purple-100 text-purple-800',
                                        'corporate' => 'bg-blue-100 text-blue-800',
                                        'group' => 'bg-green-100 text-green-800',
                                        'individual' => 'bg-gray-100 text-gray-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $guestTypeClasses[$reservation->guest->guest_type] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($reservation->guest->guest_type) }}
                                </span>
                            </div>
                        </div>

                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->guest->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->guest->phone }}</dd>
                            </div>
                            @if($reservation->guest->nationality)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nationalité</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $reservation->guest->nationality }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Points de fidélité</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($reservation->guest->loyalty_points) }} points</dd>
                            </div>
                        </dl>

                        <div class="mt-6">
                            <a href="{{ route('guests.show', $reservation->guest) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Voir le profil complet
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Résumé financier -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Résumé Financier</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Tarif par nuit</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($reservation->room_rate, 0, ',', ' ') }} FCFA</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Nombre de nuits</dt>
                                <dd class="text-sm text-gray-900">{{ $reservation->nights }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 pt-4">
                                <dt class="text-base font-medium text-gray-900">Total</dt>
                                <dd class="text-base font-medium text-gray-900">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Montant payé</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($reservation->paid_amount, 0, ',', ' ') }} FCFA</dd>
                            </div>
                            @if($reservation->remaining_balance > 0)
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-red-600">Solde restant</dt>
                                <dd class="text-sm font-medium text-red-600">{{ number_format($reservation->remaining_balance, 0, ',', ' ') }} FCFA</dd>
                            </div>
                            @endif
                        </dl>

                        @if($reservation->remaining_balance > 0)
                        <div class="mt-6">
                            <a href="{{ route('invoices.create', ['reservation_id' => $reservation->id]) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Créer une facture
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
