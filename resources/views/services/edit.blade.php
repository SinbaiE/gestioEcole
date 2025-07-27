<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Modifier Service - {{ $service->name }}
            </h2>
            <a href="{{ route('services.show', $service) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <form method="POST" action="{{ route('services.update', $service) }}" class="p-6">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du service *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ex: Massage relaxant, Transfert aéroport">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Description détaillée du service">{{ old('description', $service->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Catégorie *</label>
                        <select name="category" id="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="spa" {{ old('category', $service->category) == 'spa' ? 'selected' : '' }}>Spa & Bien-être</option>
                            <option value="restaurant" {{ old('category', $service->category) == 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                            <option value="bar" {{ old('category', $service->category) == 'bar' ? 'selected' : '' }}>Bar</option>
                            <option value="laundry" {{ old('category', $service->category) == 'laundry' ? 'selected' : '' }}>Blanchisserie</option>
                            <option value="transport" {{ old('category', $service->category) == 'transport' ? 'selected' : '' }}>Transport</option>
                            <option value="business_center" {{ old('category', $service->category) == 'business_center' ? 'selected' : '' }}>Centre d'affaires</option>
                            <option value="fitness" {{ old('category', $service->category) == 'fitness' ? 'selected' : '' }}>Fitness</option>
                            <option value="room_service" {{ old('category', $service->category) == 'room_service' ? 'selected' : '' }}>Room Service</option>
                            <option value="other" {{ old('category', $service->category) == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Prix (FCFA) *</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $service->price) }}" min="0" step="500" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pricing_type" class="block text-sm font-medium text-gray-700">Type de tarification *</label>
                            <select name="pricing_type" id="pricing_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="fixed" {{ old('pricing_type', $service->pricing_type) == 'fixed' ? 'selected' : '' }}>Prix fixe</option>
                                <option value="per_hour" {{ old('pricing_type', $service->pricing_type) == 'per_hour' ? 'selected' : '' }}>Par heure</option>
                                <option value="per_day" {{ old('pricing_type', $service->pricing_type) == 'per_day' ? 'selected' : '' }}>Par jour</option>
                                <option value="per_person" {{ old('pricing_type', $service->pricing_type) == 'per_person' ? 'selected' : '' }}>Par personne</option>
                            </select>
                            @error('pricing_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="max_capacity" class="block text-sm font-medium text-gray-700">Capacité maximale</label>
                        <input type="number" name="max_capacity" id="max_capacity" value="{{ old('max_capacity', $service->max_capacity) }}" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Laisser vide si illimitée">
                        @error('max_capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Nombre maximum de personnes pouvant utiliser ce service simultanément</p>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Service actif
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('services.show', $service) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
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
