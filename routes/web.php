<?php

use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Login
Route::get("/", fn() => redirect()->route("login"));

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, "index"])->name("dashboard.index");

    Route::middleware(['level:1'])->group(function () {
        // Category
        Route::get('/category/data', [CategoryController::class, "data"])->name("category.data");
        Route::resource('/category', CategoryController::class);

        // Product
        Route::get('/product/data', [ProductController::class, "data"])->name("product.data");
        Route::resource('/product', ProductController::class);
        Route::post('/product/delete-selected', [ProductController::class, "deleteSelected"])->name("product.deleteSelected");
        Route::post('/product/print-barcode', [ProductController::class, "printBarcode"])->name("product.printBarcode");
        Route::get('/product/autocode', [ProductController::class, 'autoCode'])->name('product.autocode');


        // Member
        Route::get('/member/data', [MemberController::class, "data"])->name("member.data");
        Route::resource('/member', MemberController::class);
        Route::post('/member/delete-selected', [MemberController::class, "deleteSelected"])->name("member.deleteSelected");
        Route::post('/member/print-member', [MemberController::class, "printMember"])->name("member.printMember");

        // Supplier
        Route::get('/supplier/data', [SupplierController::class, "data"])->name("supplier.data");
        Route::resource('/supplier', SupplierController::class);

        // Expense
        Route::get('/expense/data', [ExpenseController::class, "data"])->name("expense.data");
        Route::resource('/expense', ExpenseController::class);

        // Purchase
        Route::get('/purchase/data', [PurchaseController::class, "data"])->name("purchase.data");
        Route::get('/purchase/{id}/create', [PurchaseController::class, "create"]);
        Route::resource('/purchase', PurchaseController::class)->except("create");

        // Purchase Detail
        Route::get('/purchase_detail/{id}/data', [PurchaseDetailController::class, "data"])->name("purchase_detail.data");
        Route::get('/purchase_detail/load-form/{discount}/{total}', [PurchaseDetailController::class, "loadForm"])->name("purchase_detail.loadForm");
        Route::resource('/purchase_detail', PurchaseDetailController::class)->except("create", "show", "edit");

        // Sale
        Route::get('/sale/data', [SaleController::class, "data"])->name("sale.data");
        Route::resource('/sale', SaleController::class)->except("edit", "update");

        // Report
        Route::get('/report/data/{first_date}/{last_date}', [ReportController::class, "data"])->name("report.data");
        Route::resource('/report', ReportController::class)->except("create", "store", "edit", "update", "destroy");
        Route::get('/report/pdf/{first_date}/{last_date}', [ReportController::class, "exportPdf"])->name("report.exportPdf");

        // User
        Route::get('/user/data', [UserController::class, "data"])->name("user.data");
        Route::resource('/user', UserController::class)->except("create");

        // Setting
        Route::resource('/setting', SettingController::class)->except("create", "store", "edit", "destroy");

        // Cashier
        Route::get('/cashier', [CashierController::class, "index"])->name("cashier.index");
        Route::post('/cashier', [CashierController::class, "store"])->name("cashier.store");
        Route::get('/cashier/print/{sale}', [CashierController::class, 'print'])->name('cashier.print');

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
