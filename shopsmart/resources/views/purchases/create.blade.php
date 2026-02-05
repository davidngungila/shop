@extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Create Purchase Order</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Create a new purchase order from your supplier</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('purchases.index') }}" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Back to List</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>
    </div>

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

    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                <!-- Supplier & Date Info -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Supplier Information</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Supplier <span class="text-red-500">*</span></label>
                            <select name="supplier_id" id="supplier_id" required class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Supplier</option>
                                @foreach($suppliers ?? [] as $supplier)
                                <option value="{{ $supplier->id }}" data-email="{{ $supplier->email ?? '' }}" data-phone="{{ $supplier->phone ?? '' }}" data-address="{{ $supplier->address ?? '' }}" data-balance="{{ $supplier->balance ?? 0 }}">
                                    {{ $supplier->name }} - {{ $supplier->email ?? $supplier->phone ?? 'No contact' }}
                                </option>
                                @endforeach
                            </select>
                            <div id="supplierInfo" class="mt-2 text-xs text-gray-500 hidden"></div>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Warehouse <span class="text-gray-400">(Optional)</span></label>
                            <select name="warehouse_id" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Warehouse</option>
                                @foreach($warehouses ?? [] as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Purchase Date <span class="text-red-500">*</span></label>
                            <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Expected Delivery Date <span class="text-gray-400">(Optional)</span></label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">When do you expect to receive this order?</p>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="pending" selected>Pending</option>
                                <option value="ordered">Ordered</option>
                                <option value="partial">Partial</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span>Products to Purchase</span>
                        </h2>
                        <div class="flex gap-2">
                            <input type="text" id="productSearch" placeholder="Search products..." class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="addProductRow()" class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs sm:text-sm flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Add Product</span>
                            </button>
                        </div>
                    </div>
                    <div id="productsContainer" class="space-y-3 sm:space-y-4">
                        <!-- Products will be added here dynamically -->
                    </div>
                    <div id="emptyProductsMessage" class="text-center py-8 text-gray-500 text-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p>Click "Add Product" to start adding items to this purchase order</p>
                    </div>
                </div>

                <!-- Payment & Notes -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Payment & Notes</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Paid Amount (TZS)</label>
                            <input type="number" name="paid_amount" id="paid_amount" value="0" step="0.01" min="0" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateDueAmount()" placeholder="0.00">
                            <p class="mt-1 text-xs text-gray-500">Amount paid upfront (if any)</p>
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Due Amount (TZS)</label>
                            <div id="due_amount_display" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base bg-gray-50 border border-gray-300 rounded-lg font-semibold text-gray-900">
                                TZS 0
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Calculated automatically</p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="3" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional notes about this purchase order..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 sticky top-6">
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs sm:text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold text-gray-900" id="subtotal">TZS 0</span>
                        </div>
                        <div class="flex justify-between text-xs sm:text-sm">
                            <span class="text-gray-600">Total Discount:</span>
                            <span class="font-semibold text-red-600" id="discount">TZS 0</span>
                        </div>
                        <div class="flex justify-between text-xs sm:text-sm">
                            <span class="text-gray-600">Tax (<span id="taxRate">10</span>%):</span>
                            <span class="font-semibold text-gray-900" id="tax">TZS 0</span>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between text-base sm:text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-blue-600" id="total">TZS 0</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 pt-3 mt-3 space-y-2">
                            <div class="flex justify-between text-xs sm:text-sm">
                                <span class="text-gray-600">Paid:</span>
                                <span class="font-semibold text-green-600" id="paidDisplay">TZS 0</span>
                            </div>
                            <div class="flex justify-between text-xs sm:text-sm">
                                <span class="text-gray-600">Due:</span>
                                <span class="font-semibold text-red-600" id="dueDisplay">TZS 0</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 space-y-2">
                        <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm sm:text-base transition-colors">
                            Create Purchase Order
                        </button>
                        <p class="text-xs text-center text-gray-500">Items: <span id="itemCount">0</span></p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let productRowIndex = 0;
    const products = @json($products ?? []);
    let taxRate = 0.10; // 10% default tax

    // Format TZS currency
    function formatTZS(amount) {
        return 'TZS ' + parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    // Supplier info display
    document.getElementById('supplier_id')?.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const infoDiv = document.getElementById('supplierInfo');
        if (option.value && option.dataset.email) {
            let info = '';
            if (option.dataset.email) info += `Email: ${option.dataset.email}<br>`;
            if (option.dataset.phone) info += `Phone: ${option.dataset.phone}<br>`;
            if (option.dataset.address) info += `Address: ${option.dataset.address}<br>`;
            const balance = parseFloat(option.dataset.balance || 0);
            if (balance > 0) {
                info += `Outstanding Balance: ${formatTZS(balance)}`;
            }
            infoDiv.innerHTML = info;
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    });

    // Product search
    document.getElementById('productSearch')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        if (searchTerm.length > 0) {
            console.log('Searching for:', searchTerm);
        }
    });

    function addProductRow(product = null) {
        const container = document.getElementById('productsContainer');
        const emptyMessage = document.getElementById('emptyProductsMessage');
        
        if (emptyMessage) {
            emptyMessage.classList.add('hidden');
        }

        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-end border border-gray-200 rounded-lg p-3 sm:p-4 bg-gray-50 hover:bg-gray-100 transition-colors';
        row.id = `product-row-${productRowIndex}`;
        
        row.innerHTML = `
            <div class="sm:col-span-5">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Product <span class="text-red-500">*</span></label>
                <select name="items[${productRowIndex}][product_id]" class="product-select w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required onchange="updateProductInfo(${productRowIndex}, this.value)">
                    <option value="">Select Product</option>
                    ${products.map(p => {
                        const stockStatus = p.stock_quantity > 0 ? (p.stock_quantity <= p.low_stock_alert ? '⚠️ Low Stock' : '✅ In Stock') : '❌ Out of Stock';
                        return `<option value="${p.id}" 
                            data-price="${p.cost_price || p.selling_price}" 
                            data-stock="${p.stock_quantity}"
                            data-sku="${p.sku || 'N/A'}"
                            ${product && product.product_id == p.id ? 'selected' : ''}>
                            ${p.name} - ${formatTZS(p.cost_price || p.selling_price)} (${stockStatus})
                        </option>`;
                    }).join('')}
                </select>
                <div class="product-info mt-1 text-xs text-gray-500" id="product-info-${productRowIndex}"></div>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Quantity <span class="text-red-500">*</span></label>
                <input type="number" name="items[${productRowIndex}][quantity]" value="${product ? product.quantity : 1}" min="1" required class="quantity-input w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRowTotal(${productRowIndex})">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Unit Price <span class="text-red-500">*</span></label>
                <input type="number" name="items[${productRowIndex}][unit_price]" value="${product ? product.unit_price : 0}" step="0.01" min="0" required class="price-input w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRowTotal(${productRowIndex})" placeholder="0.00">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Discount</label>
                <input type="number" name="items[${productRowIndex}][discount]" value="${product ? product.discount : 0}" step="0.01" min="0" class="discount-input w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="calculateRowTotal(${productRowIndex})" placeholder="0.00">
            </div>
            <div class="sm:col-span-1 flex flex-col sm:flex-row gap-2">
                <div class="flex-1">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Total</label>
                    <div class="row-total px-3 sm:px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-xs sm:text-sm font-semibold text-gray-900">${formatTZS(0)}</div>
                </div>
                <button type="button" onclick="removeProductRow(${productRowIndex})" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center" title="Remove">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        container.appendChild(row);
        productRowIndex++;
        updateItemCount();
        calculateTotals();
    }

    function removeProductRow(index) {
        const row = document.getElementById(`product-row-${index}`);
        if (row) {
            row.remove();
            updateItemCount();
            calculateTotals();
            calculateDueAmount();
            
            // Show empty message if no products
            const container = document.getElementById('productsContainer');
            const emptyMessage = document.getElementById('emptyProductsMessage');
            if (container && emptyMessage && container.children.length === 0) {
                emptyMessage.classList.remove('hidden');
            }
        }
    }

    function updateProductInfo(rowIndex, productId) {
        const option = document.querySelector(`#product-row-${rowIndex} .product-select option[value="${productId}"]`);
        const infoDiv = document.getElementById(`product-info-${rowIndex}`);
        
        if (option && option.value) {
            const price = parseFloat(option.dataset.price || 0);
            const stock = parseInt(option.dataset.stock || 0);
            const sku = option.dataset.sku || 'N/A';
            
            // Update price input
            const priceInput = document.querySelector(`#product-row-${rowIndex} .price-input`);
            if (priceInput) {
                priceInput.value = price.toFixed(2);
            }
            
            // Show product info
            if (infoDiv) {
                let info = `SKU: ${sku}`;
                if (stock > 0) {
                    info += ` | Current Stock: ${stock}`;
                    if (stock <= 10) {
                        info += ' <span class="text-yellow-600">⚠️ Low Stock</span>';
                    }
                } else {
                    info += ' <span class="text-gray-500">No Stock</span>';
                }
                infoDiv.innerHTML = info;
            }
            
            calculateRowTotal(rowIndex);
        } else if (infoDiv) {
            infoDiv.innerHTML = '';
        }
    }

    function calculateRowTotal(rowIndex) {
        const row = document.getElementById(`product-row-${rowIndex}`);
        if (!row) return;

        const quantity = parseFloat(row.querySelector('.quantity-input')?.value) || 0;
        const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
        const discount = parseFloat(row.querySelector('.discount-input')?.value) || 0;
        const total = Math.max(0, (price * quantity) - discount);

        const totalDiv = row.querySelector('.row-total');
        if (totalDiv) {
            totalDiv.textContent = formatTZS(total);
        }
        
        calculateTotals();
        calculateDueAmount();
    }

    function calculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0;

        document.querySelectorAll('[id^="product-row-"]').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity-input')?.value) || 0;
            const price = parseFloat(row.querySelector('.price-input')?.value) || 0;
            const discount = parseFloat(row.querySelector('.discount-input')?.value) || 0;
            subtotal += Math.max(0, (price * quantity) - discount);
            totalDiscount += discount;
        });

        const tax = subtotal * taxRate;
        const total = subtotal + tax;

        const subtotalEl = document.getElementById('subtotal');
        const discountEl = document.getElementById('discount');
        const taxEl = document.getElementById('tax');
        const totalEl = document.getElementById('total');

        if (subtotalEl) subtotalEl.textContent = formatTZS(subtotal);
        if (discountEl) discountEl.textContent = formatTZS(totalDiscount);
        if (taxEl) taxEl.textContent = formatTZS(tax);
        if (totalEl) totalEl.textContent = formatTZS(total);
    }

    function calculateDueAmount() {
        const totalEl = document.getElementById('total');
        const paidInput = document.getElementById('paid_amount');
        const dueDisplay = document.getElementById('due_amount_display');
        const paidDisplay = document.getElementById('paidDisplay');
        const dueDisplaySummary = document.getElementById('dueDisplay');
        
        if (totalEl && paidInput && dueDisplay) {
            const totalText = totalEl.textContent.replace('TZS ', '').replace(/,/g, '');
            const total = parseFloat(totalText) || 0;
            const paid = parseFloat(paidInput.value) || 0;
            const due = Math.max(0, total - paid);
            
            dueDisplay.textContent = formatTZS(due);
            if (paidDisplay) paidDisplay.textContent = formatTZS(paid);
            if (dueDisplaySummary) dueDisplaySummary.textContent = formatTZS(due);
        }
    }

    function updateItemCount() {
        const count = document.querySelectorAll('[id^="product-row-"]').length;
        const countEl = document.getElementById('itemCount');
        if (countEl) {
            countEl.textContent = count;
        }
    }

    // Form validation
    document.getElementById('purchaseForm')?.addEventListener('submit', function(e) {
        const productRows = document.querySelectorAll('[id^="product-row-"]');
        if (productRows.length === 0) {
            e.preventDefault();
            alert('Please add at least one product to the purchase order.');
            return false;
        }
        
        // Validate each product row
        let isValid = true;
        productRows.forEach(row => {
            const productId = row.querySelector('.product-select')?.value;
            const quantity = row.querySelector('.quantity-input')?.value;
            const price = row.querySelector('.price-input')?.value;
            
            if (!productId || !quantity || !price || parseFloat(price) <= 0) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please ensure all products have valid information.');
            return false;
        }
    });

    // Paid amount change handler
    document.getElementById('paid_amount')?.addEventListener('input', calculateDueAmount);
    document.getElementById('paid_amount')?.addEventListener('change', calculateDueAmount);

    // Add first product row on load
    document.addEventListener('DOMContentLoaded', function() {
        addProductRow();
    });
</script>
@endsection

