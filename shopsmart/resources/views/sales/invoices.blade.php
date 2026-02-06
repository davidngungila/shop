@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Sales Invoices</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Advanced invoice management and analytics</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button onclick="exportToPDF()" class="px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="hidden sm:inline">Export PDF</span>
                <span class="sm:hidden">PDF</span>
            </button>
            <button onclick="exportToExcel()" class="px-3 sm:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="hidden sm:inline">Export Excel</span>
                <span class="sm:hidden">Excel</span>
            </button>
        </div>
    </div>

    <!-- Advanced Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <!-- Total Invoices -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Invoices</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalInvoices ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: #e6f5ed;">
                    <svg class="w-6 h-6" style="color: #009245;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Revenue</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                        TZS {{ number_format($totalAmount ?? 0, 0) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">All time</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">This Month</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                        TZS {{ number_format($thisMonthAmount ?? 0, 0) }}
                    </p>
                    <div class="flex items-center mt-2">
                        @php
                            $growth = $monthGrowth ?? 0;
                            $growth = is_numeric($growth) && is_finite($growth) ? $growth : 0;
                        @endphp
                        @if($growth >= 0)
                        <span class="text-xs text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        @else
                        <span class="text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Today's Sales</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($todayInvoices ?? 0) }} invoices</p>
                    <p class="text-xs text-gray-500 mt-1">TZS {{ number_format($todayAmount ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtered Statistics -->
    @if(request()->hasAny(['date_from', 'date_to', 'payment_method', 'customer_id', 'search']))
    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg border border-purple-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtered Results</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <p class="text-sm text-gray-600">Filtered Invoices</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($filteredInvoices ?? 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Filtered Amount</p>
                <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($filteredAmount ?? 0, 0) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Average Invoice</p>
                <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($averageInvoice ?? 0, 0) }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Filters & Search</h2>
            <div class="flex gap-1.5 sm:gap-2 flex-wrap">
                <button onclick="setDateRange('today')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">Today</button>
                <button onclick="setDateRange('week')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Week</button>
                <button onclick="setDateRange('month')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Month</button>
                <button onclick="setDateRange('year')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Year</button>
            </div>
        </div>
        <form method="GET" action="{{ route('sales.invoices') }}" class="space-y-3 sm:space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice #, customer..." class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                
                <select name="payment_method" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Payment Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>

                <select name="customer_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Customers</option>
                    @foreach($customers ?? [] as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="date_from" id="date_from" value="{{ request('date_from', $dateFrom ?? '') }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to', $dateTo ?? '') }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">Apply Filters</button>
                <a href="{{ route('sales.invoices') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">Clear All</a>
            </div>
        </form>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Sales Trend Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Sales Trend (Last 30 Days)</h2>
            <div class="relative" style="height: 250px;">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods Chart -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Payment Methods Distribution</h2>
            <div class="relative" style="height: 250px;">
                <canvas id="paymentMethodsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Top Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Top Customers</h2>
            @if(isset($topCustomers) && $topCustomers->count() > 0)
            <div class="space-y-3">
                @foreach($topCustomers as $index => $customerData)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $customerData->customer->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $customerData->invoice_count }} invoices</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">TZS {{ number_format($customerData->total_spent, 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-8">No customer data available</p>
            @endif
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Top Selling Products</h2>
            @if(isset($topProducts) && $topProducts->count() > 0)
            <div class="space-y-3">
                @foreach($topProducts as $index => $productData)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $productData->product->name ?? 'Unknown Product' }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($productData->total_quantity) }} units sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">TZS {{ number_format($productData->total_revenue, 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-center py-8">No product data available</p>
            @endif
        </div>
    </div>

    <!-- Payment Methods Breakdown -->
    @if(isset($paymentMethods) && $paymentMethods->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Payment Methods Breakdown</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($paymentMethods as $method)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <p class="text-sm text-gray-600 capitalize mb-2">{{ str_replace('_', ' ', $method->payment_method ?? 'N/A') }}</p>
                <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($method->total ?? 0, 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $method->count ?? 0 }} invoices</p>
                @php
                    $totalFiltered = $paymentMethods->sum('total');
                    $percentage = $totalFiltered > 0 ? ($method->total / $totalFiltered) * 100 : 0;
                @endphp
                <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($percentage, 1) }}%</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Invoices Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Invoices List</h2>
            <span class="text-xs sm:text-sm text-gray-500">{{ $sales->total() ?? 0 }} total invoices</span>
        </div>
        <div class="overflow-x-auto -mx-3 sm:-mx-4 md:mx-0">
            <!-- Mobile Card View -->
            <div class="block md:hidden divide-y divide-gray-200">
                @forelse($sales ?? [] as $sale)
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-gray-900">#{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900">TZS {{ number_format($sale->total, 0) }}</div>
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                ];
                                $color = $statusColors[$sale->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize mt-1 inline-block">
                                {{ $sale->status }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                        <div>
                            <span class="font-medium">Customer:</span>
                            <div class="mt-1">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                        </div>
                        <div>
                            <span class="font-medium">Items:</span>
                            <div class="mt-1">{{ $sale->items->count() ?? 0 }} items</div>
                        </div>
                        <div>
                            <span class="font-medium">Payment:</span>
                            <div class="mt-1 capitalize">{{ str_replace('_', ' ', $sale->payment_method ?? 'cash') }}</div>
                        </div>
                        @if($sale->discount > 0)
                        <div>
                            <span class="font-medium">Discount:</span>
                            <div class="mt-1">TZS {{ number_format($sale->discount, 0) }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-end space-x-2 pt-2 border-t border-gray-200">
                        <a href="{{ route('sales.show', $sale) }}" class="px-3 py-1.5 text-xs bg-purple-600 text-white rounded hover:bg-purple-700 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>View</span>
                        </a>
                        <a href="{{ route('sales.print', $sale) }}" target="_blank" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            <span>Receipt</span>
                        </a>
                        <a href="{{ route('sales.pdf', $sale) }}" target="_blank" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700 flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Invoice</span>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or create a new sale.</p>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <table class="min-w-full divide-y divide-gray-200 hidden md:table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales ?? [] as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">#{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-900 font-medium">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                            @if($sale->customer)
                            <div class="text-xs text-gray-500">{{ $sale->customer->email ?? $sale->customer->phone ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            @php
                                $itemCount = $sale->items ? $sale->items->count() : 0;
                                $itemQuantity = $sale->items ? $sale->items->sum('quantity') : 0;
                            @endphp
                            <div class="text-xs sm:text-sm text-gray-900">{{ $itemCount }} items</div>
                            <div class="text-xs text-gray-500">{{ number_format($itemQuantity) }} units</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                {{ str_replace('_', ' ', $sale->payment_method ?? 'cash') }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-900">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('h:i A') }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-gray-900">TZS {{ number_format($sale->total, 0) }}</div>
                            @if($sale->discount > 0)
                            <div class="text-xs text-gray-500">Discount: TZS {{ number_format($sale->discount, 0) }}</div>
                            @endif
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-xs sm:text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('sales.show', $sale) }}" class="text-purple-600 hover:text-purple-900" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('sales.print', $sale) }}" target="_blank" class="text-green-600 hover:text-green-900" title="Print Receipt">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('sales.pdf', $sale) }}" target="_blank" class="text-red-600 hover:text-red-900" title="Download PDF">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or create a new sale.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($sales) && $sales->hasPages())
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-t border-gray-200">
            <div class="overflow-x-auto">
                {{ $sales->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Sales Trend Chart
    @if(isset($dailySales) && $dailySales->count() > 0)
    const salesTrendCtx = document.getElementById('salesTrendChart');
    if (salesTrendCtx) {
        const salesData = @json($dailySales);
        const labels = salesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const totals = salesData.map(item => parseFloat(item.total || 0));
        const counts = salesData.map(item => parseInt(item.count || 0));

        new Chart(salesTrendCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (TZS)',
                    data: totals,
                    borderColor: 'rgb(147, 51, 234)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y',
                }, {
                    label: 'Invoice Count',
                    data: counts,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'TZS ' + new Intl.NumberFormat().format(value);
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        beginAtZero: true,
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }
    @endif

    // Payment Methods Chart
    @if(isset($paymentMethods) && $paymentMethods->count() > 0)
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart');
    if (paymentMethodsCtx) {
        const methodsData = @json($paymentMethods);
        const methodLabels = methodsData.map(item => {
            return (item.payment_method || 'Unknown').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        });
        const methodTotals = methodsData.map(item => parseFloat(item.total || 0));
        const colors = ['rgb(147, 51, 234)', 'rgb(59, 130, 246)', 'rgb(34, 197, 94)', 'rgb(234, 179, 8)', 'rgb(239, 68, 68)'];

        new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: methodLabels,
                datasets: [{
                    data: methodTotals,
                    backgroundColor: colors.slice(0, methodLabels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'TZS ' + new Intl.NumberFormat().format(context.parsed);
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
    @endif

    // Date Range Presets
    function setDateRange(range) {
        const today = new Date();
        let from, to;
        
        switch(range) {
            case 'today':
                from = to = today.toISOString().split('T')[0];
                break;
            case 'week':
                const weekStart = new Date(today);
                weekStart.setDate(today.getDate() - today.getDay());
                from = weekStart.toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
            case 'month':
                from = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
            case 'year':
                from = new Date(today.getFullYear(), 0, 1).toISOString().split('T')[0];
                to = today.toISOString().split('T')[0];
                break;
        }
        
        document.getElementById('date_from').value = from;
        document.getElementById('date_to').value = to;
    }

    // Export Functions
    function exportToPDF() {
        window.open('{{ route("sales.invoices") }}?export=pdf&' + new URLSearchParams(window.location.search).toString(), '_blank');
    }

    function exportToExcel() {
        window.open('{{ route("sales.invoices") }}?export=excel&' + new URLSearchParams(window.location.search).toString(), '_blank');
    }

    // Functions removed - using direct links now
</script>
@endsection
