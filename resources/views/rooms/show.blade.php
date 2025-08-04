<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chambre {{ $room->room_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Modifier
                </a>
                <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Retour
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations de la chambre -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Détails de la Chambre</h3>
                            <div class="flex space-x-2">
                                @php
                                    $roomStatusClasses = [
                                        'available' => 'bg-green-100 text-green-800',
                                        'occupied' => 'bg-blue-100 text-blue-800',
                                        'maintenance' => 'bg-yellow-100 text-yellow-800',
                                        'cleaning' => 'bg-purple-100 text-purple-800',
                                        'out_of_order' => 'bg-red-100 text-red-800'
                                    ];
                                    $housekeepingClasses = [
                                        'clean' => 'bg-green-100 text-green-800',
                                        'dirty' => 'bg-red-100 text-red-800',
                                        'inspected' => 'bg-blue-100 text-blue-800',
                                        'maintenance' => 'bg-yellow-100 text-yellow-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $roomStatusClasses[$room->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $room->status)) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $housekeepingClasses[$room->housekeeping_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($room->housekeeping_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Numéro de chambre</dt>
                                <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $room->room_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Étage</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room->floor }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type de chambre</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room->roomType->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tarif de base</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($room->roomType->base_price, 0, ',', ' ') }} FCFA/nuit</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Capacité maximale</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room->roomType->max_occupancy }} personne(s)</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de lits</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room->roomType->bed_count }} lit(s) {{ $room->roomType->bed_type }}</dd>
                            </div>
                            @if($room->roomType->room_size)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Superficie</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room->roomType->room_size }} m²</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $room->is_active ? 'Active' : 'Inactive' }}
                                </dd>
                            </div>
                        </dl>

                        @if($room->roomType->amenities && count($room->roomType->amenities) > 0)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Équipements</dt>
                            <div class="flex flex-wrap gap-2">
                                @foreach($room->roomType->amenities as $amenity)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $amenity)) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($room->roomType->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $room->roomType->description }}</dd>
                        </div>
                        @endif

                        @if($room->notes)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $room->notes }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Images de la chambre -->
                @if($room->images->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Images</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($room->images as $image)
                                <div>
                                    <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $image->path) }}" alt="Image de la chambre" class="h-40 w-full object-cover rounded-md hover:opacity-75 transition">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Historique des réservations -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Historique des Réservations</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réservation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($room->reservations()->with('guest')->latest()->limit(10)->get() as $reservation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $reservation->reservation_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $reservation->guest->full_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $reservation->check_in_date->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $reservation->check_out_date->format('d/m/Y') }}</div>
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
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Aucune réservation trouvée
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="space-y-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Actions Rapides</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <form method="POST" action="{{ route('rooms.update-status', $room) }}">
                            @csrf
                            @method('PATCH')
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Changer le statut</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Disponible</option>
                                        <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Occupée</option>
                                        <option value="maintenance" {{ $room->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="cleaning" {{ $room->status == 'cleaning' ? 'selected' : '' }}>Nettoyage</option>
                                        <option value="out_of_order" {{ $room->status == 'out_of_order' ? 'selected' : '' }}>Hors service</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="housekeeping_status" class="block text-sm font-medium text-gray-700">Statut ménage</label>
                                    <select name="housekeeping_status" id="housekeeping_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="clean" {{ $room->housekeeping_status == 'clean' ? 'selected' : '' }}>Propre</option>
                                        <option value="dirty" {{ $room->housekeeping_status == 'dirty' ? 'selected' : '' }}>Sale</option>
                                        <option value="inspected" {{ $room->housekeeping_status == 'inspected' ? 'selected' : '' }}>Inspectée</option>
                                        <option value="maintenance" {{ $room->housekeeping_status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Statistiques</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Total réservations</dt>
                                <dd class="text-sm text-gray-900">{{ $room->reservations()->count() }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Réservations ce mois</dt>
                                <dd class="text-sm text-gray-900">{{ $room->reservations()->whereMonth('check_in_date', now()->month)->count() }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Revenus générés</dt>
                                <dd class="text-sm text-gray-900">{{ number_format($room->reservations()->where('status', 'checked_out')->sum('total_amount'), 0, ',', ' ') }} FCFA</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Taux d'occupation</dt>
                                <dd class="text-sm text-gray-900">
                                    @php
                                        $totalDays = now()->diffInDays(now()->startOfYear());
                                        $occupiedDays = $room->reservations()->where('status', 'checked_out')->sum('nights');
                                        $occupancyRate = $totalDays > 0 ? ($occupiedDays / $totalDays) * 100 : 0;
                                    @endphp
                                    {{ number_format($occupancyRate, 1) }}%
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
