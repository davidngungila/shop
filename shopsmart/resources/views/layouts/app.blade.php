<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopSmart') - ShopSmart Dashboard</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    
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
                <div class="flex items-center space-x-3 mb-8">
                    <img src="{{ asset('logo.png') }}" alt="ShopSmart Logo" class="h-10 w-auto">
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
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <!-- Inventory Submenu -->
                    <div>
                        <button @click="openMenus.inventory = !openMenus.inventory" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('warehouses.*') || request()->routeIs('stock-movements.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('products.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Products</span>
                            </a>
                            <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('categories.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span>Categories</span>
                            </a>
                            <a href="{{ route('warehouses.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('warehouses.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>Warehouses</span>
                            </a>
                            <a href="{{ route('stock-movements.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('stock-movements.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span>Stock Movements</span>
                            </a>
                            <a href="{{ route('products.low-stock') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('products.low-stock') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span>Low Stock Alerts</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Sales Submenu -->
                    <div>
                        <button @click="openMenus.sales = !openMenus.sales" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('sales.*') || request()->routeIs('pos.*') || request()->routeIs('quotations.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('pos.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('pos.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>POS</span>
                            </a>
                            <a href="{{ route('quotations.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('quotations.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Quotations</span>
                            </a>
                            <a href="{{ route('sales.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('sales.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>All Sales</span>
                            </a>
                            <a href="{{ route('sales.invoices') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('sales.invoices') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Invoices</span>
                            </a>
                            <a href="{{ route('sales.returns') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('sales.returns') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Returns</span>
                            </a>
                            <a href="{{ route('quotations.reports') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('quotations.reports.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Quotation Reports</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Purchases Submenu -->
                    <div>
                        <button @click="openMenus.purchases = !openMenus.purchases" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('purchases.*') || request()->routeIs('suppliers.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('purchases.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('purchases.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>All Purchases</span>
                            </a>
                            <a href="{{ route('purchases.orders') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('purchases.orders') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Purchase Orders</span>
                            </a>
                            <a href="{{ route('suppliers.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('suppliers.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Suppliers</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Customers Submenu -->
                    <div>
                        <button @click="openMenus.customers = !openMenus.customers" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('customers.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('customers.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('customers.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>All Customers</span>
                            </a>
                            <a href="{{ route('customers.loyalty') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('customers.loyalty') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span>Loyalty Program</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Employees Submenu -->
                    <div>
                        <button @click="openMenus.employees = !openMenus.employees" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('employees.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('employees.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('employees.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>All Employees</span>
                            </a>
                            <a href="{{ route('employees.roles') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('employees.roles') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>Roles & Permissions</span>
                            </a>
                            <a href="{{ route('employees.attendance') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('employees.attendance') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Attendance</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Financial Submenu -->
                    <div>
                        <button @click="openMenus.financial = !openMenus.financial" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('financial.*') || request()->routeIs('expenses.*') || request()->routeIs('transactions.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('financial.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Overview</span>
                            </a>
                            <a href="{{ route('chart-of-accounts.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('chart-of-accounts.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Chart of Accounts</span>
                            </a>
                            <a href="{{ route('expense-categories.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('expense-categories.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                <span>Expense Categories</span>
                            </a>
                            <a href="{{ route('expenses.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('expenses.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                                <span>Manage Expenses</span>
                            </a>
                            <a href="{{ route('capital.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('capital.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Manage Capital</span>
                            </a>
                            <a href="{{ route('liabilities.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('liabilities.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span>Manage Liabilities</span>
                            </a>
                            <a href="{{ route('bank-reconciliations.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('bank-reconciliations.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Bank Reconciliation</span>
                            </a>
                            <a href="{{ route('financial-statements.profit-loss') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial-statements.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>P&L Statement</span>
                            </a>
                            <a href="{{ route('financial-statements.balance-sheet') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial-statements.balance-sheet') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Balance Sheet</span>
                            </a>
                            <a href="{{ route('financial-statements.trial-balance') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('financial-statements.trial-balance') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Trial Balance</span>
                            </a>
                            <a href="{{ route('delivery-notes.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('delivery-notes.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <span>Delivery Notes</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Reports Submenu -->
                    <div>
                        <button @click="openMenus.reports = !openMenus.reports" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>All Reports</span>
                            </a>
                            <a href="{{ route('reports.sales') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.sales') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Sales Reports</span>
                            </a>
                            <a href="{{ route('reports.purchases') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.purchases') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Purchase Reports</span>
                            </a>
                            <a href="{{ route('reports.profit-loss') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.profit-loss') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>Profit & Loss</span>
                            </a>
                            <a href="{{ route('reports.inventory') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.inventory') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Inventory Report</span>
                            </a>
                            <a href="{{ route('reports.customers') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.customer-statement') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Customer Statements</span>
                            </a>
                            <a href="{{ route('reports.customers') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('reports.supplier-statement') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Supplier Statements</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Settings Submenu -->
                    <div>
                        <button @click="openMenus.settings = !openMenus.settings" class="w-full flex items-center justify-between px-4 py-3 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-green-50 text-[#009245]' : 'text-gray-700 hover:bg-gray-50' }}">
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
                            <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.index') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Overview</span>
                            </a>
                            <a href="{{ route('settings.general') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.general') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <span>General</span>
                            </a>
                            <a href="{{ route('settings.users') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.users') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span>Users & Roles</span>
                            </a>
                            <a href="{{ route('settings.system') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.system') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>System</span>
                            </a>
                            <a href="{{ route('settings.financial') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.financial') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Financial</span>
                            </a>
                            <a href="{{ route('settings.inventory') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.inventory') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Inventory</span>
                            </a>
                            <a href="{{ route('settings.quotations') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.quotations') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Quotations</span>
                            </a>
                            <a href="{{ route('settings.notifications') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.notifications') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span>Notifications</span>
                            </a>
                            <a href="{{ route('settings.backup') }}" class="flex items-center space-x-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('settings.backup') ? 'bg-green-50 text-[#009245]' : 'text-gray-600 hover:bg-gray-50' }}">
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
                                <input type="text" placeholder="Search (Ctrl+K)" class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#009245] focus:border-transparent">
                                <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 ml-6" x-data="{ open: false }">
                            <!-- Notifications -->
                            <div x-data="{ notificationsOpen: false }" class="relative" @mouseenter="notificationsOpen = true" @mouseleave="notificationsOpen = false">
                            @php
                                // Get notification counts
                                $lowStockCount = \App\Models\Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')
                                    ->where('is_active', true)
                                    ->where('track_stock', true)
                                    ->count();
                                $outOfStockCount = \App\Models\Product::where('stock_quantity', '<=', 0)
                                    ->where('is_active', true)
                                    ->where('track_stock', true)
                                    ->count();
                                $pendingQuotations = \App\Models\Quotation::where('status', 'pending')
                                    ->where(function($q) {
                                        $q->whereNull('expiry_date')
                                          ->orWhere('expiry_date', '>=', now());
                                    })
                                    ->count();
                                $expiredQuotations = \App\Models\Quotation::where('status', '!=', 'converted')
                                    ->whereNotNull('expiry_date')
                                    ->where('expiry_date', '<', now())
                                    ->count();
                                $pendingPurchases = \App\Models\Purchase::whereIn('status', ['pending', 'ordered'])
                                    ->count();
                                
                                $totalNotifications = $lowStockCount + $outOfStockCount + $pendingQuotations + $expiredQuotations + $pendingPurchases;
                                
                                // Get recent low stock products
                                $lowStockProducts = \App\Models\Product::whereColumn('stock_quantity', '<=', 'low_stock_alert')
                                    ->where('is_active', true)
                                    ->where('track_stock', true)
                                    ->with('category')
                                    ->latest()
                                    ->limit(5)
                                    ->get();
                                
                                // Get recent pending quotations
                                $recentQuotations = \App\Models\Quotation::where('status', 'pending')
                                    ->with('customer')
                                    ->latest()
                                    ->limit(5)
                                    ->get();
                            @endphp
                                <button class="relative w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-all duration-200 hover:scale-110">
                                    <svg class="w-5 h-5 text-gray-600 transition-colors duration-200" :class="{'text-[#009245]': notificationsOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    @if($totalNotifications > 0)
                                        <span class="absolute top-0 right-0 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center border-2 border-white">
                                            {{ $totalNotifications > 99 ? '99+' : $totalNotifications }}
                                        </span>
                                    @endif
                                </button>
                                
                                <!-- Notifications Dropdown -->
                                <div x-show="notificationsOpen" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-hidden flex flex-col">
                                    <!-- Header -->
                                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                            @if($totalNotifications > 0)
                                                <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                                    {{ $totalNotifications }} new
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Notifications List -->
                                    <div class="overflow-y-auto flex-1">
                                        @if($totalNotifications > 0)
                                            <!-- Low Stock Alerts -->
                                            @if($lowStockCount > 0 || $outOfStockCount > 0)
                                                <div class="px-4 py-2 bg-yellow-50 border-b border-yellow-100">
                                                    <p class="text-xs font-semibold text-yellow-800 mb-1">Stock Alerts</p>
                                                </div>
                                                @if($outOfStockCount > 0)
                                                    <a href="{{ route('products.low-stock') }}" class="block px-4 py-3 hover:bg-red-50 transition-colors duration-200 border-b border-gray-100">
                                                        <div class="flex items-start space-x-3">
                                                            <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-medium text-gray-900">Out of Stock</p>
                                                                <p class="text-xs text-gray-500 mt-0.5">{{ $outOfStockCount }} product(s) out of stock</p>
                                                                <p class="text-xs text-gray-400 mt-1">Click to view</p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endif
                                                @if($lowStockCount > 0)
                                                    <a href="{{ route('products.low-stock') }}" class="block px-4 py-3 hover:bg-yellow-50 transition-colors duration-200 border-b border-gray-100">
                                                        <div class="flex items-start space-x-3">
                                                            <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-medium text-gray-900">Low Stock Alert</p>
                                                                <p class="text-xs text-gray-500 mt-0.5">{{ $lowStockCount }} product(s) running low</p>
                                                                <p class="text-xs text-gray-400 mt-1">Click to view</p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endif
                                            @endif
                                            
                                            <!-- Pending Quotations -->
                                            @if($pendingQuotations > 0)
                                                <div class="px-4 py-2 bg-blue-50 border-b border-blue-100">
                                                    <p class="text-xs font-semibold text-blue-800 mb-1">Quotations</p>
                                                </div>
                                                <a href="{{ route('quotations.index', ['status' => 'pending']) }}" class="block px-4 py-3 hover:bg-blue-50 transition-colors duration-200 border-b border-gray-100">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">Pending Quotations</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">{{ $pendingQuotations }} quotation(s) awaiting approval</p>
                                                            <p class="text-xs text-gray-400 mt-1">Click to view</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                            <!-- Expired Quotations -->
                                            @if($expiredQuotations > 0)
                                                <a href="{{ route('quotations.index', ['status' => 'expired']) }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-200 border-b border-gray-100">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">Expired Quotations</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">{{ $expiredQuotations }} quotation(s) expired</p>
                                                            <p class="text-xs text-gray-400 mt-1">Click to view</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                            <!-- Pending Purchases -->
                                            @if($pendingPurchases > 0)
                                                <a href="{{ route('purchases.orders') }}" class="block px-4 py-3 hover:bg-purple-50 transition-colors duration-200 border-b border-gray-100">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">Pending Purchases</p>
                                                            <p class="text-xs text-gray-500 mt-0.5">{{ $pendingPurchases }} purchase order(s) pending</p>
                                                            <p class="text-xs text-gray-400 mt-1">Click to view</p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                            <!-- Recent Low Stock Products -->
                                            @if($lowStockProducts->count() > 0)
                                                <div class="px-4 py-2 bg-yellow-50 border-b border-yellow-100">
                                                    <p class="text-xs font-semibold text-yellow-800 mb-1">Recent Low Stock Items</p>
                                                </div>
                                                @foreach($lowStockProducts->take(3) as $product)
                                                    <a href="{{ route('products.show', $product) }}" class="block px-4 py-2 hover:bg-yellow-50 transition-colors duration-200 border-b border-gray-100">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                                                <p class="text-xs text-gray-500">Stock: {{ $product->stock_quantity }} {{ $product->unit ?? 'pcs' }}</p>
                                                            </div>
                                                            <span class="text-xs text-yellow-600 font-semibold">Low</span>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @endif
                                        @else
                                            <div class="px-4 py-8 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">All caught up!</h3>
                                                <p class="mt-1 text-xs text-gray-500">No new notifications</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Footer -->
                                    @if($totalNotifications > 0)
                                        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                            <a href="{{ route('products.low-stock') }}" class="text-xs font-medium text-[#009245] hover:text-[#007a38] transition-colors duration-200">
                                                View all notifications →
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Profile Dropdown -->
                            <div class="relative" @mouseenter="open = true" @mouseleave="open = false">
                                @auth
                                    @php
                                        $user = auth()->user();
                                        $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null;
                                        $userInitials = strtoupper(substr($user->name, 0, 1));
                                        $userRole = $user->role ?? 'User';
                                        $roleColors = [
                                            'admin' => 'bg-red-100 text-red-700',
                                            'manager' => 'bg-blue-100 text-blue-700',
                                            'sales' => 'bg-green-100 text-green-700',
                                            'cashier' => 'bg-yellow-100 text-yellow-700',
                                            'inventory' => 'bg-purple-100 text-purple-700',
                                            'accountant' => 'bg-indigo-100 text-indigo-700',
                                        ];
                                        $roleColor = $roleColors[strtolower($userRole)] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <button class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-200 hover:shadow-md group">
                                        <div class="relative group">
                                            @if($avatarUrl)
                                                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200 transition-transform duration-200 group-hover:scale-110">
                                            @else
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-white text-sm transition-transform duration-200 group-hover:scale-110" style="background-color: #009245;">
                                                    {{ $userInitials }}
                                                </div>
                                            @endif
                                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full transition-transform duration-200 group-hover:scale-125"></span>
                                        </div>
                                        <div class="hidden md:block text-left">
                                            <p class="text-sm font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#009245]">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 capitalize transition-colors duration-200 group-hover:text-gray-700">{{ $userRole }}</p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400 hidden md:block transition-transform duration-200 group-hover:rotate-180 group-hover:text-[#009245]" :class="{'rotate-180 text-[#009245]': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-colors" style="background-color: #009245; color: white;" onmouseover="this.style.backgroundColor='#007a38'" onmouseout="this.style.backgroundColor='#009245'">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </button>
                                @endauth
                                
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                    @auth
                                        @php
                                            $user = auth()->user();
                                            $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null;
                                            $userInitials = strtoupper(substr($user->name, 0, 1));
                                            $userRole = $user->role ?? 'User';
                                            $roleColors = [
                                                'admin' => 'bg-red-100 text-red-700',
                                                'manager' => 'bg-blue-100 text-blue-700',
                                                'sales' => 'bg-green-100 text-green-700',
                                                'cashier' => 'bg-yellow-100 text-yellow-700',
                                                'inventory' => 'bg-purple-100 text-purple-700',
                                                'accountant' => 'bg-indigo-100 text-indigo-700',
                                            ];
                                            $roleColor = $roleColors[strtolower($userRole)] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <div class="px-4 py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200 rounded-t-lg">
                                            <div class="flex items-center space-x-3">
                                                @if($avatarUrl)
                                                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-gray-200 transition-transform duration-200 hover:scale-110">
                                                @else
                                                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-semibold text-white transition-transform duration-200 hover:scale-110" style="background-color: #009245;">
                                                        {{ $userInitials }}
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mt-1 transition-all duration-200 hover:scale-105 {{ $roleColor }}">
                                                        {{ ucfirst($userRole) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endauth
                                    <a href="{{ route('profile.index') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#009245] transition-colors duration-200 group">
                                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>My Profile</span>
                                    </a>
                                    <a href="{{ route('profile.security') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#009245] transition-colors duration-200 group">
                                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span>Security</span>
                                    </a>
                                    <a href="{{ route('profile.activity') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#009245] transition-colors duration-200 group">
                                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Activity</span>
                                    </a>
                                    <div class="border-t border-gray-200 mt-2 pt-2">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center space-x-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full transition-colors duration-200 group">
                                                <svg class="w-4 h-4 transition-transform duration-200 group-hover:scale-110 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                <span class="transition-colors duration-200 group-hover:font-semibold">Logout</span>
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

