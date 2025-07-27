<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestion des Factures') }}
            </h2>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Nouvelle Facture
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtres -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Rechercher</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nom du client..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Tous les statuts</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Envoyée</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payée</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>En retard</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
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

        <!-- Liste des factures -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Réservation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($invoices as $invoice)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</div>
                                    <div class="text-sm text-gray-500">{{ $invoice->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->guest->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $invoice->guest->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $invoice->reservation->reservation_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($invoice->total_amount, 0, ',', ' ') }} FCFA</div>
                                    @if($invoice->discount_amount > 0)
                                        <div class="text-sm text-green-600">Remise: -{{ number_format($invoice->discount_amount, 0, ',', ' ') }} FCFA</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $invoice->due_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $invoiceStatusClasses = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'sent' => 'bg-blue-100 text-blue-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'overdue' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $invoiceStatusClasses[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-900">Voir</a>
                                        @if($invoice->status == 'draft')
                                            <a href="{{ route('invoices.edit', $invoice) }}" class="text-indigo-600 hover:text-indigo-900">Modifier</a>
                                        @endif
                                        <a href="{{ route('invoices.download-pdf', $invoice) }}" class="text-green-600 hover:text-green-900">PDF</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Aucune facture trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
