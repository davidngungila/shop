@extends('layouts.app')

@section('title', 'Record Stock Movement')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Record Stock Movement</h1>
            <p class="text-gray-600 mt-1">Add new inventory movement</p>
        </div>
        <a href="{{ route('stock-movements.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('stock-movements.store') }}" class="space-y-6">
            @csrf

            <!-- Product Selection -->
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Product <span class="text-red-500">*</span>
                </label>
                <select 
                    id="product_id" 
                    name="product_id" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('product_id') border-red-500 @enderror"
                >
                    <option value="">Select a product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (SKU: {{ $product->sku }}) - Stock: {{ $product->stock_quantity }}
                    </option>
                    @endforeach
                </select>
                @error('product_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Movement Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Movement Type <span class="text-red-500">*</span>
                </label>
                <select 
                    id="type" 
                    name="type" 
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('type') border-red-500 @enderror"
                >
                    <option value="">Select movement type</option>
                    <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Stock In (Add)</option>
                    <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Stock Out (Remove)</option>
                    <option value="return" {{ old('type') == 'return' ? 'selected' : '' }}>Return (Add back)</option>
                    <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                </select>
                @error('type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    Quantity <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    id="quantity" 
                    name="quantity" 
                    value="{{ old('quantity') }}"
                    min="1"
                    required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('quantity') border-red-500 @enderror"
                    placeholder="Enter quantity"
                >
                @error('quantity')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warehouse Selection -->
            <div>
                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Warehouse
                </label>
                <select 
                    id="warehouse_id" 
                    name="warehouse_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('warehouse_id') border-red-500 @enderror"
                >
                    <option value="">Select a warehouse (optional)</option>
                    @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }}
                    </option>
                    @endforeach
                </select>
                @error('warehouse_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                    placeholder="Add any additional notes about this movement..."
                >{{ old('notes') }}</textarea>
                @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('stock-movements.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: #009245; color: white;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'" onfocus="this.style.outline='2px solid #009245'; this.style.outlineOffset='2px'" onblur="this.style.outline='none'">
                    Record Movement
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

