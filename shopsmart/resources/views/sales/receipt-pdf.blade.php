<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->invoice_number ?? $sale->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            padding: 15px;
            font-size: 12px;
            line-height: 1.5;
            color: #000;
            background: #fff;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #009245;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #009245;
            text-transform: uppercase;
        }
        .logo {
            max-width: 60px;
            max-height: 60px;
            margin: 0 auto 8px;
            display: block;
        }
        .company-info {
            font-size: 10px;
            margin: 3px 0;
            color: #666;
        }
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            text-transform: uppercase;
            color: #333;
        }
        .receipt-info {
            margin: 10px 0;
            font-size: 11px;
            background: #f9fafb;
            padding: 10px;
            border-radius: 5px;
        }
        .receipt-info strong {
            display: inline-block;
            width: 100px;
            color: #666;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11px;
        }
        .items-table thead {
            background: #009245;
            color: white;
        }
        .items-table th {
            text-align: left;
            padding: 8px 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .items-table td {
            padding: 6px 5px;
            border-bottom: 1px solid #e5e7eb;
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
            font-weight: 500;
        }
        .summary {
            margin-top: 15px;
            border-top: 2px solid #009245;
            padding-top: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
            font-size: 12px;
        }
        .summary-row.total {
            border-top: 2px solid #009245;
            border-bottom: 2px solid #009245;
            padding: 12px 0;
            margin: 12px 0;
            font-size: 16px;
            font-weight: bold;
            color: #009245;
        }
        .payment-info {
            margin-top: 15px;
            padding: 12px;
            background: #f3f4f6;
            border-radius: 5px;
            font-size: 11px;
        }
        .payment-info strong {
            color: #666;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #666;
        }
        .barcode {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            background: #f9fafb;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            font-weight: bold;
        }
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 10mm;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        @php
            $logoPath = null;
            if ($companyLogo && file_exists(public_path('storage/' . $companyLogo))) {
                $logoPath = public_path('storage/' . $companyLogo);
            } elseif (file_exists(public_path('logo.png'))) {
                $logoPath = public_path('logo.png');
            }
            
            // Convert image to base64 for PDF compatibility
            if ($logoPath && file_exists($logoPath)) {
                $imageData = file_get_contents($logoPath);
                $imageInfo = getimagesize($logoPath);
                $mimeType = $imageInfo['mime'];
                $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
            }
        @endphp
        @if(isset($base64Image))
            <img src="{{ $base64Image }}" alt="{{ $companyName }}" class="logo">
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
        @if($sale->customer->email)
        <strong>Email:</strong> {{ $sale->customer->email }}<br>
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
                <th class="item-price">Amount (TZS)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td class="item-name">
                    {{ $item->product->name ?? 'Item' }}
                    @if($item->product && $item->product->sku)
                    <br><span style="font-size: 9px; color: #666;">SKU: {{ $item->product->sku }}</span>
                    @endif
                </td>
                <td class="item-qty">{{ number_format($item->quantity) }}</td>
                <td class="item-price">{{ number_format($item->total, 0) }}</td>
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
            <span>Tax (VAT):</span>
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
    </div>

    <div class="barcode">
        Invoice: {{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    <div class="footer">
        <div style="font-weight: bold; margin-bottom: 5px;">Thank you for your business!</div>
        <div>Please keep this receipt for your records</div>
        <div style="margin-top: 8px;">
            Generated: {{ now()->setTimezone('Africa/Dar_es_Salaam')->format('d/m/Y h:i A') }} EAT
        </div>
        <div style="margin-top: 5px; font-size: 8px;">
            This is a computer-generated receipt
        </div>
    </div>
</body>
</html>

