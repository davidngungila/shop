<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopSmart') - ShopSmart Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Mobile Menu Toggle -->
        <button id="mobileMenuToggle" class="lg:hidden fixed top-4 left-4 z-40 p-2 bg-white rounded-lg shadow-md">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 fixed h-screen overflow-y-auto z-30 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="p-4">
                <div class="flex items-center space-x-2 mb-8">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">S</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">ShopSmart</span>
                </div>
                
                <nav class="space-y-1" x-data="{ 
                    openMenus: {
                        inventory: {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('warehouses.*') || request()->routeIs('stock-movements.*') ? 'true' : 'false' }},
                        sales: {{ request()->routeIs('sales.*') || request()->routeIs('pos.*') || request()->routeIs('quotations.*') ? 'true' : 'false' }},
                        purchases: {{ request()->routeIs('purchases.*') || request()->routeIs('suppliers.*') ? 'true' : 'false' }},
                        customers: {{ request()->routeIs('customers.*') ? 'true' : 'false' }},
                        employees: {{ request()->routeIs('employees.*') ? 'true' : 'false' }},
                        financial: {{ request()->routeIs('financial.*') || request()->routeIs('expenses.*') || request()->routeIs('transactions.*') ? 'true' : 'false' }},
                        reports: {{ request()->routeIs('reports.*') ? 'true' : 'false' }},
                        settings: {{ request()->routeIs('settings.*') ? 'true' : 'false' }}
                    }
                }">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <!-- Inventory Submenu -->
                    <div>
                        <button @click="openMenus.inventory = !openMenus.inventory" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('warehouses.*') || request()->routeIs('stock-movements.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Inventory</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.inventory }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.inventory" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('products.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Products</span>
                            </a>
                            <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('categories.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span>Categories</span>
                            </a>
                            <a href="{{ route('warehouses.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('warehouses.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>Warehouses</span>
                            </a>
                            <a href="{{ route('stock-movements.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('stock-movements.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span>Stock Movements</span>
                            </a>
                            <a href="{{ route('products.low-stock') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('products.low-stock') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Low Stock Alerts</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Sales Submenu -->
                    <div>
                        <button @click="openMenus.sales = !openMenus.sales" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('sales.*') || request()->routeIs('pos.*') || request()->routeIs('quotations.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Sales</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.sales }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.sales" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('pos.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('pos.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>POS</span>
                            </a>
                            <a href="{{ route('quotations.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('quotations.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Quotations</span>
                            </a>
                            <a href="{{ route('sales.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('sales.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>All Sales</span>
                            </a>
                            <a href="{{ route('sales.invoices') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('sales.invoices') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Invoices</span>
                            </a>
                            <a href="{{ route('sales.returns') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('sales.returns') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Returns</span>
                            </a>
                            <a href="{{ route('quotations.reports') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('quotations.reports.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Quotation Reports</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Purchases Submenu -->
                    <div>
                        <button @click="openMenus.purchases = !openMenus.purchases" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('purchases.*') || request()->routeIs('suppliers.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Purchases</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.purchases }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.purchases" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('purchases.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('purchases.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>All Purchases</span>
                            </a>
                            <a href="{{ route('purchases.orders') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('purchases.orders') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Purchase Orders</span>
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('suppliers.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Suppliers</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Customers Submenu -->
                    <div>
                        <button @click="openMenus.customers = !openMenus.customers" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Customers</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.customers }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.customers" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('customers.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('customers.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>All Customers</span>
                            </a>
                            <a href="{{ route('customers.loyalty') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('customers.loyalty') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span>Loyalty Program</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Employees Submenu -->
                    <div>
                        <button @click="openMenus.employees = !openMenus.employees" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('employees.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Employees</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.employees }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.employees" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('employees.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('employees.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>All Employees</span>
                            </a>
                            <a href="{{ route('employees.roles') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('employees.roles') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>Roles & Permissions</span>
                            </a>
                            <a href="{{ route('employees.attendance') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('employees.attendance') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Attendance</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Financial Submenu -->
                    <div>
                        <button @click="openMenus.financial = !openMenus.financial" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('financial.*') || request()->routeIs('expenses.*') || request()->routeIs('transactions.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Financial</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.financial }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.financial" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('financial.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Overview</span>
                            </a>
                            <a href="{{ route('chart-of-accounts.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('chart-of-accounts.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Chart of Accounts</span>
                            </a>
                            <a href="{{ route('expense-categories.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('expense-categories.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span>Expense Categories</span>
                            </a>
                            <a href="{{ route('expenses.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('expenses.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                                <span>Manage Expenses</span>
                            </a>
                            <a href="{{ route('capital.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('capital.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Manage Capital</span>
                            </a>
                            <a href="{{ route('liabilities.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('liabilities.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>Manage Liabilities</span>
                            </a>
                            <a href="{{ route('bank-reconciliations.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('bank-reconciliations.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Bank Reconciliation</span>
                            </a>
                            <a href="{{ route('financial-statements.profit-loss') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial-statements.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>P&L Statement</span>
                            </a>
                            <a href="{{ route('financial-statements.balance-sheet') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial-statements.balance-sheet') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Balance Sheet</span>
                            </a>
                            <a href="{{ route('financial-statements.trial-balance') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial-statements.trial-balance') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Trial Balance</span>
                            </a>
                            <a href="{{ route('delivery-notes.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('delivery-notes.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <span>Delivery Notes</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Reports Submenu -->
                    <div>
                        <button @click="openMenus.reports = !openMenus.reports" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Reports</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.reports }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.reports" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>All Reports</span>
                            </a>
                            <a href="{{ route('reports.sales') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.sales') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Sales Reports</span>
                            </a>
                            <a href="{{ route('reports.purchases') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.purchases') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Purchase Reports</span>
                            </a>
                            <a href="{{ route('reports.profit-loss') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.profit-loss') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>Profit & Loss</span>
                            </a>
                            <a href="{{ route('reports.inventory') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.inventory') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Inventory Report</span>
                            </a>
                            <a href="{{ route('reports.customers') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.customer-statement') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Customer Statements</span>
                            </a>
                            <a href="{{ route('reports.customers') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.supplier-statement') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Supplier Statements</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Settings Submenu -->
                    <div>
                        <button @click="openMenus.settings = !openMenus.settings" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-purple-50 text-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Settings</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-90': openMenus.settings }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="openMenus.settings" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.index') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Overview</span>
                            </a>
                            <a href="{{ route('settings.general') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.general') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>General</span>
                            </a>
                            <a href="{{ route('settings.users') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.users') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Users & Roles</span>
                            </a>
                            <a href="{{ route('settings.system') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.system') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>System</span>
                            </a>
                            <a href="{{ route('settings.financial') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.financial') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Financial</span>
                            </a>
                            <a href="{{ route('settings.inventory') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.inventory') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Inventory</span>
                            </a>
                            <a href="{{ route('settings.quotations') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.quotations') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Quotations</span>
                            </a>
                            <a href="{{ route('settings.notifications') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.notifications') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span>Notifications</span>
                            </a>
                            <a href="{{ route('settings.backup') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.backup') ? 'bg-purple-50 text-purple-600' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <span>Backup & Maintenance</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 max-w-xl">
                            <div class="relative">
                                <input type="text" placeholder="Search (Ctrl+K)" class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 ml-6" x-data="{ open: false }">
                            <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="hidden sm:inline">New Sale</span>
                            </button>
                            
                            <!-- Notifications -->
                            <button class="relative w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            <!-- Profile Dropdown -->
                            <div class="relative">
                                <button @click="open = !open" class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold hover:bg-purple-700 transition-colors">
                                    @auth
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @endauth
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                    <div class="px-4 py-3 border-b border-gray-200">
                                        <p class="text-sm font-semibold text-gray-900">@auth{{ auth()->user()->name }}@endauth</p>
                                        <p class="text-xs text-gray-500">@auth{{ auth()->user()->email }}@endauth</p>
                                    </div>
                                    <a href="{{ route('profile.index') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="{{ route('profile.security') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span>Security</span>
                                    </a>
                                    <a href="{{ route('profile.activity') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Activity</span>
                                    </a>
                                    <div class="border-t border-gray-200 mt-2 pt-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-translate-x-full');
        });
    </script>
</body>
</html>

