<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details th, .invoice-details td {
            padding: 8px;
            text-align: left;
        }
        .line-items {
            margin-bottom: 20px;
        }
        .line-items table {
            width: 100%;
            border-collapse: collapse;
        }
        .line-items th, .line-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .line-items th {
            background-color: #f2f2f2;
        }
        .totals {
            text-align: right;
        }
        .totals table {
            width: 300px;
            float: right;
            border-collapse: collapse;
        }
        .totals th, .totals td {
            padding: 8px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Invoice</h1>
        </div>
        <div class="invoice-details">
            <table>
                <tr>
                    <td>
                        <strong>Invoice To:</strong><br>
                        {{ $invoice->guest->full_name }}<br>
                        {{ $invoice->guest->email }}<br>
                        {{ $invoice->guest->phone }}
                    </td>
                    <td style="text-align: right;">
                        <strong>Invoice Date:</strong> {{ $invoice->created_at->format('F d, Y') }}<br>
                        <strong>Due Date:</strong> {{ $invoice->due_date->format('F d, Y') }}<br>
                        <strong>Invoice #:</strong> {{ $invoice->invoice_number }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="line-items">
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->line_items as $item)
                        <tr>
                            <td>{{ $item['description'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['unit_price'], 2) }}</td>
                            <td>{{ number_format($item['quantity'] * $item['unit_price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="totals">
            <table>
                <tr>
                    <th>Subtotal</th>
                    <td>{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <th>Tax</th>
                    <td>{{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Discount</th>
                    <td>{{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
