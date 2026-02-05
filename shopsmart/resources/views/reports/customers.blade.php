@extends('layouts.app')

@section('title', 'Customer Statements')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Customer Statements</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Generate customer account statements</p>
        </div>
    </div>

    <!-- Customers List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">All Customers</h2>
            <span class="text-xs sm:text-sm text-gray-500">{{ $customers->total() ?? 0 }} total customers</span>
        </div>
        
        <!-- Mobile Card View -->
        <div class="block md:hidden divide-y divide-gray-200">
            @forelse($customers ?? [] as $customer)
            <div class="p-4 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $customer->email ?? $customer->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-base font-bold text-gray-900">TZS {{ number_format($customer->sales_sum_total ?? 0, 0) }}</div>
                        <div class="text-xs text-gray-500">{{ $customer->sales_count ?? 0 }} orders</div>
                    </div>
                </div>
                <a href="{{ route('reports.customer-statement', $customer) }}" class="block w-full px-4 py-2 text-sm text-center bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    View Statement
                </a>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No customers found</h3>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto -mx-3 sm:-mx-4 md:mx-0">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-3 sm:px-4 md:px-6 py-2 sm:py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers ?? [] as $customer)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="text-xs sm:text-sm text-gray-500">{{ $customer->email ?? $customer->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm font-semibold text-gray-900">TZS {{ number_format($customer->sales_sum_total ?? 0, 0) }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <div class="text-xs sm:text-sm text-gray-500">{{ $customer->sales_count ?? 0 }}</div>
                        </td>
                        <td class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 whitespace-nowrap text-right">
                            <a href="{{ route('reports.customer-statement', $customer) }}" class="text-xs sm:text-sm text-purple-600 hover:text-purple-700 font-medium">View Statement</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No customers found</h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(isset($customers) && $customers->hasPages())
        <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-t border-gray-200">
            <div class="overflow-x-auto">
                {{ $customers->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

