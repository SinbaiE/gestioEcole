<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">
                {{ __('messages.invoices') }}
            </h2>
            <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                New Invoice
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6">
            <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Guest name..." class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                        <option value="">All</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <x-data-table :headers="['Invoice', 'Guest', 'Reservation', 'Amount', 'Due Date', 'Status']" :items="$invoices">
            @forelse($invoices as $invoice)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->invoice_number }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice->created_at->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $invoice->guest->full_name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $invoice->guest->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white">{{ $invoice->reservation->reservation_number }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($invoice->total_amount, 0, ',', ' ') }} FCFA</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                        <a href="{{ route('invoices.show', $invoice) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                        No invoices found.
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </div>
</x-app-layout>
