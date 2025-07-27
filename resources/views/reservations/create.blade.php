<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nouvelle Réservation') }}
            </h2>
            <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Retour
            </a>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <form method="POST" action="{{ route('reservations.store') }}" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informations client -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informations Client</h3>
                        
                        <div>
                            <label for="guest_id" class="block text-sm font-medium text-gray-700">Client *</label>
                            <select name="guest_id" id="guest_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionner un client</option>
                                @foreach($guests as $guest)
                                    <option value="{{ $guest->id }}" {{ old('guest_id') == $guest->id ? 'selected' : '' }}>
                                        {{ $guest->full_name }} - {{ $guest->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('guest_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="room_type_id" class="block text-sm font-medium text-gray-700">Type de chambre *</label>
                            <select name="room_type_id" id="room_type_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionner un type</option>
                                @foreach($roomTypes as $roomType)
                                    <option value="{{ $roomType->id }}" data-price="{{ $roomType->base_price }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                        {{ $roomType->name }} - {{ number_format($roomType->base_price, 0, ',', ' ') }} FCFA/nuit
                                    </option>
                                @endforeach
                            </select>
                            @error('room_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="adults" class="block text-sm font-medium text-gray-700">Nombre d'adultes *</label>
                            <input type="number" name="adults" id="adults" value="{{ old('adults', 1) }}" min="1" max="10" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('adults')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="children" class="block text-sm font-medium text-gray-700">Nombre d'enfants</label>
                            <input type="number" name="children" id="children" value="{{ old('children', 0) }}" min="0" max="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('children')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informations séjour -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Informations Séjour</h3>
                        
                        <div>
                            <label for="check_in_date" class="block text-sm font-medium text-gray-700">Date d'arrivée *</label>
                            <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date') }}" min="{{ date('Y-m-d') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('check_in_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="check_out_date" class="block text-sm font-medium text-gray-700">Date de départ *</label>
                            <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('check_out_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="room_rate" class="block text-sm font-medium text-gray-700">Tarif par nuit (FCFA) *</label>
                            <input type="number" name="room_rate" id="room_rate" value="{{ old('room_rate') }}" min="0" step="1000" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('room_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Nombre de nuits:</span>
                                <span id="nights-display" class="text-sm text-gray-900">0</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-lg font-medium text-gray-900">Total estimé:</span>
                                <span id="total-display" class="text-lg font-bold text-blue-600">0 FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Demandes spéciales -->
                <div class="mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Demandes Spéciales</h3>
                    
                    <div>
                        <label for="special_requests" class="block text-sm font-medium text-gray-700">Demandes spéciales</label>
                        <textarea name="special_requests" id="special_requests" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Lit bébé, vue mer, étage élevé...">{{ old('special_requests') }}</textarea>
                        @error('special_requests')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes internes</label>
                        <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Notes pour le personnel...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('reservations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Annuler
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Créer la réservation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roomTypeSelect = document.getElementById('room_type_id');
            const roomRateInput = document.getElementById('room_rate');
            const checkInInput = document.getElementById('check_in_date');
            const checkOutInput = document.getElementById('check_out_date');
            const nightsDisplay = document.getElementById('nights-display');
            const totalDisplay = document.getElementById('total-display');

            // Auto-fill room rate when room type is selected
            roomTypeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price) {
                    roomRateInput.value = price;
                    calculateTotal();
                }
            });

            // Calculate total when dates or rate change
            [checkInInput, checkOutInput, roomRateInput].forEach(input => {
                input.addEventListener('change', calculateTotal);
            });

            function calculateTotal() {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);
                const rate = parseFloat(roomRateInput.value) || 0;

                if (checkIn && checkOut && checkOut > checkIn) {
                    const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
                    const total = nights * rate;

                    nightsDisplay.textContent = nights;
                    totalDisplay.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
                } else {
                    nightsDisplay.textContent = '0';
                    totalDisplay.textContent = '0 FCFA';
                }
            }

            // Set minimum checkout date
            checkInInput.addEventListener('change', function() {
                const checkInDate = new Date(this.value);
                checkInDate.setDate(checkInDate.getDate() + 1);
                checkOutInput.min = checkInDate.toISOString().split('T')[0];
            });
        });
    </script>
</x-app-layout>
