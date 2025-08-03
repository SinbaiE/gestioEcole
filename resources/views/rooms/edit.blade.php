<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier Chambre {{ $room->room_number }}
            </h2>
            <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <form method="POST" action="{{ route('rooms.update', $room) }}" class="p-6" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div>
                        <label for="room_type_id" class="block text-sm font-medium text-gray-700">Type de chambre *</label>
                        <select name="room_type_id" id="room_type_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner un type</option>
                            @foreach($roomTypes as $roomType)
                                <option value="{{ $roomType->id }}" {{ old('room_type_id', $room->room_type_id) == $roomType->id ? 'selected' : '' }}>
                                    {{ $roomType->name }} - {{ number_format($roomType->base_price, 0, ',', ' ') }} FCFA/nuit
                                </option>
                            @endforeach
                        </select>
                        @error('room_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="room_number" class="block text-sm font-medium text-gray-700">Numéro de chambre *</label>
                        <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $room->room_number) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: 101, A12, Suite-1">
                        @error('room_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="floor" class="block text-sm font-medium text-gray-700">Étage *</label>
                        <input type="text" name="floor" id="floor" value="{{ old('floor', $room->floor) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: 1, RDC, Mezzanine">
                        @error('floor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut *</label>
                        <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupée</option>
                            <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="cleaning" {{ old('status', $room->status) == 'cleaning' ? 'selected' : '' }}>Nettoyage</option>
                            <option value="out_of_order" {{ old('status', $room->status) == 'out_of_order' ? 'selected' : '' }}>Hors service</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="housekeeping_status" class="block text-sm font-medium text-gray-700">Statut ménage</label>
                        <select name="housekeeping_status" id="housekeeping_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="clean" {{ old('housekeeping_status', $room->housekeeping_status) == 'clean' ? 'selected' : '' }}>Propre</option>
                            <option value="dirty" {{ old('housekeeping_status', $room->housekeeping_status) == 'dirty' ? 'selected' : '' }}>Sale</option>
                            <option value="inspected" {{ old('housekeeping_status', $room->housekeeping_status) == 'inspected' ? 'selected' : '' }}>Inspectée</option>
                            <option value="maintenance" {{ old('housekeeping_status', $room->housekeeping_status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('housekeeping_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Notes sur la chambre, équipements spéciaux, etc.">{{ old('notes', $room->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Images actuelles</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($room->images as $image)
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="Room image" class="h-24 w-full object-cover rounded-md">
                                    <div class="absolute top-0 right-0">
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700">Ajouter de nouvelles images</label>
                        <input type="file" name="images[]" id="images" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $room->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Chambre active
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
