<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->invoice_number ?? $sale->id }}</title>
    @php
        $companyName = \App\Models\Setting::get('company_name', 'ShopSmart');
        $companyAddress = \App\Models\Setting::get('company_address', 'Dar es Salaam, Tanzania');
        $companyPhone = \App\Models\Setting::get('company_phone', '+255 XXX XXX XXX');
        $companyEmail = \App\Models\Setting::get('company_email', '');
        $companyLogo = \App\Models\Setting::get('company_logo', '');
    @endphp
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 5mm;
            }
        }
        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .company-info {
            font-size: 10px;
            margin: 2px 0;
        }
        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            text-transform: uppercase;
        }
        .receipt-info {
            margin: 8px 0;
            font-size: 11px;
        }
        .receipt-info strong {
            display: inline-block;
            width: 80px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 11px;
        }
        .items-table thead {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
        }
        .items-table th {
            text-align: left;
            padding: 5px 0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .items-table td {
            padding: 4px 0;
            border-bottom: 1px dotted #ccc;
        }
        .items-table .item-name {
            width: 50%;
        }
        .items-table .item-qty {
            text-align: center;
            width: 15%;
        }
        .items-table .item-price {
            text-align: right;
            width: 35%;
        }
        .summary {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 8px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
            font-size: 11px;
        }
        .summary-row.total {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 8px 0;
            margin: 8px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .payment-info {
            margin-top: 10px;
            padding-top: 8px;
            border-top: 1px dashed #000;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #000;
            font-size: 9px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 10px;
        }
        .no-print {
            display: none;
        }
        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }
            .no-print {
                display: block;
                text-align: center;
                margin-bottom: 20px;
            }
            .no-print button {
                padding: 10px 20px;
                background: #009245;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                margin: 5px;
            }
            .no-print button:hover {
                background: #007a38;
            }
            .logo {
                max-width: 60px;
                max-height: 60px;
                margin: 0 auto 5px;
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Receipt</button>
        <button onclick="window.close()">✕ Close</button>
    </div>

    <div class="receipt-header">
        @if($companyLogo && file_exists(public_path('storage/' . $companyLogo)))
            <img src="{{ asset('storage/' . $companyLogo) }}" alt="{{ $companyName }}" class="logo">
        @elseif(file_exists(public_path('logo.png')))
            <img src="{{ asset('logo.png') }}" alt="{{ $companyName }}" class="logo">
        @endif
        <div class="company-name">{{ strtoupper($companyName) }}</div>
        <div class="company-info">Point of Sale System</div>
        @if($companyAddress)
        <div class="company-info">{{ $companyAddress }}</div>
        @endif
        @if($companyPhone)
        <div class="company-info">Tel: {{ $companyPhone }}</div>
        @endif
        @if($companyEmail)
        <div class="company-info">Email: {{ $companyEmail }}</div>
        @endif
    </div>

    <div class="receipt-title">SALES RECEIPT</div>

    <div class="receipt-info">
        <strong>Invoice #:</strong> {{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}<br>
        <strong>Date:</strong> {{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('d/m/Y') }}<br>
        <strong>Time:</strong> {{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('h:i A') }}<br>
        @if($sale->customer)
        <strong>Customer:</strong> {{ $sale->customer->name }}<br>
        @if($sale->customer->phone)
        <strong>Phone:</strong> {{ $sale->customer->phone }}<br>
        @endif
        @else
        <strong>Customer:</strong> Walk-in Customer<br>
        @endif
        <strong>Cashier:</strong> {{ $sale->user->name ?? 'System' }}<br>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th class="item-name">Item</th>
                <th class="item-qty">Qty</th>
                <th class="item-price">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td class="item-name">
                    {{ $item->product->name ?? 'Item' }}
                    @if($item->product && $item->product->sku)
                    <br><span style="font-size: 9px;">SKU: {{ $item->product->sku }}</span>
                    @endif
                </td>
                <td class="item-qty">{{ number_format($item->quantity) }}</td>
                <td class="item-price">TZS {{ number_format($item->total, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-row">
            <span>Subtotal:</span>
            <span>TZS {{ number_format($sale->subtotal, 0) }}</span>
        </div>
        @if($sale->discount > 0)
        <div class="summary-row">
            <span>Discount:</span>
            <span>- TZS {{ number_format($sale->discount, 0) }}</span>
        </div>
        @endif
        @if($sale->tax > 0)
        <div class="summary-row">
            <span>Tax:</span>
            <span>TZS {{ number_format($sale->tax, 0) }}</span>
        </div>
        @endif
        <div class="summary-row total">
            <span>TOTAL:</span>
            <span>TZS {{ number_format($sale->total, 0) }}</span>
        </div>
    </div>

    <div class="payment-info">
        <strong>Payment Method:</strong> {{ strtoupper(str_replace('_', ' ', $sale->payment_method ?? 'cash')) }}<br>
        <strong>Amount Paid:</strong> TZS {{ number_format($sale->total, 0) }}<br>
        @if($sale->payment_method != 'cash' && $sale->total > $sale->subtotal)
        <strong>Change:</strong> TZS {{ number_format($sale->total - $sale->subtotal, 0) }}<br>
        @endif
    </div>

    <div class="barcode">
        {{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    <div class="footer">
        <div>Thank you for your business!</div>
        <div style="margin-top: 5px;">Please keep this receipt</div>
        <div style="margin-top: 5px; font-size: 8px;">
            Generated: {{ now()->setTimezone('Africa/Dar_es_Salaam')->format('d/m/Y h:i A') }}
        </div>
    </div>

    <script>
        // Auto-print when page loads (optional)
        window.onload = function() {
            // Uncomment the line below to auto-print
            // window.print();
        };
    </script>
</body>
</html>

