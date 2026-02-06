@extends('layouts.app')

@section('title', 'Create Product')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Create Product</h1>
            <p class="text-gray-600 mt-1">Add a new product to inventory</p>
        </div>
        <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back</span>
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('products.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            placeholder="Enter product name"
                        >
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU -->
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                            SKU
                        </label>
                        <input 
                            type="text" 
                            id="sku" 
                            name="sku" 
                            value="{{ old('sku') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('sku') border-red-500 @enderror"
                            placeholder="Product SKU"
                        >
                        @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Barcode -->
                    <div>
                        <label for="barcode" class="block text-sm font-medium text-gray-700 mb-2">
                            Barcode
                        </label>
                        <input 
                            type="text" 
                            id="barcode" 
                            name="barcode" 
                            value="{{ old('barcode') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('barcode') border-red-500 @enderror"
                            placeholder="Product barcode"
                        >
                        @error('barcode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category
                        </label>
                        <select 
                            id="category_id" 
                            name="category_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                        >
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Unit -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                            Unit <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="unit" 
                            name="unit" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('unit') border-red-500 @enderror"
                        >
                            <option value="">Select unit</option>
                            <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                            <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                            <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                            <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliter (ml)</option>
                            <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="dozen" {{ old('unit') == 'dozen' ? 'selected' : '' }}>Dozen</option>
                        </select>
                        @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                            placeholder="Enter product description"
                        >{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cost Price -->
                    <div>
                        <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Cost Price (TZS) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="cost_price" 
                            name="cost_price" 
                            value="{{ old('cost_price') }}"
                            step="0.01"
                            min="0"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('cost_price') border-red-500 @enderror"
                            placeholder="0.00"
                        >
                        @error('cost_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-2">
                            Selling Price (TZS) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="selling_price" 
                            name="selling_price" 
                            value="{{ old('selling_price') }}"
                            step="0.01"
                            min="0"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('selling_price') border-red-500 @enderror"
                            placeholder="0.00"
                        >
                        @error('selling_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Inventory</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Stock Quantity <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            value="{{ old('stock_quantity', 0) }}"
                            min="0"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror"
                            placeholder="0"
                        >
                        @error('stock_quantity')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Low Stock Alert -->
                    <div>
                        <label for="low_stock_alert" class="block text-sm font-medium text-gray-700 mb-2">
                            Low Stock Alert <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            id="low_stock_alert" 
                            name="low_stock_alert" 
                            value="{{ old('low_stock_alert', 10) }}"
                            min="0"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('low_stock_alert') border-red-500 @enderror"
                            placeholder="10"
                        >
                        @error('low_stock_alert')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Alert when stock falls below this quantity</p>
                    </div>

                    <!-- Warehouse -->
                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Warehouse
                        </label>
                        <select 
                            id="warehouse_id" 
                            name="warehouse_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('warehouse_id') border-red-500 @enderror"
                        >
                            <option value="">Select a warehouse</option>
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
                </div>
            </div>

            <!-- Settings -->
            <div class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Settings</h2>
                <div class="space-y-4">
                    <label class="flex items-center space-x-3">
                        <input 
                            type="checkbox" 
                            name="track_stock" 
                            value="1"
                            {{ old('track_stock', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#009245] border-gray-300 rounded focus:ring-[#009245]"
                        >
                        <span class="text-sm font-medium text-gray-700">Track Stock</span>
                    </label>
                    <label class="flex items-center space-x-3">
                        <input 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                            class="w-4 h-4 text-[#009245] border-gray-300 rounded focus:ring-[#009245]"
                        >
                        <span class="text-sm font-medium text-gray-700">Active</span>
                    </label>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('products.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2" style="background-color: #009245; color: white;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'" onfocus="this.style.outline='2px solid #009245'; this.style.outlineOffset='2px'" onblur="this.style.outline='none'">
                    Create Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

