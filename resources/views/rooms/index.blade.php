<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Chambres') }}
            </h2>
            <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nouvelle Chambre
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtres -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('rooms.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Numéro de chambre..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupée</option>
                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="cleaning" {{ request('status') == 'cleaning' ? 'selected' : '' }}>Nettoyage</option>
                            <option value="out_of_order" {{ request('status') == 'out_of_order' ? 'selected' : '' }}>Hors service</option>
                        </select>
                    </div>
                    <div>
                        <label for="room_type" class="block text-sm font-medium text-gray-700">Type de chambre</label>
                        <select name="room_type" id="room_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tous les types</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" {{ request('room_type') == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des chambres -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chambre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ménage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rooms as $room)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $room->room_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $room->roomType->name }}</div>
                                    <div class="text-sm text-gray-500">{{ number_format($room->roomType->base_price, 0, ',', ' ') }} FCFA/nuit</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('rooms.show', $room) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        <a href="{{ route('rooms.edit', $room) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Aucune chambre trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
