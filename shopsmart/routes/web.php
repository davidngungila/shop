<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationReportController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\CapitalController;
use App\Http\Controllers\LiabilityController;
use App\Http\Controllers\BankReconciliationController;
use App\Http\Controllers\DeliveryNoteController;
use App\Http\Controllers\FinancialStatementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Authentication Routes (Public)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirect root to dashboard (or login if not authenticated)
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Products/Inventory
    Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('warehouses', WarehouseController::class);
Route::resource('stock-movements', StockMovementController::class);
    Route::get('/inventory/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');

    // Sales - Specific routes must come before resource routes
    Route::get('/sales/invoices', [SaleController::class, 'invoices'])->name('sales.invoices');
    Route::get('/sales/returns', [SaleController::class, 'returns'])->name('sales.returns');
    Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
    Route::get('/sales/{sale}/pdf', [SaleController::class, 'pdf'])->name('sales.pdf');
    Route::resource('sales', SaleController::class);
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/complete', [POSController::class, 'complete'])->name('pos.complete');

    // Quotations - Specific routes must come before resource routes
    Route::get('/quotations/reports', [QuotationReportController::class, 'overview'])->name('quotations.reports');
    Route::get('/quotations/reports/overview', [QuotationReportController::class, 'overview'])->name('quotations.reports.overview');
    Route::get('/quotations/{quotation}/pdf', [QuotationController::class, 'downloadPDF'])->name('quotations.pdf');
    Route::post('/quotations/{quotation}/update-status', [QuotationController::class, 'updateStatus'])->name('quotations.update-status');
    Route::post('/quotations/{quotation}/convert-to-sale', [QuotationController::class, 'convertToSale'])->name('quotations.convert-to-sale');
    Route::post('/quotations/{quotation}/send-email', [QuotationController::class, 'sendEmail'])->name('quotations.send-email');
    Route::resource('quotations', QuotationController::class);

    // Purchases
    Route::resource('purchases', PurchaseController::class);
Route::resource('suppliers', SupplierController::class);
    Route::get('/purchases/orders', [PurchaseController::class, 'orders'])->name('purchases.orders');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/loyalty', [CustomerController::class, 'loyalty'])->name('customers.loyalty');

    // Employees
    Route::resource('employees', EmployeeController::class);
Route::get('/employees/roles', [EmployeeController::class, 'roles'])->name('employees.roles');
    Route::get('/employees/attendance', [EmployeeController::class, 'attendance'])->name('employees.attendance');

    // Financial
    Route::get('/financial', [FinancialController::class, 'index'])->name('financial.index');
Route::get('/financial/income', [FinancialController::class, 'income'])->name('financial.income');
Route::resource('expenses', ExpenseController::class);
Route::resource('transactions', TransactionController::class);
    Route::get('/financial/profit-loss', [FinancialController::class, 'profitLoss'])->name('financial.profit-loss');

    // Chart of Accounts
    Route::resource('chart-of-accounts', ChartOfAccountController::class);

// Expense Categories
    Route::resource('expense-categories', ExpenseCategoryController::class);

    // Capital
    Route::resource('capital', CapitalController::class);

// Liabilities
    Route::resource('liabilities', LiabilityController::class);

    // Bank Reconciliation
    Route::resource('bank-reconciliations', BankReconciliationController::class);

// Delivery Notes
    Route::resource('delivery-notes', DeliveryNoteController::class);

    // Financial Statements
    Route::get('/financial-statements/profit-loss', [FinancialStatementController::class, 'profitLoss'])->name('financial-statements.profit-loss');
Route::get('/financial-statements/balance-sheet', [FinancialStatementController::class, 'balanceSheet'])->name('financial-statements.balance-sheet');
    Route::get('/financial-statements/trial-balance', [FinancialStatementController::class, 'trialBalance'])->name('financial-statements.trial-balance');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('/reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
Route::get('/reports/financial', [ReportController::class, 'sales'])->name('reports.financial');
Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
Route::get('/reports/customers/{customer}/statement', [ReportController::class, 'customerStatement'])->name('reports.customer-statement');
Route::get('/reports/suppliers', [ReportController::class, 'suppliers'])->name('reports.suppliers');
Route::get('/reports/suppliers/{supplier}/statement', [ReportController::class, 'supplierStatement'])->name('reports.supplier-statement');
    Route::get('/reports/profit-loss', [FinancialStatementController::class, 'profitLoss'])->name('reports.profit-loss');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingsController::class, 'index'])->name('index');
    Route::get('/general', [SettingsController::class, 'general'])->name('general');
    Route::post('/general', [SettingsController::class, 'updateGeneral'])->name('general.update');
    Route::get('/users', [SettingsController::class, 'users'])->name('users');
    Route::get('/roles', [SettingsController::class, 'roles'])->name('roles');
    Route::get('/system', [SettingsController::class, 'system'])->name('system');
    Route::post('/system', [SettingsController::class, 'updateSystem'])->name('system.update');
    Route::get('/financial', [SettingsController::class, 'financial'])->name('financial');
    Route::post('/financial', [SettingsController::class, 'updateFinancial'])->name('financial.update');
    Route::get('/inventory', [SettingsController::class, 'inventory'])->name('inventory');
    Route::post('/inventory', [SettingsController::class, 'updateInventory'])->name('inventory.update');
    Route::get('/quotations', [SettingsController::class, 'quotations'])->name('quotations');
    Route::post('/quotations', [SettingsController::class, 'updateQuotations'])->name('quotations.update');
    Route::get('/notifications', [SettingsController::class, 'notifications'])->name('notifications');
    Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications.update');
    Route::get('/backup', [SettingsController::class, 'backup'])->name('backup');
        Route::resource('user-roles', UserRoleController::class);
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::put('/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
        Route::post('/preferences', [ProfileController::class, 'updatePreferences'])->name('preferences.update');
        Route::get('/activity', [ProfileController::class, 'activity'])->name('activity');
        Route::get('/security', [ProfileController::class, 'security'])->name('security');
    });
});
