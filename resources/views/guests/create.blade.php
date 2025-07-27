<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nouveau Client') }}
            </h2>
            <a href="{{ route('guests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <form method="POST" action="{{ route('guests.store') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations personnelles -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informations personnelles</h3>
                        
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom *</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Nom *</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone *</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date de naissance</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Genre</label>
                            <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionner</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculin</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Féminin</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations complémentaires -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informations complémentaires</h3>
                        
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700">Nationalité</label>
                            <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="id_type" class="block text-sm font-medium text-gray-700">Type de pièce d'identité</label>
                            <select name="id_type" id="id_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionner</option>
                                <option value="passport" {{ old('id_type') == 'passport' ? 'selected' : '' }}>Passeport</option>
                                <option value="national_id" {{ old('id_type') == 'national_id' ? 'selected' : '' }}>Carte d'identité nationale</option>
                                <option value="driving_license" {{ old('id_type') == 'driving_license' ? 'selected' : '' }}>Permis de conduire</option>
                            </select>
                            @error('id_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700">Numéro de pièce</label>
                            <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                            <textarea name="address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Pays</label>
                            <input type="text" name="country" id="country" value="{{ old('country', 'Cameroun') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="guest_type" class="block text-sm font-medium text-gray-700">Type de client *</label>
                            <select name="guest_type" id="guest_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="individual" {{ old('guest_type') == 'individual' ? 'selected' : '' }}>Individuel</option>
                                <option value="corporate" {{ old('guest_type') == 'corporate' ? 'selected' : '' }}>Entreprise</option>
                                <option value="group" {{ old('guest_type') == 'group' ? 'selected' : '' }}>Groupe</option>
                                <option value="vip" {{ old('guest_type') == 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                            @error('guest_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('guests.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Créer le client
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
