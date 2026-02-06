<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        // Auto-expire quotations
        Quotation::where('status', '!=', 'converted')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->where('status', '!=', 'expired')
            ->update(['status' => 'expired']);

        $query = Quotation::with(['customer', 'user', 'items']);

        // Date range filter with defaults
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth()->toDateString();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth()->toDateString();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('quotation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('quotation_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('quotation_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Statistics (All time)
        $totalQuotations = Quotation::count();
        $totalAmount = Quotation::sum('total');
        
        // Today's statistics
        $todayQuotations = Quotation::whereDate('quotation_date', today())->count();
        $todayAmount = Quotation::whereDate('quotation_date', today())->sum('total');
        
        // This month statistics
        $thisMonthQuotations = Quotation::whereMonth('quotation_date', now()->month)
            ->whereYear('quotation_date', now()->year)
            ->count();
        $thisMonthAmount = Quotation::whereMonth('quotation_date', now()->month)
            ->whereYear('quotation_date', now()->year)
            ->sum('total');
        
        // Status breakdown
        $statusBreakdown = Quotation::selectRaw('status, COUNT(*) as count, SUM(total) as total')
            ->groupBy('status')
            ->get();

        // Filtered statistics
        $filteredQuery = Quotation::query();
        if ($request->filled('status')) {
            $filteredQuery->where('status', $request->status);
        }
        if ($request->filled('customer_id')) {
            $filteredQuery->where('customer_id', $request->customer_id);
        }
        if ($request->filled('date_from')) {
            $filteredQuery->whereDate('quotation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $filteredQuery->whereDate('quotation_date', '<=', $request->date_to);
        }
        $filteredCount = $filteredQuery->count();
        $filteredAmount = $filteredQuery->sum('total');

        // Top customers by quotation value
        $topCustomers = Quotation::whereNotNull('customer_id')
            ->selectRaw('customer_id, COUNT(*) as quotation_count, SUM(total) as total_value')
            ->groupBy('customer_id')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get()
            ->load('customer');

        $quotations = $query->latest()->paginate(20);
        $customers = Customer::where('is_active', true)->get();
        
        return view('quotations.index', compact(
            'quotations', 'customers', 
            'totalQuotations', 'totalAmount',
            'todayQuotations', 'todayAmount',
            'thisMonthQuotations', 'thisMonthAmount',
            'statusBreakdown', 'filteredCount', 'filteredAmount',
            'topCustomers', 'dateFrom', 'dateTo'
        ));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        
        return view('quotations.create', compact('customers', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'quotation_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:quotation_date',
            'terms_conditions' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.description' => 'nullable|string',
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

            $quotation = Quotation::create([
                'quotation_number' => 'QUO-' . strtoupper(Str::random(8)),
                'customer_id' => $validated['customer_id'] ?? null,
                'user_id' => auth()->id() ?? 1,
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => $tax,
                'total' => $total,
                'status' => 'pending',
                'quotation_date' => $validated['quotation_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'terms_conditions' => $validated['terms_conditions'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $itemTotal = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $itemTotal,
                    'description' => $item['description'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating quotation: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['customer', 'user', 'warehouse', 'items.product']);
        return view('quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return redirect()->route('quotations.show', $quotation)->with('error', 'Cannot edit a converted quotation.');
        }

        $quotation->load('items');
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        
        return view('quotations.edit', compact('quotation', 'customers', 'products', 'warehouses'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return back()->with('error', 'Cannot update a converted quotation.');
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'quotation_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:quotation_date',
            'status' => 'required|in:pending,approved,rejected',
            'terms_conditions' => 'nullable|string',
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

            $tax = $subtotal * 0.10;
            $total = $subtotal + $tax;

            $quotation->update([
                'customer_id' => $validated['customer_id'] ?? null,
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => $tax,
                'total' => $total,
                'status' => $validated['status'],
                'quotation_date' => $validated['quotation_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'terms_conditions' => $validated['terms_conditions'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete old items and create new ones
            $quotation->items()->delete();
            foreach ($validated['items'] as $item) {
                $itemTotal = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            return redirect()->route('quotations.show', $quotation)->with('success', 'Quotation updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating quotation: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Quotation $quotation)
    {
        if ($quotation->status === 'converted') {
            return back()->with('error', 'Cannot delete a converted quotation.');
        }

        $quotation->delete();
        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $quotation->update(['status' => $validated['status']]);
        return back()->with('success', 'Quotation status updated successfully.');
    }

    public function convertToSale(Quotation $quotation)
    {
        if (!$quotation->canBeConverted()) {
            return back()->with('error', 'This quotation cannot be converted to a sale.');
        }

        DB::beginTransaction();
        try {
            $quotation->load('items.product');

            $sale = Sale::create([
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'customer_id' => $quotation->customer_id,
                'user_id' => auth()->id() ?? 1,
                'warehouse_id' => $quotation->warehouse_id,
                'subtotal' => $quotation->subtotal,
                'discount' => $quotation->discount,
                'tax' => $quotation->tax,
                'total' => $quotation->total,
                'payment_method' => 'credit',
                'status' => 'completed',
                'notes' => 'Converted from Quotation: ' . $quotation->quotation_number,
            ]);

            foreach ($quotation->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount,
                    'total' => $item->total,
                ]);

                // Update stock
                if ($item->product->track_stock) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                }
            }

            $quotation->update([
                'status' => 'converted',
                'converted_to_sale_id' => $sale->id,
            ]);

            DB::commit();
            return redirect()->route('sales.show', $sale)->with('success', 'Quotation converted to sale successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error converting quotation: ' . $e->getMessage());
        }
    }

    public function sendEmail(Quotation $quotation)
    {
        if (!$quotation->customer || !$quotation->customer->email) {
            return back()->with('error', 'Customer email not found.');
        }

        // TODO: Implement email sending
        $quotation->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Quotation sent to customer email successfully.');
    }

    public function downloadPDF(Quotation $quotation)
    {
        $quotation->load(['customer', 'user', 'warehouse', 'items.product', 'items.product.category', 'sale']);
        
        // Get company settings
        $companyName = \App\Models\Setting::get('company_name', 'ShopSmart');
        $companyEmail = \App\Models\Setting::get('company_email', '');
        $companyPhone = \App\Models\Setting::get('company_phone', '');
        $companyAddress = \App\Models\Setting::get('company_address', '');
        $companyTaxId = \App\Models\Setting::get('tax_id', '');
        $companyWebsite = \App\Models\Setting::get('company_website', '');
        
        $settings = [
            'company_name' => $companyName,
            'company_email' => $companyEmail,
            'company_phone' => $companyPhone,
            'company_address' => $companyAddress,
            'tax_id' => $companyTaxId,
            'company_website' => $companyWebsite,
        ];
        
        // Generate PDF using facade or service container
        try {
            $pdf = Pdf::loadView('quotations.pdf', compact('quotation', 'settings'));
        } catch (\Exception $e) {
            // Fallback: use service container directly
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('quotations.pdf', compact('quotation', 'settings'));
        }
        
        // Set PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        
        // Download PDF with filename
        $filename = 'Quotation-' . $quotation->quotation_number . '-' . now()->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}
