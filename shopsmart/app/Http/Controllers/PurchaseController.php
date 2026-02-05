<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Purchase::with(['supplier', 'user', 'items.product']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Apply date range to filtered query for statistics
        $filteredQuery = \App\Models\Purchase::query();
        if ($request->filled('date_from')) {
            $filteredQuery->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $filteredQuery->whereDate('purchase_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $filteredQuery->where('status', $request->status);
        }
        if ($request->filled('supplier_id')) {
            $filteredQuery->where('supplier_id', $request->supplier_id);
        }

        // Statistics (All time)
        $totalPurchases = \App\Models\Purchase::count();
        $totalAmount = \App\Models\Purchase::sum('total');
        
        // Today's statistics
        $todayPurchases = \App\Models\Purchase::whereDate('purchase_date', today())->count();
        $todayAmount = \App\Models\Purchase::whereDate('purchase_date', today())->sum('total');
        
        // This month statistics
        $thisMonthPurchases = \App\Models\Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->count();
        $thisMonthAmount = \App\Models\Purchase::whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total');
        
        // Last month for comparison
        $lastMonthAmount = \App\Models\Purchase::whereMonth('purchase_date', now()->subMonth()->month)
            ->whereYear('purchase_date', now()->subMonth()->year)
            ->sum('total');
        $monthGrowth = $lastMonthAmount > 0 ? (($thisMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100 : 0;

        // Filtered statistics
        $filteredPurchases = $filteredQuery->count();
        $filteredAmount = $filteredQuery->sum('total');
        $averagePurchase = $filteredPurchases > 0 ? $filteredAmount / $filteredPurchases : 0;

        // Status breakdown (filtered)
        $statusBreakdownQuery = \App\Models\Purchase::query();
        if ($request->filled('date_from')) {
            $statusBreakdownQuery->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $statusBreakdownQuery->whereDate('purchase_date', '<=', $request->date_to);
        }
        $statusBreakdown = $statusBreakdownQuery
            ->selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();

        // Daily purchases trend (last 30 days)
        $dailyPurchases = \App\Models\Purchase::where('purchase_date', '>=', now()->subDays(30))
            ->selectRaw('DATE(purchase_date) as date, COUNT(*) as count, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top suppliers (filtered)
        $topSuppliersQuery = \App\Models\Purchase::whereNotNull('supplier_id');
        if ($request->filled('date_from')) {
            $topSuppliersQuery->whereDate('purchase_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $topSuppliersQuery->whereDate('purchase_date', '<=', $request->date_to);
        }
        $topSuppliers = $topSuppliersQuery
            ->selectRaw('supplier_id, COUNT(*) as purchase_count, SUM(total) as total_spent')
            ->groupBy('supplier_id')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get()
            ->load('supplier');

        // Top products purchased
        $topProductsQuery = \App\Models\PurchaseItem::whereHas('purchase', function($q) {
                // Base query
            });
        if ($request->filled('date_from')) {
            $topProductsQuery->whereHas('purchase', function($q) use ($request) {
                $q->whereDate('purchase_date', '>=', $request->date_from);
            });
        }
        if ($request->filled('date_to')) {
            $topProductsQuery->whereHas('purchase', function($q) use ($request) {
                $q->whereDate('purchase_date', '<=', $request->date_to);
            });
        }
        $topProducts = $topProductsQuery
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total) as total_cost')
            ->groupBy('product_id')
            ->orderBy('total_cost', 'desc')
            ->limit(5)
            ->get()
            ->load('product');

        $purchases = $query->latest('purchase_date')->paginate(20);
        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('name')->get();

        return view('purchases.index', compact(
            'purchases', 'totalPurchases', 'totalAmount', 
            'todayPurchases', 'todayAmount', 
            'thisMonthPurchases', 'thisMonthAmount', 'monthGrowth',
            'filteredPurchases', 'filteredAmount', 'averagePurchase',
            'statusBreakdown', 'suppliers', 'dailyPurchases', 
            'topSuppliers', 'topProducts', 'dateFrom', 'dateTo'
        ));
    }

    public function orders(Request $request)
    {
        $query = \App\Models\Purchase::with(['supplier', 'user', 'warehouse', 'items.product']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'ordered', 'partial']);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('purchase_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('purchase_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('purchase_date', '<=', $request->date_to);
        }

        // Supplier filter
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Statistics
        $totalOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])->count();
        $totalValue = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])->sum('total');
        $pendingOrders = \App\Models\Purchase::where('status', 'pending')->count();
        $overdueOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->where('expected_delivery_date', '<', now())
            ->count();
        
        // This month orders
        $thisMonthOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->count();
        $thisMonthValue = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->whereMonth('purchase_date', now()->month)
            ->whereYear('purchase_date', now()->year)
            ->sum('total');

        // Status breakdown
        $statusBreakdown = \App\Models\Purchase::selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->whereIn('status', ['pending', 'ordered', 'partial', 'completed', 'cancelled'])
            ->groupBy('status')
            ->get();

        // Daily orders trend (last 30 days)
        $dailyOrders = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->where('purchase_date', '>=', now()->subDays(30))
            ->selectRaw('DATE(purchase_date) as date, COUNT(*) as count, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top suppliers by order value
        $topSuppliers = \App\Models\Purchase::whereIn('status', ['pending', 'ordered', 'partial'])
            ->whereNotNull('supplier_id')
            ->selectRaw('supplier_id, COUNT(*) as order_count, SUM(total) as total_value')
            ->groupBy('supplier_id')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get()
            ->load('supplier');

        $purchases = $query->latest('purchase_date')->paginate(20);
        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('name')->get();

        return view('purchases.orders', compact(
            'purchases', 'totalOrders', 'totalValue', 'pendingOrders', 'overdueOrders', 
            'thisMonthOrders', 'thisMonthValue', 'statusBreakdown', 'suppliers', 
            'dailyOrders', 'topSuppliers', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = \App\Models\Supplier::where('is_active', true)->orderBy('name')->get();
        $products = \App\Models\Product::where('is_active', true)->with('category')->orderBy('name')->get();
        $warehouses = \App\Models\Warehouse::where('is_active', true)->orderBy('name')->get();
        
        return view('purchases.create', compact('suppliers', 'products', 'warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'purchase_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'nullable|in:pending,ordered,partial,completed,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $totalDiscount = 0;

            foreach ($validated['items'] as $item) {
                $itemTotal = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
                $subtotal += $itemTotal;
                $totalDiscount += ($item['discount'] ?? 0);
            }

            $tax = $subtotal * 0.10; // 10% tax
            $total = $subtotal + $tax;
            $paidAmount = $request->input('paid_amount', 0);
            $dueAmount = $total - $paidAmount;

            $purchase = \App\Models\Purchase::create([
                'purchase_number' => 'PO-' . strtoupper(Str::random(8)),
                'supplier_id' => $validated['supplier_id'],
                'user_id' => auth()->id(),
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => $tax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'status' => $validated['status'] ?? 'pending',
                'purchase_date' => $validated['purchase_date'],
                'expected_delivery_date' => $validated['expected_delivery_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $itemDiscount = $item['discount'] ?? 0;
                $itemSubtotal = $item['unit_price'] * $item['quantity'];
                $itemTotal = $itemSubtotal - $itemDiscount;
                
                \App\Models\PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = \App\Models\Purchase::with(['supplier', 'user', 'warehouse', 'items.product'])->findOrFail($id);
    {
        $purchase->load(['supplier', 'user', 'warehouse', 'items.product']);
        
        // Calculate payment progress
        $paymentProgress = $purchase->total > 0 ? ($purchase->paid_amount / $purchase->total) * 100 : 0;
        
        // Check if overdue
        $isOverdue = $purchase->expected_delivery_date && 
                     $purchase->expected_delivery_date < now() && 
                     in_array($purchase->status, ['pending', 'ordered', 'partial']);
        
        // Days until/since delivery
        $deliveryDays = null;
        if ($purchase->expected_delivery_date) {
            $deliveryDays = now()->diffInDays($purchase->expected_delivery_date, false);
        }
        
        return view('purchases.show', compact('purchase', 'paymentProgress', 'isOverdue', 'deliveryDays'));
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
    public function update(Request $request, $id)
    {
        $purchase = \App\Models\Purchase::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'sometimes|in:pending,ordered,partial,completed,received,paid,cancelled',
            'paid_amount' => 'sometimes|numeric|min:0|max:' . $purchase->total,
            'notes' => 'sometimes|nullable|string',
        ]);

        DB::beginTransaction();
        try {
            if (isset($validated['status'])) {
                $purchase->status = $validated['status'];
            }
            
            if (isset($validated['paid_amount'])) {
                $purchase->paid_amount = $validated['paid_amount'];
                $purchase->due_amount = $purchase->total - $purchase->paid_amount;
            }
            
            if (isset($validated['notes'])) {
                $purchase->notes = $validated['notes'];
            }
            
            $purchase->save();
            
            DB::commit();
            return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
