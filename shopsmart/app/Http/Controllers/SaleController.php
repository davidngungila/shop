<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items', 'items.product']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Statistics (All time)
        $totalSales = Sale::count();
        $totalAmount = Sale::sum('total');
        
        // Today's statistics
        $todaySales = Sale::whereDate('created_at', today())->count();
        $todayAmount = Sale::whereDate('created_at', today())->sum('total');
        
        // This month statistics
        $thisMonthSales = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $thisMonthAmount = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        // Status breakdown
        $statusBreakdown = Sale::selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();

        // Payment methods breakdown
        $paymentMethods = Sale::selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        $sales = $query->latest()->paginate(20);
        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('sales.index', compact(
            'sales', 'totalSales', 'totalAmount', 
            'todaySales', 'todayAmount', 
            'thisMonthSales', 'thisMonthAmount',
            'statusBreakdown', 'paymentMethods', 'customers', 
            'dateFrom', 'dateTo'
        ));
    }

    public function invoices(Request $request)
    {
        $query = Sale::where('status', 'completed')
            ->with(['customer', 'user', 'items', 'items.product']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        // Apply date range to filtered query for statistics
        $filteredQuery = Sale::where('status', 'completed');
        if ($request->filled('date_from')) {
            $filteredQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $filteredQuery->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('payment_method')) {
            $filteredQuery->where('payment_method', $request->payment_method);
        }
        if ($request->filled('customer_id')) {
            $filteredQuery->where('customer_id', $request->customer_id);
        }

        // Statistics (All time)
        $totalInvoices = Sale::where('status', 'completed')->count();
        $totalAmount = Sale::where('status', 'completed')->sum('total');
        
        // Today's statistics
        $todayInvoices = Sale::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();
        $todayAmount = Sale::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');
        
        // This month statistics
        $thisMonthInvoices = Sale::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $thisMonthAmount = Sale::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        
        // Last month for comparison
        $lastMonthAmount = Sale::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total');
        $monthGrowth = $lastMonthAmount > 0 ? (($thisMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100 : 0;

        // Filtered statistics
        $filteredInvoices = $filteredQuery->count();
        $filteredAmount = $filteredQuery->sum('total');
        $averageInvoice = $filteredInvoices > 0 ? $filteredAmount / $filteredInvoices : 0;

        // Payment methods breakdown (filtered)
        $paymentMethodsQuery = Sale::where('status', 'completed');
        if ($request->filled('date_from')) {
            $paymentMethodsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $paymentMethodsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        $paymentMethods = $paymentMethodsQuery
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total) as total')
            ->groupBy('payment_method')
            ->get();

        // Daily sales trend (last 30 days)
        $dailySales = Sale::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top customers (filtered)
        $topCustomersQuery = Sale::where('status', 'completed')->whereNotNull('customer_id');
        if ($request->filled('date_from')) {
            $topCustomersQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $topCustomersQuery->whereDate('created_at', '<=', $request->date_to);
        }
        $topCustomers = $topCustomersQuery
            ->selectRaw('customer_id, COUNT(*) as invoice_count, SUM(total) as total_spent')
            ->groupBy('customer_id')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get()
            ->load('customer');

        // Top products sold
        $topProductsQuery = \App\Models\SaleItem::whereHas('sale', function($q) {
                $q->where('status', 'completed');
            });
        if ($request->filled('date_from')) {
            $topProductsQuery->whereHas('sale', function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $topProductsQuery->whereHas('sale', function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }
        $topProducts = $topProductsQuery
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total) as total_revenue')
            ->groupBy('product_id')
            ->orderBy('total_revenue', 'desc')
            ->limit(5)
            ->get()
            ->load('product');

        $sales = $query->latest()->paginate(20);
        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('sales.invoices', compact(
            'sales', 'totalInvoices', 'totalAmount', 
            'todayInvoices', 'todayAmount', 
            'thisMonthInvoices', 'thisMonthAmount', 'monthGrowth',
            'filteredInvoices', 'filteredAmount', 'averageInvoice',
            'paymentMethods', 'customers', 'dailySales', 
            'topCustomers', 'topProducts', 'dateFrom', 'dateTo'
        ));
    }

    public function returns(Request $request)
    {
        $query = Sale::where('status', 'refunded')
            ->with(['customer', 'user', 'items.product']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Statistics
        $totalReturns = Sale::where('status', 'refunded')->count();
        $totalRefundAmount = Sale::where('status', 'refunded')->sum('total');
        $todayReturns = Sale::where('status', 'refunded')
            ->whereDate('created_at', today())
            ->count();
        $todayRefundAmount = Sale::where('status', 'refunded')
            ->whereDate('created_at', today())
            ->sum('total');
        $thisMonthRefundAmount = Sale::where('status', 'refunded')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // Monthly returns trend
        $monthlyReturns = Sale::where('status', 'refunded')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $sales = $query->latest()->paginate(20);
        $customers = \App\Models\Customer::orderBy('name')->get();

        return view('sales.returns', compact('sales', 'totalReturns', 'totalRefundAmount', 'todayReturns', 'todayRefundAmount', 'thisMonthRefundAmount', 'monthlyReturns', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'warehouse']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Print receipt (thermal printer format)
     */
    public function print(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'warehouse']);
        return view('sales.receipt-print', compact('sale'));
    }

    /**
     * Download receipt as PDF
     */
    public function pdf(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product', 'warehouse']);
        return view('sales.receipt-pdf', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
