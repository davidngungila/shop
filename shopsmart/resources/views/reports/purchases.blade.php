@extends('layouts.app')

@section('title', 'Purchase Reports')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Purchase Reports</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Track purchase orders and supplier analytics</p>
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
        <form method="GET" action="{{ route('reports.purchases') }}" class="space-y-3 sm:space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <input type="date" name="date_from" id="date_from" value="{{ $dateFrom ?? request('date_from', now()->startOfMonth()->toDateString()) }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <input type="date" name="date_to" id="date_to" value="{{ $dateTo ?? request('date_to', now()->toDateString()) }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                
                <select name="supplier_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers ?? [] as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>

                <select name="status" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">Apply Filters</button>
                <a href="{{ route('reports.purchases') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <!-- Total Purchases -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Purchases</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalPurchases ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($totalOrders ?? 0) }} orders</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Order -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Average Order</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($averageOrder ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Per transaction</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Purchases -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Today's Purchases</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($todayPurchases ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Today</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">This Month</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($thisMonthPurchases ?? 0, 0) }}</p>
                    @if(isset($monthGrowth))
                    <div class="flex items-center mt-2">
                        @if($monthGrowth >= 0)
                        <span class="text-xs text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            {{ number_format(abs($monthGrowth), 1) }}%
                        </span>
                        @else
                        <span class="text-xs text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6"></path>
                            </svg>
                            {{ number_format(abs($monthGrowth), 1) }}%
                        </span>
                        @endif
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                    @endif
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Daily Purchases Trend Chart -->
        @if(isset($dailyPurchases) && $dailyPurchases->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Daily Purchases Trend</h2>
            <div class="relative" style="height: 300px;">
                <canvas id="dailyPurchasesChart"></canvas>
            </div>
        </div>
        @endif

        <!-- Monthly Purchases Trend Chart -->
        @if(isset($monthlyPurchases) && $monthlyPurchases->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Monthly Purchases Trend (Last 12 Months)</h2>
            <div class="relative" style="height: 300px;">
                <canvas id="monthlyPurchasesChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    <!-- Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Top Suppliers -->
        @if(isset($purchasesBySupplier) && $purchasesBySupplier->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Top Suppliers</h2>
            <div class="space-y-3">
                @foreach($purchasesBySupplier as $index => $supplier)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $supplier->supplier->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $supplier->count }} orders</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">TZS {{ number_format($supplier->total, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Products Purchased -->
        @if(isset($topProducts) && $topProducts->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Top Products Purchased</h2>
            <div class="space-y-3">
                @foreach($topProducts as $index => $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $product->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($product->total_quantity ?? 0) }} units</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">TZS {{ number_format($product->total_cost ?? 0, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Status Breakdown -->
    @if(isset($statusBreakdown) && $statusBreakdown->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Status Breakdown</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($statusBreakdown as $status)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <p class="text-sm text-gray-600 capitalize mb-2">{{ $status->status ?? 'N/A' }}</p>
                <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($status->total ?? 0, 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $status->count ?? 0 }} orders</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Purchases Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Purchases List</h2>
            <span class="text-xs sm:text-sm text-gray-500">{{ $purchases->total() ?? 0 }} total purchases</span>
        </div>
        
        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-gray-200">
            @forelse($purchases ?? [] as $purchase)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <div class="text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</div>
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'ordered' => 'bg-blue-100 text-blue-800',
                                    'partial' => 'bg-orange-100 text-orange-800',
                                ];
                                $color = $statusColors[$purchase->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $color }} capitalize">
                                {{ $purchase->status }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $purchase->purchase_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-base font-bold text-gray-900">TZS {{ number_format($purchase->total, 0) }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-gray-500">Supplier:</span>
                        <div class="font-medium text-gray-900 mt-0.5">{{ $purchase->supplier->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Items:</span>
                        <div class="font-medium text-gray-900 mt-0.5">{{ $purchase->items->count() ?? 0 }} items</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No purchases found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your date range or filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto -mx-3 sm:-mx-4 md:mx-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases ?? [] as $purchase)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-900 font-medium">{{ $purchase->supplier->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'ordered' => 'bg-blue-100 text-blue-800',
                                    'partial' => 'bg-orange-100 text-orange-800',
                                ];
                                $color = $statusColors[$purchase->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                                {{ $purchase->status }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-900">{{ $purchase->purchase_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-gray-900">TZS {{ number_format($purchase->total, 0) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No purchases found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your date range or filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($purchases) && $purchases->hasPages())
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-t border-gray-200">
            <div class="overflow-x-auto">
                {{ $purchases->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

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
        // Daily Purchases Chart
        const dailyPurchasesData = @json($dailyPurchases ?? []);
        const dailyCtx = document.getElementById('dailyPurchasesChart');
        
        if (dailyCtx && dailyPurchasesData && dailyPurchasesData.length > 0 && typeof Chart !== 'undefined') {
            try {
                const labels = dailyPurchasesData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                });
                const totals = dailyPurchasesData.map(item => parseFloat(item.total || 0));

                new Chart(dailyCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Purchases (TZS)',
                            data: totals,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                                        return 'Purchases: TZS ' + context.parsed.y.toLocaleString('en-US');
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
                console.error('Error creating daily purchases chart:', error);
            }
        }

        // Monthly Purchases Chart
        const monthlyPurchasesData = @json($monthlyPurchases ?? []);
        const monthlyCtx = document.getElementById('monthlyPurchasesChart');
        
        if (monthlyCtx && monthlyPurchasesData && monthlyPurchasesData.length > 0 && typeof Chart !== 'undefined') {
            try {
                const labels = monthlyPurchasesData.map(item => {
                    const [year, month] = item.month.split('-');
                    const date = new Date(year, month - 1);
                    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
                });
                const totals = monthlyPurchasesData.map(item => parseFloat(item.total || 0));

                new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Purchases (TZS)',
                            data: totals,
                            backgroundColor: 'rgba(147, 51, 234, 0.8)',
                            borderColor: 'rgb(147, 51, 234)',
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
                                        return 'Purchases: TZS ' + context.parsed.y.toLocaleString('en-US');
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
                console.error('Error creating monthly purchases chart:', error);
            }
        }
    });
</script>
@endsection
