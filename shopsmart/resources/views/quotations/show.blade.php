@extends('layouts.app')

@section('title', 'Quotation Details')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Quotation #{{ $quotation->quotation_number }}</h1>
            <p class="text-gray-600 mt-1">Created on {{ $quotation->quotation_date->format('F d, Y') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('quotations.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Back</a>
            @if($quotation->status !== 'converted')
            <a href="{{ route('quotations.edit', $quotation) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Edit</a>
            @endif
            <a href="{{ route('quotations.pdf', $quotation) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Download PDF</a>
            @if($quotation->customer && $quotation->customer->email)
            <form action="{{ route('quotations.send-email', $quotation) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Send Email</button>
            </form>
            @endif
            @if($quotation->canBeConverted())
            <form action="{{ route('quotations.convert-to-sale', $quotation) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700" onclick="return confirm('Convert this quotation to a sale? This will reduce inventory.')">Convert to Sale</button>
            </form>
            @endif
        </div>
    </div>

    <!-- Status Alert -->
    @if($quotation->isExpired() && $quotation->status !== 'converted')
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span class="text-red-800 font-medium">This quotation has expired on {{ $quotation->expiry_date->format('F d, Y') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer & Quotation Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Customer</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ $quotation->customer->name ?? 'Walk-in Customer' }}</p>
                        @if($quotation->customer)
                        <p class="text-sm text-gray-600 mt-1">{{ $quotation->customer->email ?? '' }}</p>
                        <p class="text-sm text-gray-600">{{ $quotation->customer->phone ?? '' }}</p>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Quotation Details</h3>
                        <p class="text-sm text-gray-900"><span class="font-medium">Date:</span> {{ $quotation->quotation_date->format('F d, Y') }}</p>
                        @if($quotation->expiry_date)
                        <p class="text-sm text-gray-900"><span class="font-medium">Expiry:</span> {{ $quotation->expiry_date->format('F d, Y') }}</p>
                        @endif
                        <p class="text-sm text-gray-900"><span class="font-medium">Status:</span> 
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ 
                                $quotation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                ($quotation->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                ($quotation->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                ($quotation->status === 'converted' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')))
                            }}">
                                {{ ucfirst($quotation->status) }}
                            </span>
                        </p>
                        @if($quotation->is_sent)
                        <p class="text-sm text-gray-500 mt-1">Sent on {{ $quotation->sent_at->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Items</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                                <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($quotation->items as $item)
                            <tr>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                    @if($item->description)
                                    <div class="text-xs text-gray-500">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($item->discount, 2) }}</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">${{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Terms & Notes -->
            @if($quotation->terms_conditions || $quotation->notes)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @if($quotation->terms_conditions)
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Terms & Conditions</h3>
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $quotation->terms_conditions }}</p>
                </div>
                @endif
                @if($quotation->notes)
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Internal Notes</h3>
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $quotation->notes }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Summary -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold">${{ number_format($quotation->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Discount:</span>
                        <span class="font-semibold">${{ number_format($quotation->discount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax (10%):</span>
                        <span class="font-semibold">${{ number_format($quotation->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-3">
                        <span>Total:</span>
                        <span>${{ number_format($quotation->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Update -->
            @if($quotation->status !== 'converted')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h2>
                <form action="{{ route('quotations.update-status', $quotation) }}" method="POST">
                    @csrf
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] mb-3">
                        <option value="pending" {{ $quotation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $quotation->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $quotation->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg" style="background-color: #009245; color: white;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">Update Status</button>
                </form>
            </div>
            @endif

            @if($quotation->converted_to_sale_id)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h3 class="text-sm font-medium text-blue-900 mb-2">Converted to Sale</h3>
                <a href="{{ route('sales.show', $quotation->converted_to_sale_id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                    View Sale →
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

