@extends('layouts.app')

@section('title', 'Sales Reports')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Sales Reports</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Comprehensive sales analytics and insights</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button onclick="window.print()" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="hidden sm:inline">Print</span>
            </button>
            <button onclick="exportToPDF()" class="px-3 sm:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="hidden sm:inline">Export PDF</span>
                <span class="sm:hidden">PDF</span>
            </button>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Filters & Date Range</h2>
            <div class="flex gap-1.5 sm:gap-2 flex-wrap">
                <button onclick="setDateRange('today')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">Today</button>
                <button onclick="setDateRange('week')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Week</button>
                <button onclick="setDateRange('month')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Month</button>
                <button onclick="setDateRange('year')" class="px-2 sm:px-3 py-1.5 sm:py-1 text-xs bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">This Year</button>
            </div>
        </div>
        <form method="GET" action="{{ route('reports.sales') }}" class="space-y-3 sm:space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <input type="date" name="date_from" id="date_from" value="{{ $dateFrom ?? request('date_from', now()->startOfMonth()->toDateString()) }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <input type="date" name="date_to" id="date_to" value="{{ $dateTo ?? request('date_to', now()->toDateString()) }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                
                <select name="customer_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Customers</option>
                    @foreach($customers ?? [] as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>

                <select name="payment_method" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Payment Methods</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                    <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="mobile_money" {{ request('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">Apply Filters</button>
                <a href="{{ route('reports.sales') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <!-- Total Sales -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg shadow-sm border border-purple-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm text-purple-700 font-medium">Total Sales</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-purple-900 mt-2">TZS {{ number_format($totalSales ?? 0, 0) }}</p>
                    <p class="text-xs text-purple-600 mt-1">{{ number_format($totalOrders ?? 0) }} orders</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Order -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm text-blue-700 font-medium">Average Order</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-900 mt-2">TZS {{ number_format($averageOrder ?? 0, 0) }}</p>
                    <p class="text-xs text-blue-600 mt-1">Per transaction</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm text-green-700 font-medium">Today's Sales</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-900 mt-2">TZS {{ number_format($todaySales ?? 0, 0) }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ number_format($todayOrders ?? 0) }} orders</p>
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg shadow-sm border border-orange-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm text-orange-700 font-medium">This Month</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-orange-900 mt-2">TZS {{ number_format($thisMonthSales ?? 0, 0) }}</p>
                    @if(isset($monthGrowth))
                    <div class="flex items-center mt-2">
                        @if($monthGrowth >= 0)
                        <span class="text-xs text-green-700 flex items-center font-semibold">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            {{ number_format(abs($monthGrowth), 1) }}%
                        </span>
                        @else
                        <span class="text-xs text-red-700 flex items-center font-semibold">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6"></path>
                            </svg>
                            {{ number_format(abs($monthGrowth), 1) }}%
                        </span>
                        @endif
                        <span class="text-xs text-orange-600 ml-2">vs last month</span>
                    </div>
                    @endif
                </div>
                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-orange-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Daily Sales Trend Chart -->
        @if(isset($dailySales) && $dailySales->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daily Sales Trend</h2>
                <span class="text-xs text-gray-500">{{ $dailySales->count() }} days</span>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Monthly Sales Trend Chart -->
        @if(isset($monthlySales) && $monthlySales->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Monthly Sales Trend</h2>
                <span class="text-xs text-gray-500">Last 12 months</span>
            </div>
            <div class="relative" style="height: 300px;">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    <!-- Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Payment Methods Breakdown -->
        @if(isset($salesByPayment) && $salesByPayment->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Sales by Payment Method</h2>
            <div class="space-y-3">
                @foreach($salesByPayment as $method)
                @php
                    $totalFiltered = $salesByPayment->sum('total');
                    $percentage = $totalFiltered > 0 ? ($method->total / $totalFiltered) * 100 : 0;
                @endphp
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700 capitalize">{{ str_replace('_', ' ', $method->payment_method ?? 'N/A') }}</span>
                        <span class="font-semibold text-gray-900">TZS {{ number_format($method->total ?? 0, 0) }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full transition-all" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600 w-12 text-right">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $method->count ?? 0 }} orders</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Customers -->
        @if(isset($topCustomers) && $topCustomers->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Top Customers</h2>
            <div class="space-y-3">
                @foreach($topCustomers as $index => $customer)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-sm flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $customer->customer->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $customer->count }} orders</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 ml-3">TZS {{ number_format($customer->total, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Top Products -->
    @if(isset($topProducts) && $topProducts->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Top Selling Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4">
            @foreach($topProducts as $index => $product)
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow bg-gray-50">
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full mx-auto mb-3">
                    <span class="text-green-600 font-bold text-sm">#{{ $index + 1 }}</span>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 text-center mb-2 line-clamp-2">{{ $product->name ?? 'N/A' }}</h3>
                <div class="text-center space-y-1">
                    <p class="text-xs text-gray-600">Quantity Sold</p>
                    <p class="text-base font-bold text-gray-900">{{ number_format($product->total_quantity ?? 0) }}</p>
                    <p class="text-xs text-gray-600 mt-2">Revenue</p>
                    <p class="text-sm font-semibold text-purple-600">TZS {{ number_format($product->total_revenue ?? 0, 0) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Sales List</h2>
                <div class="flex items-center space-x-4 text-xs sm:text-sm text-gray-600">
                    <span>Showing {{ $sales->firstItem() ?? 0 }}-{{ $sales->lastItem() ?? 0 }} of {{ $sales->total() ?? 0 }}</span>
                </div>
            </div>
        </div>
        
        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-gray-200">
            @forelse($sales ?? [] as $sale)
            <div class="p-4 space-y-3 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-2">
                            <div class="text-sm font-semibold text-gray-900">#{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                {{ str_replace('_', ' ', $sale->payment_method ?? 'cash') }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y h:i A') }}</div>
                    </div>
                    <div class="text-right ml-3">
                        <div class="text-base font-bold text-gray-900">TZS {{ number_format($sale->total, 0) }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-gray-500">Customer:</span>
                        <div class="font-medium text-gray-900 mt-0.5 truncate">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Items:</span>
                        <div class="font-medium text-gray-900 mt-0.5">{{ $sale->items->count() ?? 0 }} items</div>
                    </div>
                </div>
                <div class="flex gap-2 pt-2 border-t border-gray-100">
                    <a href="{{ route('sales.show', $sale) }}" class="flex-1 px-3 py-1.5 text-xs text-center bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">View</a>
                    <a href="{{ route('sales.print', $sale) }}" target="_blank" class="flex-1 px-3 py-1.5 text-xs text-center bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors">Print</a>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No sales found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your date range or filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales ?? [] as $sale)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">#{{ $sale->invoice_number ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $sale->customer->name ?? 'Walk-in Customer' }}</div>
                            @if($sale->customer)
                            <div class="text-xs text-gray-500">{{ $sale->customer->email ?? $sale->customer->phone ?? '' }}</div>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $sale->items->count() ?? 0 }} items</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                {{ str_replace('_', ' ', $sale->payment_method ?? 'cash') }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('h:i A') }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
                            <div class="text-sm font-bold text-gray-900">TZS {{ number_format($sale->total, 0) }}</div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right">
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
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No sales found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your date range or filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($sales) && $sales->hasPages())
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-t border-gray-200 bg-gray-50">
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

    // Export to PDF
    function exportToPDF() {
        window.print();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Daily Sales Chart
        const dailySalesData = @json($dailySales ?? []);
        const dailyCtx = document.getElementById('dailySalesChart');
        
        if (dailyCtx && dailySalesData && dailySalesData.length > 0 && typeof Chart !== 'undefined') {
            try {
                const labels = dailySalesData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                });
                const totals = dailySalesData.map(item => parseFloat(item.total || 0));

                new Chart(dailyCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sales (TZS)',
                            data: totals,
                            borderColor: 'rgb(147, 51, 234)',
                            backgroundColor: 'rgba(147, 51, 234, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Sales: TZS ' + context.parsed.y.toLocaleString('en-US');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'TZS ' + (value / 1000000).toFixed(1) + 'M';
                                        } else if (value >= 1000) {
                                            return 'TZS ' + (value / 1000).toFixed(0) + 'K';
                                        }
                                        return 'TZS ' + value.toLocaleString('en-US');
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error creating daily sales chart:', error);
            }
        }

        // Monthly Sales Chart
        const monthlySalesData = @json($monthlySales ?? []);
        const monthlyCtx = document.getElementById('monthlySalesChart');
        
        if (monthlyCtx && monthlySalesData && monthlySalesData.length > 0 && typeof Chart !== 'undefined') {
            try {
                const labels = monthlySalesData.map(item => {
                    const [year, month] = item.month.split('-');
                    const date = new Date(year, month - 1);
                    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                });
                const totals = monthlySalesData.map(item => parseFloat(item.total || 0));

                new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sales (TZS)',
                            data: totals,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Sales: TZS ' + context.parsed.y.toLocaleString('en-US');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'TZS ' + (value / 1000000).toFixed(1) + 'M';
                                        } else if (value >= 1000) {
                                            return 'TZS ' + (value / 1000).toFixed(0) + 'K';
                                        }
                                        return 'TZS ' + value.toLocaleString('en-US');
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error creating monthly sales chart:', error);
            }
        }
    });
</script>
@endsection
