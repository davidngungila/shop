@extends('layouts.app')

@section('title', 'Supplier Statement - ' . $supplier->name)

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Supplier Statement</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">{{ $supplier->name }}</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button onclick="window.print()" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="hidden sm:inline">Print</span>
            </button>
            <a href="{{ route('reports.suppliers') }}" class="px-3 sm:px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>
        </div>
    </div>

    <!-- Supplier Info -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <p class="text-xs sm:text-sm text-gray-600">Supplier Name</p>
                <p class="text-sm sm:text-base font-semibold text-gray-900 mt-1">{{ $supplier->name }}</p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-gray-600">Email</p>
                <p class="text-sm sm:text-base text-gray-900 mt-1">{{ $supplier->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-gray-600">Phone</p>
                <p class="text-sm sm:text-base text-gray-900 mt-1">{{ $supplier->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs sm:text-sm text-gray-600">Address</p>
                <p class="text-sm sm:text-base text-gray-900 mt-1">{{ $supplier->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 md:p-6">
        <form method="GET" action="{{ route('reports.supplier-statement', $supplier) }}" class="space-y-3 sm:space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">Apply Filters</button>
                <a href="{{ route('reports.supplier-statement', $supplier) }}" class="w-full sm:w-auto px-4 sm:px-6 py-2 text-sm sm:text-base bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Opening Balance</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($openingBalance ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Purchases</p>
            <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2">TZS {{ number_format($totalPurchases ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Total Paid</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600 mt-2">TZS {{ number_format($totalPaid ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <p class="text-xs sm:text-sm text-gray-600">Closing Balance</p>
            <p class="text-xl sm:text-2xl font-bold text-red-600 mt-2">TZS {{ number_format($closingBalance ?? 0, 0) }}</p>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Transaction History</h2>
            <span class="text-xs sm:text-sm text-gray-500">{{ $purchases->total() ?? 0 }} transactions</span>
        </div>
        
        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-gray-200">
            @forelse($purchases ?? [] as $purchase)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $purchase->purchase_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-base font-bold text-gray-900">TZS {{ number_format($purchase->total, 0) }}</div>
                        <div class="text-xs text-gray-500">Due: TZS {{ number_format($purchase->due_amount, 0) }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-gray-500">Paid:</span>
                        <div class="font-medium text-green-600 mt-0.5">TZS {{ number_format($purchase->paid_amount, 0) }}</div>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <div class="font-medium text-gray-900 mt-0.5 capitalize">{{ $purchase->status }}</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your date range.</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto -mx-3 sm:-mx-4 md:mx-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Due</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($purchases ?? [] as $purchase)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">#{{ $purchase->purchase_number ?? str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-900">{{ $purchase->purchase_date->setTimezone('Africa/Dar_es_Salaam')->format('M d, Y') }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-gray-900">TZS {{ number_format($purchase->total, 0) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-green-600">TZS {{ number_format($purchase->paid_amount, 0) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-red-600">TZS {{ number_format($purchase->due_amount, 0) }}</div>
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your date range.</p>
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
@endsection

