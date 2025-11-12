<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\CashierShiftController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GrnController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\QuickBooksController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KitchenController;

// Login
Route::get("/", fn() => redirect()->route("login"));

//Logout
Route::post('/logout', function () {
    Auth::logout();

    // Invalidate session and regenerate token
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    // Redirect to login with a full reload
    return redirect('/login')->withHeaders([
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
})->name('logout');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, "index"])->name("dashboard.index");

        //Quick-Books
        Route::get('/qbo/connect', [QuickBooksController::class, 'connect']);
        Route::get('/qbo/callback', [QuickBooksController::class, 'callback']);

    Route::middleware(['level:1'])->group(function (){
        // Category
        Route::resource('/category', CategoryController::class);
        Route::get('/category/data', [CategoryController::class, "data"])->name("category.data");


        // Product
        Route::resource('/product', ProductController::class);
        Route::get('/product/data', [ProductController::class, "data"])->name("product.data");
        Route::post('/product/delete-selected', [ProductController::class, "deleteSelected"])->name("product.deleteSelected");
        Route::post('/product/print-barcode', [ProductController::class, "printBarcode"])->name("product.printBarcode");
        Route::get('/product/autocode', [ProductController::class, 'autoCode'])->name('product.autocode');

        // Member
        Route::resource('/member', MemberController::class);
        Route::get('/member/data', [MemberController::class, "data"])->name("member.data");
        Route::post('/member/delete-selected', [MemberController::class, "deleteSelected"])->name("member.deleteSelected");
        Route::post('/member/print-member', [MemberController::class, "printMember"])->name("member.printMember");

        // Supplier
        Route::resource('/supplier', SupplierController::class);
        Route::get('/supplier/data', [SupplierController::class, "data"])->name("supplier.data");
        Route::get('/suppliers/all', [SupplierController::class, 'getAll'])->name('suppliers.all');
        //Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');

        // PO
        Route::resource('po', PurchaseOrderController::class);
        Route::get('/po', [PurchaseOrderController::class, 'index'])->name('po.index');//view po oders on table
        Route::get('/po/create', [PurchaseOrderController::class, 'create'])->name('po.create');// create new po
        Route::delete('/po/{po}', [PurchaseOrderController::class, 'destroy'])->name('po.destroy');// to delete
        Route::get('/po/next-number', [PurchaseOrderController::class, 'getNextPoNumber']);// this is generate po numer auto and display
        Route::get('/po/{po}', [PurchaseOrderController::class, 'show'])->name('po.show');// to view pdf

        // GRN
        Route::get('/grn', [GrnController::class, 'index'])->name('grn.index');
        Route::get('/grn/create', [GrnController::class, 'create'])->name('grn.create');
        Route::get('/grn/{grn}', [GrnController::class, 'show'])->name('grn.show');
        Route::get('/grn/{grn}/edit', [GrnController::class, 'edit'])->name('grn.edit');
        Route::post('/grn', [GrnController::class, 'store'])->name('grn.store');
        Route::put('/grn/{grn}', [GrnController::class, 'update'])->name('grn.update');
        Route::delete('/grn/{grn}', [GrnController::class, 'destroy'])->name('grn.destroy');

        // Expense
        Route::resource('/expense', ExpenseController::class);
        Route::get('/expense/data', [ExpenseController::class, "data"])->name("expense.data");

        // Sale
        Route::get('/sale/data', [SaleController::class, "data"])->name("sale.data");
        Route::post('/sale/store', [SaleController::class, 'store'])->name('sale.store');
        //Route::resource('/sale', SaleController::class)->except("edit", "update");

        // Report
        Route::get('/report/data/{first_date}/{last_date}', [ReportController::class, "data"])->name("report.data");
        Route::resource('/report', ReportController::class)->except("create", "store", "edit", "update", "destroy");
        Route::get('/report/pdf/{first_date}/{last_date}', [ReportController::class, "exportPdf"])->name("report.exportPdf");

        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        // New route to handle report generation
        Route::get('/reports/generate', [ReportsController::class, 'generate'])->name('reports.generate');
        // Admin dashboard charts
        Route::get('/reports/income-chart', function () {
                return view('reports.income_chart');
            })->name('reports.income-chart');
        Route::get('/reports/income-chart-data', [ReportsController::class, 'incomeChartData'])->name('reports.income-chart-data');

        //supplier_report
        Route::get('/reports/supplier_report', [ReportsController::class, 'supplier_report'])->name('reports.supplier_report');
        Route::get('reports/supplier_exportPdf/{first_date?}/{last_date?}',[ReportsController::class, 'supplier_report_pdf'])->name('reports.supplier_exportPdf');

        //product_report
        Route::get('/reports/product_report', [ReportsController::class, 'product_report'])->name('reports.product_report');
        Route::get('reports/product_exportPdf', [ReportsController::class, 'product_exportPdf'])->name('reports.product_exportPdf');

        //customer_report
        Route::get('/reports/customer_report', [ReportsController::class, 'customer_report'])->name('reports.customer_report');
        Route::get('reports/customer_exportPdf/{first_date?}/{last_date?}',[ReportsController::class, 'customer_exportPdf'])->name('reports.customer_exportPdf');

        // User
        Route::get('/user/data', [UserController::class, "data"])->name("user.data");
        Route::resource('/user', UserController::class)->except("create");

        // Setting
        Route::resource('/setting', SettingController::class)->except("create", "store", "edit", "destroy");

        // Cashier
        Route::get('/cashier', [CashierController::class, "index"])->name("cashier.index");
        Route::get('/cashier/print/{sale}', [CashierController::class, 'print'])->name('cashier.print');
        Route::get('/cashier/drafts', [CashierController::class, 'getDraftSales'])->name('cashier.drafts');
        Route::get('/cashier/drafts/{id}', [CashierController::class, 'getDraftSale']);
        Route::get('/sales/customer/{id}', [SaleController::class, 'getCustomerSales'])->name('sales.customer');
        Route::post('/cashier', [CashierController::class, "store"])->name("cashier.store");
        Route::post('/sales/return', [SaleController::class, 'storeReturn'])->name('sales.return');

        // Cashier Shift
        Route::prefix('cashier_shifts')->name('cashierShifts.')->group(function () {
        Route::get('/', [CashierShiftController::class, 'index'])->name('index');
        Route::get('/data', [CashierShiftController::class, 'data'])->name('data');
        Route::post('/', [CashierShiftController::class, 'store'])->name('store');
        Route::post('/{id}/close', [CashierShiftController::class, 'close'])->name('close');
        Route::delete('/{id}', [CashierShiftController::class, 'destroy'])->name('destroy');
        });

        //Kitchen
        Route::prefix('kitchen')->group(function () {
        Route::get('/', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::get('/create', [KitchenController::class, 'create'])->name('kitchen.create');
        Route::post('/kitchen/store', [KitchenController::class, 'store'])->name('kitchen.store');

        });

    });

    Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    ])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, "index"])->name("dashboard.index");

        //Quick-Books
        Route::get('/qbo/connect', [QuickBooksController::class, 'connect']);
        Route::get('/qbo/callback', [QuickBooksController::class, 'callback']);

    Route::middleware(['level:1,2'])->group(function () {
        // Cashier
        Route::get('/cashier', [CashierController::class, "index"])->name("cashier.index");
        Route::post('/cashier', [CashierController::class, "store"])->name("cashier.store");
        Route::get('/cashier/print/{sale}', [CashierController::class, 'print'])->name('cashier.print');
        Route::get('/cashier/drafts', [CashierController::class, 'getDraftSales'])->name('cashier.drafts');
        Route::get('/cashier/drafts/{id}', [CashierController::class, 'getDraftSale']);
        Route::get('/sales/customer/{id}', [SaleController::class, 'getCustomerSales'])->name('sales.customer');
        Route::post('/sales/return', [SaleController::class, 'storeReturn'])->name('sales.return');

        // Member
        Route::get('/member/data', [MemberController::class, "data"])->name("member.data");
        Route::resource('/member', MemberController::class);
        Route::post('/member/delete-selected', [MemberController::class, "deleteSelected"])->name("member.deleteSelected");
        Route::post('/member/print-member', [MemberController::class, "printMember"])->name("member.printMember");
        });
    });

    // Transaction
    Route::get('/transaction/new', [SaleController::class, "create"])->name("transaction.new");
    Route::post('/sale/store', [SaleController::class, 'store'])->name('sale.store');
    Route::get('/transaction/{id}/data', [SaleDetailController::class, "data"])->name("transaction.data");
    Route::get('/transaction/load-form/{discount}/{total}/{accepted}', [SaleDetailController::class, "loadForm"])->name("transaction.loadForm");
    Route::resource('/transaction', SaleDetailController::class)->except("show", "edit");
    Route::get('/transaction/finish', [SaleController::class, "finish"])->name("transaction.finish");
    Route::get('/transaction/small-note', [SaleController::class, "smallNote"])->name("transaction.small_note");
    Route::get('/transaction/big-note', [SaleController::class, "bigNote"])->name("transaction.big_note");

    // Profile
    Route::get('/profile', [UserController::class, "profile"])->name("user.profile");
    Route::put('/profile', [UserController::class, "updateProfile"])->name("user.updateProfile");
});

