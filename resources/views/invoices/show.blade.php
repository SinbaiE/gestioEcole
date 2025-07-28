<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoice Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Invoice') }} #{{ $invoice->invoice_number }}</h3>
                        <div class="flex items-center">
                            <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <i class="fas fa-file-pdf mr-2"></i>
                                {{ __('Download PDF') }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Invoice To') }}</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $invoice->guest->full_name }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $invoice->guest->email }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $invoice->guest->phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ __('Invoice Date') }}</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $invoice->created_at->format('F d, Y') }}</p>
                                <p class="text-sm font-medium text-gray-500 mt-4">{{ __('Due Date') }}</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $invoice->due_date->format('F d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="text-lg font-medium text-gray-900">{{ __('Line Items') }}</h4>
                        <div class="mt-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Description') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Quantity') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Unit Price') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Total') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($invoice->line_items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item['description'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item['quantity'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($item['unit_price'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($item['quantity'] * $item['unit_price'], 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <div class="w-1/3">
                            <div class="flex justify-between">
                                <p class="text-sm font-medium text-gray-500">{{ __('Subtotal') }}</p>
                                <p class="text-sm text-gray-900">{{ number_format($invoice->subtotal, 2) }}</p>
                            </div>
                            <div class="flex justify-between mt-2">
                                <p class="text-sm font-medium text-gray-500">{{ __('Tax') }}</p>
                                <p class="text-sm text-gray-900">{{ number_format($invoice->tax_amount, 2) }}</p>
                            </div>
                            <div class="flex justify-between mt-2">
                                <p class="text-sm font-medium text-gray-500">{{ __('Discount') }}</p>
                                <p class="text-sm text-gray-900">{{ number_format($invoice->discount_amount, 2) }}</p>
                            </div>
                            <div class="flex justify-between mt-4 pt-4 border-t border-gray-200">
                                <p class="text-lg font-medium text-gray-900">{{ __('Total') }}</p>
                                <p class="text-lg font-medium text-gray-900">{{ number_format($invoice->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
