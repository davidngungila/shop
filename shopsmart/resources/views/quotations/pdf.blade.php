<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quotation->quotation_number }} - {{ $settings['company_name'] ?? 'ShopSmart' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'DM Sans', 'Roboto', Arial, sans-serif; 
            padding: 40px; 
            color: #1f2937; 
            background: #fff;
            font-size: 14px;
            line-height: 1.6;
        }
        .container { max-width: 900px; margin: 0 auto; }
        
        /* Header */
        .header { 
            border-bottom: 4px solid #009245; 
            padding-bottom: 25px; 
            margin-bottom: 35px; 
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-left .logo {
            height: 60px;
            width: auto;
        }
        .header-left h1 { 
            color: #009245; 
            font-size: 32px; 
            font-weight: 700;
            margin-bottom: 8px; 
            letter-spacing: -0.5px;
        }
        .header-left .quotation-number { 
            font-size: 18px; 
            font-weight: 600; 
            color: #6b7280;
            margin-top: 5px;
        }
        .header-right {
            text-align: right;
        }
        .company-info {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.8;
        }
        .company-name {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-expired { background: #e5e7eb; color: #374151; }
        .status-converted { background: #dbeafe; color: #1e40af; }
        
        /* Info Grid */
        .info-grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 40px; 
            margin-bottom: 40px; 
        }
        .info-section {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #009245;
        }
        .info-section h3 { 
            color: #009245; 
            font-size: 11px; 
            text-transform: uppercase; 
            margin-bottom: 12px; 
            font-weight: 700;
            letter-spacing: 1px;
        }
        .info-section p { 
            font-size: 14px; 
            margin: 6px 0; 
            color: #374151;
        }
        .info-section strong {
            color: #1f2937;
            font-weight: 600;
        }
        .info-section .label {
            color: #6b7280;
            font-size: 12px;
            display: inline-block;
            min-width: 100px;
        }
        
        /* Table */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 30px; 
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        table thead {
            background: linear-gradient(135deg, #009245 0%, #007a38 100%);
            color: #fff;
        }
        table th { 
            padding: 14px 12px; 
            text-align: left; 
            font-size: 11px; 
            text-transform: uppercase; 
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        table td { 
            padding: 16px 12px; 
            border-bottom: 1px solid #e5e7eb; 
            font-size: 14px; 
            color: #374151;
        }
        table tbody tr:hover {
            background: #f9fafb;
        }
        table tbody tr:last-child td {
            border-bottom: none;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .product-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .product-details {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }
        .product-sku {
            display: inline-block;
            background: #f3f4f6;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            margin-right: 8px;
        }
        
        /* Summary */
        .summary { 
            margin-top: 30px; 
            max-width: 400px; 
            margin-left: auto; 
        }
        .summary-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 10px 0; 
            font-size: 14px;
        }
        .summary-row .label {
            color: #6b7280;
        }
        .summary-row .value {
            font-weight: 600;
            color: #1f2937;
        }
        .summary-row.total { 
            border-top: 3px solid #009245; 
            margin-top: 15px; 
            padding-top: 20px; 
            font-size: 20px; 
            font-weight: 700; 
        }
        .summary-row.total .value {
            color: #009245;
            font-size: 24px;
        }
        .summary-row.highlight {
            background: #f9fafb;
            padding: 10px 15px;
            border-radius: 6px;
            margin: 5px 0;
        }
        
        /* Additional Sections */
        .section {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 2px solid #e5e7eb;
        }
        .section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #009245;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .section-content {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.8;
            white-space: pre-line;
        }
        .notes-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
        }
        
        /* Footer */
        .footer { 
            margin-top: 60px; 
            padding-top: 25px; 
            border-top: 2px solid #e5e7eb; 
            font-size: 12px; 
            color: #6b7280;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
        }
        .conversion-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }
        .conversion-info strong {
            color: #1e40af;
        }
        
        /* Print Styles */
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
            .header { page-break-inside: avoid; }
            table { page-break-inside: auto; }
            table tr { page-break-inside: avoid; page-break-after: auto; }
            .summary { page-break-inside: avoid; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            body { padding: 20px; }
            .info-grid { grid-template-columns: 1fr; gap: 20px; }
            .header { flex-direction: column; }
            .header-right { text-align: left; margin-top: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <img src="{{ public_path('logo.png') }}" alt="ShopSmart Logo" class="logo" style="max-height: 60px; width: auto;">
                <div>
                    <h1>QUOTATION</h1>
                    <div class="quotation-number">#{{ $quotation->quotation_number }}</div>
                    <span class="status-badge status-{{ $quotation->status }}">
                        {{ ucfirst($quotation->status) }}
                    </span>
                </div>
            </div>
            <div class="header-right">
                <div class="company-name">{{ $settings['company_name'] ?? 'ShopSmart' }}</div>
                <div class="company-info">
                    @if($settings['company_address'] ?? '')
                        <div>{{ $settings['company_address'] }}</div>
                    @endif
                    @if($settings['company_phone'] ?? '')
                        <div>Phone: {{ $settings['company_phone'] }}</div>
                    @endif
                    @if($settings['company_email'] ?? '')
                        <div>Email: {{ $settings['company_email'] }}</div>
                    @endif
                    @if($settings['company_website'] ?? '')
                        <div>Web: {{ $settings['company_website'] }}</div>
                    @endif
                    @if($settings['tax_id'] ?? '')
                        <div style="margin-top: 8px; font-weight: 600;">Tax ID: {{ $settings['tax_id'] }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="info-grid">
            <div class="info-section">
                <h3>Bill To</h3>
                <p style="font-weight: 700; font-size: 16px; color: #1f2937; margin-bottom: 10px;">
                    {{ $quotation->customer->name ?? 'Walk-in Customer' }}
                </p>
                @if($quotation->customer)
                    @if($quotation->customer->email)
                        <p><span class="label">Email:</span> {{ $quotation->customer->email }}</p>
                    @endif
                    @if($quotation->customer->phone)
                        <p><span class="label">Phone:</span> {{ $quotation->customer->phone }}</p>
                    @endif
                    @if($quotation->customer->address)
                        <p><span class="label">Address:</span> {{ $quotation->customer->address }}</p>
                    @endif
                    @if($quotation->customer->tax_id)
                        <p><span class="label">Tax ID:</span> {{ $quotation->customer->tax_id }}</p>
                    @endif
                @else
                    <p style="color: #9ca3af; font-style: italic;">No customer information</p>
                @endif
            </div>
            <div class="info-section">
                <h3>Quotation Details</h3>
                <p><span class="label">Date:</span> <strong>{{ $quotation->quotation_date->format('F d, Y') }}</strong></p>
                <p><span class="label">Time:</span> {{ $quotation->created_at->format('h:i A') }}</p>
                @if($quotation->expiry_date)
                    <p><span class="label">Valid Until:</span> <strong>{{ $quotation->expiry_date->format('F d, Y') }}</strong></p>
                    @php
                        $daysRemaining = now()->diffInDays($quotation->expiry_date, false);
                    @endphp
                    @if($daysRemaining > 0)
                        <p style="color: #059669; font-size: 12px; margin-top: 5px;">
                            ⏱ {{ $daysRemaining }} {{ $daysRemaining == 1 ? 'day' : 'days' }} remaining
                        </p>
                    @elseif($daysRemaining < 0)
                        <p style="color: #dc2626; font-size: 12px; margin-top: 5px;">
                            ⚠ Expired {{ abs($daysRemaining) }} {{ abs($daysRemaining) == 1 ? 'day' : 'days' }} ago
                        </p>
                    @else
                        <p style="color: #f59e0b; font-size: 12px; margin-top: 5px;">
                            ⚠ Expires today
                        </p>
                    @endif
                @endif
                @if($quotation->warehouse)
                    <p><span class="label">Warehouse:</span> {{ $quotation->warehouse->name }}</p>
                @endif
                @if($quotation->user)
                    <p><span class="label">Prepared By:</span> {{ $quotation->user->name }}</p>
                @endif
                @if($quotation->is_sent && $quotation->sent_at)
                    <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                        <span class="label">Sent:</span> {{ $quotation->sent_at->format('M d, Y h:i A') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Product / Description</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-right" style="width: 15%;">Unit Price</th>
                    <th class="text-right" style="width: 15%;">Discount</th>
                    <th class="text-right" style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quotation->items as $index => $item)
                <tr>
                    <td class="text-center" style="color: #9ca3af;">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-name">{{ $item->product->name }}</div>
                        <div class="product-details">
                            @if($item->product->sku)
                                <span class="product-sku">SKU: {{ $item->product->sku }}</span>
                            @endif
                            @if($item->product->category)
                                <span style="color: #6b7280;">{{ $item->product->category->name }}</span>
                            @endif
                        </div>
                        @if($item->description)
                            <div style="margin-top: 6px; font-size: 12px; color: #6b7280; font-style: italic;">
                                {{ $item->description }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center">
                        {{ number_format($item->quantity) }}
                        @if($item->product->unit)
                            <span style="color: #9ca3af; font-size: 11px;">{{ $item->product->unit }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <strong>{{ number_format($item->unit_price, 0) }} TZS</strong>
                    </td>
                    <td class="text-right">
                        @if($item->discount > 0)
                            <span style="color: #dc2626;">-{{ number_format($item->discount, 0) }} TZS</span>
                        @else
                            <span style="color: #9ca3af;">—</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <strong style="color: #009245;">{{ number_format($item->total, 0) }} TZS</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span class="label">Subtotal:</span>
                <span class="value">{{ number_format($quotation->subtotal, 0) }} TZS</span>
            </div>
            @if($quotation->discount > 0)
            <div class="summary-row highlight">
                <span class="label">Total Discount:</span>
                <span class="value" style="color: #dc2626;">-{{ number_format($quotation->discount, 0) }} TZS</span>
            </div>
            @endif
            <div class="summary-row">
                <span class="label">Tax (10%):</span>
                <span class="value">{{ number_format($quotation->tax, 0) }} TZS</span>
            </div>
            <div class="summary-row total">
                <span>Total Amount:</span>
                <span class="value">{{ number_format($quotation->total, 0) }} TZS</span>
            </div>
        </div>

        <!-- Conversion Info -->
        @if($quotation->status === 'converted' && $quotation->sale)
        <div class="conversion-info">
            <strong>✓ This quotation has been converted to a sale.</strong><br>
            <span style="font-size: 12px;">
                Invoice Number: <strong>{{ $quotation->sale->invoice_number }}</strong><br>
                Converted on: {{ $quotation->updated_at->format('F d, Y h:i A') }}
            </span>
        </div>
        @endif

        <!-- Terms & Conditions -->
        @if($quotation->terms_conditions)
        <div class="section">
            <h3>Terms & Conditions</h3>
            <div class="section-content">{{ $quotation->terms_conditions }}</div>
        </div>
        @endif

        <!-- Notes -->
        @if($quotation->notes)
        <div class="section">
            <h3>Additional Notes</h3>
            <div class="notes-box">
                {{ $quotation->notes }}
            </div>
        </div>
        @endif

        <!-- Customer Notes -->
        @if($quotation->customer_notes)
        <div class="section">
            <h3>Customer Notes</h3>
            <div class="section-content">{{ $quotation->customer_notes }}</div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for your business!</strong></p>
            <p>This is a computer-generated quotation. No signature required.</p>
            <p style="margin-top: 15px; font-size: 11px; color: #9ca3af;">
                Generated on {{ now()->format('F d, Y \a\t h:i A') }} | 
                Quotation #{{ $quotation->quotation_number }}
            </p>
        </div>
    </div>
</body>
</html>
