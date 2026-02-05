@extends('layouts.app')

@section('title', 'Inventory Reports')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Inventory Reports</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Stock levels and product analytics</p>
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
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Filters & Search</h2>
        </div>
        <form method="GET" action="{{ route('reports.inventory') }}" class="space-y-3 sm:space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search product, SKU, barcode..." class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                
                <select name="category_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Categories</option>
                    @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>

                <select name="warehouse_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Warehouses</option>
                    @foreach($warehouses ?? [] as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ request('warehouse_id') == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                    @endforeach
                </select>

                <select name="stock_status" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Stock Status</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                </select>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">Apply Filters</button>
                <a href="{{ route('reports.inventory') }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Total Products</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalProducts ?? 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ number_format($activeProducts ?? 0) }} active</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Stock Value -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Stock Value (Cost)</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalStockValue ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">At cost price</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Potential Profit -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Potential Profit</p>
                    <p class="text-xl sm:text-2xl font-bold text-green-600 mt-2">TZS {{ number_format($potentialProfit ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-500 mt-1">If all sold</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600">Low Stock Items</p>
                    <p class="text-xl sm:text-2xl font-bold text-yellow-600 mt-2">{{ number_format($lowStockCount ?? 0) }}</p>
                    <p class="text-xs text-red-600 mt-1">{{ number_format($outOfStockCount ?? 0) }} out of stock</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Low Stock Products -->
        @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-yellow-200 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-yellow-900">Low Stock Alert</h2>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $lowStockProducts->count() }} items</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($lowStockProducts as $product)
                <div class="flex items-center justify-between p-2 bg-yellow-50 rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">Stock: {{ number_format($product->stock_quantity) }} / Alert: {{ number_format($product->low_stock_alert) }}</p>
                    </div>
                    <span class="text-xs font-semibold text-yellow-700">Low</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Out of Stock Products -->
        @if(isset($outOfStockProducts) && $outOfStockProducts->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-red-200 p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base sm:text-lg font-semibold text-red-900">Out of Stock</h2>
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">{{ $outOfStockProducts->count() }} items</span>
            </div>
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach($outOfStockProducts as $product)
                <div class="flex items-center justify-between p-2 bg-red-50 rounded-lg">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                    </div>
                    <span class="text-xs font-semibold text-red-700">Out</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Analytics Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Products by Category -->
        @if(isset($productsByCategory) && $productsByCategory->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Products by Category</h2>
            <div class="space-y-3">
                @foreach($productsByCategory as $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $category->category->name ?? 'Uncategorized' }}</p>
                        <p class="text-xs text-gray-500">{{ number_format($category->count) }} products</p>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">TZS {{ number_format($category->value ?? 0, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Top Products by Value -->
        @if(isset($topProductsByValue) && $topProductsByValue->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Top Products by Stock Value</h2>
            <div class="space-y-3">
                @foreach($topProductsByValue as $index => $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($product->stock_quantity) }} units</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-gray-900">TZS {{ number_format($product->stock_value ?? 0, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Stock Movements Summary -->
    @if(isset($stockMovements) && $stockMovements->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Stock Movements (Last 30 Days)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            @foreach($stockMovements as $movement)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-600 capitalize mb-2">{{ $movement->type ?? 'N/A' }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($movement->total_quantity ?? 0) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $movement->count ?? 0 }} movements</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Products List</h2>
            <span class="text-xs sm:text-sm text-gray-500">{{ $products->total() ?? 0 }} total products</span>
        </div>
        
        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-gray-200">
            @forelse($products ?? [] as $product)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">SKU: {{ $product->sku ?? 'N/A' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-base font-bold text-gray-900">
                            <span class="{{ $product->stock_quantity <= 0 ? 'text-red-600' : ($product->stock_quantity <= $product->low_stock_alert ? 'text-yellow-600' : 'text-gray-900') }}">
                                {{ number_format($product->stock_quantity) }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">Stock</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-gray-500">Category:</span>
                        <div class="font-medium text-gray-900 mt-0.5">{{ $product->category->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Value:</span>
                        <div class="font-medium text-gray-900 mt-0.5">TZS {{ number_format($product->stock_quantity * $product->cost_price, 0) }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Cost Price:</span>
                        <div class="font-medium text-gray-900 mt-0.5">TZS {{ number_format($product->cost_price, 0) }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Selling Price:</span>
                        <div class="font-medium text-gray-900 mt-0.5">TZS {{ number_format($product->selling_price, 0) }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters.</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto -mx-3 sm:-mx-4 md:mx-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cost Price</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Selling Price</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products ?? [] as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-500">{{ $product->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <span class="text-xs sm:text-sm font-semibold {{ $product->stock_quantity <= 0 ? 'text-red-600' : ($product->stock_quantity <= $product->low_stock_alert ? 'text-yellow-600' : 'text-gray-900') }}">
                                {{ number_format($product->stock_quantity) }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm text-gray-900">TZS {{ number_format($product->cost_price, 0) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm text-gray-900">TZS {{ number_format($product->selling_price, 0) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-gray-900">TZS {{ number_format($product->stock_quantity * $product->cost_price, 0) }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($products) && $products->hasPages())
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-t border-gray-200">
            <div class="overflow-x-auto">
                {{ $products->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@section('scripts')
<script>
    // Export to PDF
    function exportToPDF() {
        window.print();
    }
</script>
@endsection
