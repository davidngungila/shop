@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-gray-600 mt-1">
                {{ $now->format('l, F d, Y') }} - {{ $now->format('h:i A') }} EAT
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('pos.index') }}" class="px-4 py-2 rounded-lg flex items-center space-x-2" style="background-color: #009245; color: white;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Sale</span>
            </a>
        </div>
    </div>

    <!-- Main KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Today's Sales -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Today's Sales</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                        TZS {{ number_format($todaySales ?? 0, 0) }}
                    </p>
                    <div class="flex items-center mt-2">
                        @php
                            $growth = $salesGrowth ?? 0;
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            {{ number_format(abs($growth), 1) }}%
                        </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs yesterday</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- This Month Sales -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">This Month Sales</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">
                        TZS {{ number_format($thisMonthSales ?? 0, 0) }}
                    </p>
                    <div class="flex items-center mt-2">
                        @php
                            $mGrowth = $monthGrowth ?? 0;
                            $mGrowth = is_numeric($mGrowth) && is_finite($mGrowth) ? $mGrowth : 0;
                        @endphp
                        @if($mGrowth >= 0)
                        <span class="text-xs text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            {{ number_format(abs($mGrowth), 1) }}%
                        </span>
                        @else
                        <span class="text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                            </svg>
                            {{ number_format(abs($mGrowth), 1) }}%
                        </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Products</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ $totalProducts ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-red-600 font-semibold">{{ $lowStockCount ?? 0 }}</span> low stock
                        @if($outOfStockCount > 0)
                        | <span class="text-red-600 font-semibold">{{ $outOfStockCount }}</span> out of stock
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Customers</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ $totalCustomers ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-2">
                        <span class="text-green-600 font-semibold">+{{ $newCustomersToday ?? 0 }}</span> today
                        | <span class="text-blue-600 font-semibold">{{ $activeCustomers ?? 0 }}</span> active
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Today's Orders -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Today's Orders</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $todayOrders ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $pendingOrders ?? 0 }} pending</p>
                </div>
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Profit -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Profit (30 days)</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">
                        TZS {{ number_format($profit ?? 0, 0) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($profitMargin ?? 0, 1) }}% margin</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Stock Value -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Stock Value</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">
                        TZS {{ number_format($totalStockValue ?? 0, 0) }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activeProducts ?? 0 }} active items</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Quotations -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-600">Pending Quotations</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">{{ $pendingQuotations ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        TZS {{ number_format($quotationValue ?? 0, 0) }} approved
                    </p>
                </div>
                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Sales Trend (Last 7 Days)</h2>
                <select id="chartPeriod" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="7">Last 7 Days</option>
                    <option value="30">Last 30 Days</option>
                    <option value="6">Last 6 Months</option>
                </select>
            </div>
            <div style="position: relative; height: 300px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Methods</h2>
            <div class="space-y-3">
                @forelse($paymentMethods ?? [] as $method)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-700 capitalize">{{ $method->payment_method ?? 'N/A' }}</span>
                        <span class="text-sm font-semibold text-gray-900">TZS {{ number_format($method->total, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $percentage = ($totalSales > 0 && isset($method->total)) ? min(100, max(0, ($method->total / $totalSales) * 100)) : 0;
                        @endphp
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $method->count }} transactions</p>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No payment data</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Products and Customers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Selling Products (30 Days)</h2>
            <div class="space-y-3">
                @forelse($topProducts ?? [] as $index => $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-purple-600">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ $product->total_quantity }} sold</p>
                        <p class="text-xs text-gray-500">TZS {{ number_format($product->total_revenue, 0) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No sales data available</p>
                @endforelse
            </div>
        </div>

        <!-- Top Customers -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top Customers (30 Days)</h2>
            <div class="space-y-3">
                @forelse($topCustomers ?? [] as $index => $customer)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-bold text-green-600">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $customer->name }}</p>
                            <p class="text-xs text-gray-500">{{ $customer->sales_count ?? 0 }} purchases</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">
                            TZS {{ number_format($customer->sales_sum_total ?? 0, 0) }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-500 text-center py-4">No customer data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Sales Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Recent Sales</h2>
            <a href="{{ route('sales.index') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentSales ?? [] as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $sale->invoice_number ?? $sale->id }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $sale->customer->name ?? 'Walk-in' }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $sale->created_at->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                {{ $sale->payment_method ?? 'cash' }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                            TZS {{ number_format($sale->total, 0) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No recent sales</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const dailySalesData = @json($dailySales ?? []);
        const ctx = document.getElementById('salesChart');
        
        if (!ctx) {
            return;
        }

        // Ensure we have data, if not create empty array
        let labels = [];
        let salesData = [];
        let ordersData = [];

        if (dailySalesData && dailySalesData.length > 0) {
            labels = dailySalesData.map(item => {
                if (!item.date) return '';
                try {
                    const date = new Date(item.date);
                    if (isNaN(date.getTime())) return '';
                    return date.toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' });
                } catch (e) {
                    return '';
                }
            }).filter(label => label !== '');

            salesData = dailySalesData.map(item => {
                const value = parseFloat(item.total || item.total || 0);
                return isNaN(value) ? 0 : value;
            });

            ordersData = dailySalesData.map(item => {
                const value = parseInt(item.count || item.count || 0);
                return isNaN(value) ? 0 : value;
            });
        } else {
            // Create default data for last 7 days with zeros
            const today = new Date();
            for (let i = 6; i >= 0; i--) {
                const date = new Date(today);
                date.setDate(date.getDate() - i);
                labels.push(date.toLocaleDateString('en-US', { weekday: 'short', day: 'numeric', month: 'short' }));
                salesData.push(0);
                ordersData.push(0);
            }
        }

        // Check if Chart is available
        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded');
            ctx.parentElement.innerHTML = '<p class="text-gray-500 text-center py-8">Chart library loading...</p>';
            return;
        }

        try {
            const salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sales (TZS)',
                        data: salesData,
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y',
                        borderWidth: 2,
                    }, {
                        label: 'Orders',
                        data: ordersData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    if (context.datasetIndex === 0) {
                                        const value = context.parsed.y || 0;
                                        return 'Sales: TZS ' + value.toLocaleString('en-US');
                                    } else {
                                        const value = context.parsed.y || 0;
                                        return 'Orders: ' + value;
                                    }
                                }
                            }
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
                                    if (value >= 1000000) {
                                        return 'TZS ' + (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return 'TZS ' + (value / 1000).toFixed(0) + 'K';
                                    }
                                    return 'TZS ' + value.toLocaleString('en-US');
                                },
                                stepSize: 100000
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
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
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating chart:', error);
            ctx.parentElement.innerHTML = '<p class="text-gray-500 text-center py-8">Unable to load chart. Please refresh the page.</p>';
        }
    });
</script>
@endsection
@endsection
