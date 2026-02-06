@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Product Details</h1>
            <p class="text-gray-600 mt-1">View product information</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                <span>Edit</span>
            </a>
            <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Product Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Product Name</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold">{{ $product->name }}</dd>
                    </div>
                    @if($product->sku)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SKU</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $product->sku }}</dd>
                    </div>
                    @endif
                    @if($product->barcode)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Barcode</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $product->barcode }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->category->name ?? 'Uncategorized' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Unit</dt>
                        <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $product->unit }}</dd>
                    </div>
                    @if($product->description)
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->description }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cost Price</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold">TZS {{ number_format($product->cost_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Selling Price</dt>
                        <dd class="mt-1 text-lg text-gray-900 font-semibold">TZS {{ number_format($product->selling_price, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Profit Margin</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @php
                                $margin = $product->cost_price > 0 ? (($product->selling_price - $product->cost_price) / $product->cost_price) * 100 : 0;
                            @endphp
                            <span class="font-semibold {{ $margin >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($margin, 2) }}%
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Profit per Unit</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            <span class="font-semibold {{ ($product->selling_price - $product->cost_price) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                TZS {{ number_format($product->selling_price - $product->cost_price, 2) }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Inventory Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Inventory</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stock Quantity</dt>
                        <dd class="mt-1">
                            <span class="text-2xl font-bold {{ $product->stock_quantity <= $product->low_stock_alert ? 'text-red-600' : ($product->stock_quantity <= 0 ? 'text-gray-400' : 'text-gray-900') }}">
                                {{ number_format($product->stock_quantity) }}
                            </span>
                            <span class="text-sm text-gray-500 ml-2">{{ $product->unit }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Low Stock Alert</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ number_format($product->low_stock_alert) }} {{ $product->unit }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Stock Status</dt>
                        <dd class="mt-1">
                            @if($product->stock_quantity <= 0)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Out of Stock</span>
                            @elseif($product->stock_quantity <= $product->low_stock_alert)
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">In Stock</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Warehouse</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $product->warehouse->name ?? 'Not assigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Track Stock</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->track_stock ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->track_stock ? 'Yes' : 'No' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('products.edit', $product) }}" class="w-full flex items-center justify-center space-x-2 px-4 py-2 rounded-lg" style="background-color: #009245; color: white;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Product</span>
                    </a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete Product</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Total Value</dt>
                        <dd class="text-sm font-semibold text-gray-900">TZS {{ number_format($product->stock_quantity * $product->cost_price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Potential Revenue</dt>
                        <dd class="text-sm font-semibold text-gray-900">TZS {{ number_format($product->stock_quantity * $product->selling_price, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center">
                        <dt class="text-sm text-gray-500">Potential Profit</dt>
                        <dd class="text-sm font-semibold text-green-600">TZS {{ number_format($product->stock_quantity * ($product->selling_price - $product->cost_price), 2) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timestamps</h3>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-gray-500">Created</dt>
                        <dd class="text-gray-900">{{ $product->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Last Updated</dt>
                        <dd class="text-gray-900">{{ $product->updated_at->format('M d, Y h:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

