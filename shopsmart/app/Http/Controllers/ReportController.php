<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $query = Sale::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->with(['customer', 'items.product']);

        // Apply additional filters
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $sales = $query->latest()->paginate(50);

        // Statistics
        $totalSales = Sale::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->sum('total');
        
        $totalOrders = Sale::whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->count();
        
        $averageOrder = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Today's sales
        $todaySales = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total');
        
        $todayOrders = Sale::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        // This month sales
        $thisMonthSales = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total');

        // Last month for comparison
        $lastMonthSales = Sale::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->where('status', 'completed')
            ->sum('total');
        
        $monthGrowth = $lastMonthSales > 0 ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        // Sales by payment method
        $salesByPayment = Sale::select('payment_method', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        // Daily sales trend
        $dailySales = Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly sales trend (last 12 months)
        $monthlySales = Sale::select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top customers
        $topCustomers = Sale::select('customer_id', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->load('customer');

        // Top products
        $topProducts = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->whereBetween('sales.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('sales.status', 'completed')
            ->select('products.id', 'products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'), DB::raw('SUM(sale_items.total) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();

        $customers = Customer::orderBy('name')->get();

        return view('reports.sales', compact(
            'sales', 'totalSales', 'totalOrders', 'averageOrder', 
            'todaySales', 'todayOrders', 'thisMonthSales', 'monthGrowth',
            'salesByPayment', 'dailySales', 'monthlySales', 
            'topCustomers', 'topProducts', 'customers', 'dateFrom', 'dateTo'
        ));
    }

    public function purchases(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $query = Purchase::whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->with(['supplier', 'items.product']);

        // Apply additional filters
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'completed');
        }

        $purchases = $query->latest()->paginate(50);

        // Statistics
        $totalPurchases = Purchase::whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('total');
        
        $totalOrders = Purchase::whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->count();
        
        $averageOrder = $totalOrders > 0 ? $totalPurchases / $totalOrders : 0;

        // Today's purchases
        $todayPurchases = Purchase::whereDate('purchase_date', today())
            ->where('status', 'completed')
            ->sum('total');

        // This month purchases
        $thisMonthPurchases = Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->where('status', 'completed')
            ->sum('total');

        // Last month for comparison
        $lastMonthPurchases = Purchase::whereMonth('purchase_date', now()->subMonth()->month)
            ->whereYear('purchase_date', now()->subMonth()->year)
            ->where('status', 'completed')
            ->sum('total');
        
        $monthGrowth = $lastMonthPurchases > 0 ? (($thisMonthPurchases - $lastMonthPurchases) / $lastMonthPurchases) * 100 : 0;

        // Purchases by supplier
        $purchasesBySupplier = Purchase::select('supplier_id', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('supplier_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get()
            ->load('supplier');

        // Daily purchases trend
        $dailyPurchases = Purchase::select(DB::raw('DATE(purchase_date) as date'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly purchases trend (last 12 months)
        $monthlyPurchases = Purchase::select(DB::raw('DATE_FORMAT(purchase_date, "%Y-%m") as month'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->where('status', 'completed')
            ->where('purchase_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top products purchased
        $topProducts = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('products', 'purchase_items.product_id', '=', 'products.id')
            ->whereBetween('purchases.purchase_date', [$dateFrom, $dateTo])
            ->where('purchases.status', 'completed')
            ->select('products.id', 'products.name', DB::raw('SUM(purchase_items.quantity) as total_quantity'), DB::raw('SUM(purchase_items.total) as total_cost'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_cost', 'desc')
            ->limit(10)
            ->get();

        // Status breakdown
        $statusBreakdown = Purchase::select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->groupBy('status')
            ->get();

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('reports.purchases', compact(
            'purchases', 'totalPurchases', 'totalOrders', 'averageOrder',
            'todayPurchases', 'thisMonthPurchases', 'monthGrowth',
            'purchasesBySupplier', 'dailyPurchases', 'monthlyPurchases',
            'topProducts', 'statusBreakdown', 'suppliers', 'dateFrom', 'dateTo'
        ));
    }

    public function inventory(Request $request)
    {
        $query = Product::with(['category', 'warehouse']);

        // Filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereRaw('stock_quantity <= low_stock_alert');
            } elseif ($request->stock_status === 'out') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($request->stock_status === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->paginate(50);

        // Statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        
        $totalStockValue = Product::sum(DB::raw('stock_quantity * cost_price'));
        $totalSellingValue = Product::sum(DB::raw('stock_quantity * selling_price'));
        $potentialProfit = $totalSellingValue - $totalStockValue;

        $lowStockCount = Product::whereRaw('stock_quantity <= low_stock_alert')
            ->where('stock_quantity', '>', 0)
            ->count();
        
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();

        // Products by category
        $productsByCategory = Product::select('category_id', DB::raw('COUNT(*) as count'), DB::raw('SUM(stock_quantity * cost_price) as value'))
            ->with('category')
            ->groupBy('category_id')
            ->get();

        // Top products by value
        $topProductsByValue = Product::select('id', 'name', 'stock_quantity', 'cost_price', 'selling_price')
            ->selectRaw('(stock_quantity * cost_price) as stock_value')
            ->orderBy('stock_value', 'desc')
            ->limit(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::whereRaw('stock_quantity <= low_stock_alert')
            ->where('stock_quantity', '>', 0)
            ->where('is_active', true)
            ->orderBy('stock_quantity')
            ->limit(20)
            ->get();

        // Out of stock products
        $outOfStockProducts = Product::where('stock_quantity', '<=', 0)
            ->where('is_active', true)
            ->orderBy('name')
            ->limit(20)
            ->get();

        // Stock movement summary (last 30 days)
        $stockMovements = DB::table('stock_movements')
            ->select(DB::raw('type'), DB::raw('COUNT(*) as count'), DB::raw('SUM(quantity) as total_quantity'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('type')
            ->get();

        $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
        $warehouses = \App\Models\Warehouse::where('is_active', true)->orderBy('name')->get();

        return view('reports.inventory', compact(
            'products', 'totalProducts', 'activeProducts', 'totalStockValue', 
            'totalSellingValue', 'potentialProfit', 'lowStockCount', 'outOfStockCount',
            'productsByCategory', 'topProductsByValue', 'lowStockProducts', 
            'outOfStockProducts', 'stockMovements', 'categories', 'warehouses'
        ));
    }

    public function customers()
    {
        $customers = Customer::withCount(['sales' => function($query) {
            $query->where('status', 'completed');
        }])
        ->withSum(['sales' => function($query) {
            $query->where('status', 'completed');
        }], 'total')
        ->orderBy('name')
        ->paginate(20);

        return view('reports.customers', compact('customers'));
    }

    public function customerStatement(Request $request, Customer $customer)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $sales = Sale::where('customer_id', $customer->id)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->with(['items.product', 'user'])
            ->latest()
            ->paginate(50);

        $totalSales = Sale::where('customer_id', $customer->id)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->sum('total');
        
        $totalPaid = Sale::where('customer_id', $customer->id)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->sum('paid_amount');
        
        $totalDue = Sale::where('customer_id', $customer->id)
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('status', 'completed')
            ->sum('due_amount');

        // Opening balance (sales before date range)
        $openingBalance = Sale::where('customer_id', $customer->id)
            ->where('created_at', '<', $dateFrom . ' 00:00:00')
            ->where('status', 'completed')
            ->sum('due_amount');

        // Closing balance
        $closingBalance = Sale::where('customer_id', $customer->id)
            ->where('created_at', '<=', $dateTo . ' 23:59:59')
            ->where('status', 'completed')
            ->sum('due_amount');

        return view('reports.customer-statement', compact('customer', 'sales', 'totalSales', 'totalPaid', 'totalDue', 'openingBalance', 'closingBalance', 'dateFrom', 'dateTo'));
    }

    public function suppliers()
    {
        $suppliers = Supplier::where('is_active', true)
            ->withCount(['purchases' => function($query) {
                $query->where('status', 'completed');
            }])
            ->withSum(['purchases' => function($query) {
                $query->where('status', 'completed');
            }], 'total')
            ->orderBy('name')
            ->paginate(20);

        return view('reports.suppliers', compact('suppliers'));
    }

    public function supplierStatement(Request $request, Supplier $supplier)
    {
        $dateFrom = $request->get('date_from', now()->subMonths(3)->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $purchases = Purchase::where('supplier_id', $supplier->id)
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->with(['items.product', 'user'])
            ->latest()
            ->paginate(50);

        $totalPurchases = Purchase::where('supplier_id', $supplier->id)
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('total');
        
        $totalPaid = Purchase::where('supplier_id', $supplier->id)
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('paid_amount');
        
        $totalDue = Purchase::where('supplier_id', $supplier->id)
            ->whereBetween('purchase_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->sum('due_amount');

        // Opening balance (purchases before date range)
        $openingBalance = Purchase::where('supplier_id', $supplier->id)
            ->where('purchase_date', '<', $dateFrom)
            ->where('status', 'completed')
            ->sum('due_amount');

        // Closing balance
        $closingBalance = Purchase::where('supplier_id', $supplier->id)
            ->where('purchase_date', '<=', $dateTo)
            ->where('status', 'completed')
            ->sum('due_amount');

        return view('reports.supplier-statement', compact('supplier', 'purchases', 'totalPurchases', 'totalPaid', 'totalDue', 'openingBalance', 'closingBalance', 'dateFrom', 'dateTo'));
    }
}
