@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Purchase Order #{{ $purchase->purchase_number ?? str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Created on {{ $purchase->purchase_date->setTimezone('Africa/Dar_es_Salaam')->format('F d, Y') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('purchases.index') }}" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>
            <a href="{{ route('purchases.edit', $purchase) }}" class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span class="hidden sm:inline">Edit</span>
            </a>
            <button onclick="window.print()" class="px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="hidden sm:inline">Print</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Status & Overdue Alert -->
    @if($isOverdue ?? false)
    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span class="text-red-800 font-medium">This purchase order is overdue. Expected delivery was {{ $purchase->expected_delivery_date->setTimezone('Africa/Dar_es_Salaam')->format('F d, Y') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Supplier & Purchase Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Supplier & Order Information</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <h3 class="text-xs sm:text-sm font-medium text-gray-500 mb-2">Supplier</h3>
                        <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $purchase->supplier->name ?? 'N/A' }}</p>
                        @if($purchase->supplier)
                        <div class="mt-2 space-y-1 text-xs sm:text-sm text-gray-600">
                            @if($purchase->supplier->email)
                            <p>📧 {{ $purchase->supplier->email }}</p>
                            @endif
                            @if($purchase->supplier->phone)
                            <p>📞 {{ $purchase->supplier->phone }}</p>
                            @endif
                            @if($purchase->supplier->address)
                            <p>📍 {{ $purchase->supplier->address }}</p>
                            @endif
                        </div>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-xs sm:text-sm font-medium text-gray-500 mb-2">Order Details</h3>
                        <div class="space-y-2 text-xs sm:text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Purchase Date:</span>
                                <span class="font-medium text-gray-900">{{ $purchase->purchase_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</span>
                            </div>
                            @if($purchase->expected_delivery_date)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Expected Delivery:</span>
                                <span class="font-medium {{ $isOverdue ? 'text-red-600' : ($deliveryDays !== null && $deliveryDays < 3 ? 'text-yellow-600' : 'text-gray-900') }}">
                                    {{ $purchase->expected_delivery_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}
                                    @if($deliveryDays !== null)
                                        @if($deliveryDays < 0)
                                            <span class="text-red-600">({{ abs($deliveryDays) }} days overdue)</span>
                                        @elseif($deliveryDays == 0)
                                            <span class="text-yellow-600">(Today)</span>
                                        @else
                                            <span class="text-gray-500">({{ $deliveryDays }} days)</span>
                                        @endif
                                    @endif
                                </span>
                            </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'ordered' => 'bg-blue-100 text-blue-800',
                                        'partial' => 'bg-orange-100 text-orange-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'received' => 'bg-green-100 text-green-800',
                                        'paid' => 'bg-purple-100 text-purple-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$purchase->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                                    {{ $purchase->status }}
                                </span>
                            </div>
                            @if($purchase->warehouse)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Warehouse:</span>
                                <span class="font-medium text-gray-900">{{ $purchase->warehouse->name }}</span>
                            </div>
                            @endif
                            @if($purchase->user)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created By:</span>
                                <span class="font-medium text-gray-900">{{ $purchase->user->name ?? 'N/A' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span>Purchase Items ({{ $purchase->items->count() }})</span>
                </h2>
                <div class="overflow-x-auto -mx-4 sm:-mx-6 md:mx-0">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                                <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($purchase->items as $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $item->product->name ?? 'N/A' }}</div>
                                    @if($item->product && $item->product->category)
                                    <div class="text-xs text-gray-500">{{ $item->product->category->name }}</div>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm text-gray-500 font-mono">{{ $item->product->sku ?? 'N/A' }}</div>
                                </td>
                                <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                    <div class="text-xs sm:text-sm font-semibold text-gray-900">{{ number_format($item->quantity) }}</div>
                                </td>
                                <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                    <div class="text-xs sm:text-sm text-gray-900">TZS {{ number_format($item->unit_price, 0) }}</div>
                                </td>
                                <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                                    <div class="text-xs sm:text-sm font-semibold text-gray-900">TZS {{ number_format($item->total, 0) }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No items found</h3>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            @if($purchase->notes)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Notes</span>
                </h2>
                <p class="text-sm sm:text-base text-gray-700 whitespace-pre-wrap">{{ $purchase->notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-4 sm:space-y-6">
            <!-- Summary Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 sticky top-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold text-gray-900">TZS {{ number_format($purchase->subtotal, 0) }}</span>
                    </div>
                    @if($purchase->discount > 0)
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-gray-600">Discount:</span>
                        <span class="font-semibold text-red-600">-TZS {{ number_format($purchase->discount, 0) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xs sm:text-sm">
                        <span class="text-gray-600">Tax:</span>
                        <span class="font-semibold text-gray-900">TZS {{ number_format($purchase->tax, 0) }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <div class="flex justify-between text-base sm:text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-blue-600">TZS {{ number_format($purchase->total, 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Payment Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs sm:text-sm">
                            <span class="text-gray-600">Paid Amount:</span>
                            <span class="font-semibold text-green-600">TZS {{ number_format($purchase->paid_amount, 0) }}</span>
                        </div>
                        <div class="flex justify-between text-xs sm:text-sm">
                            <span class="text-gray-600">Due Amount:</span>
                            <span class="font-semibold text-red-600">TZS {{ number_format($purchase->due_amount, 0) }}</span>
                        </div>
                        
                        <!-- Payment Progress Bar -->
                        @if($purchase->total > 0)
                        <div class="mt-4">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Payment Progress</span>
                                <span>{{ number_format($paymentProgress ?? 0, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ min(100, $paymentProgress ?? 0) }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-6 pt-6 border-t border-gray-200 space-y-2">
                    @if($purchase->status !== 'completed' && $purchase->status !== 'cancelled')
                    <form action="{{ route('purchases.update', $purchase) }}" method="POST" class="space-y-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium transition-colors">
                            Mark as Completed
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('suppliers.show', $purchase->supplier) }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium text-center transition-colors">
                        View Supplier
                    </a>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Order Timeline</h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-1.5"></div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm font-medium text-gray-900">Order Created</p>
                            <p class="text-xs text-gray-500">{{ $purchase->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @if($purchase->expected_delivery_date)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 {{ $isOverdue ? 'bg-red-500' : 'bg-yellow-500' }} rounded-full mt-1.5"></div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm font-medium text-gray-900">Expected Delivery</p>
                            <p class="text-xs text-gray-500">{{ $purchase->expected_delivery_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</p>
                        </div>
                    </div>
                    @endif
                    @if($purchase->updated_at != $purchase->created_at)
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-1.5"></div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm font-medium text-gray-900">Last Updated</p>
                            <p class="text-xs text-gray-500">{{ $purchase->updated_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

