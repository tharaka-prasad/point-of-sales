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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Login
Route::get("/", fn() => redirect()->route("login"));

//Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
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
        //Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');

        // PO
        Route::resource('po', PurchaseOrderController::class);
        Route::get('/po', [PurchaseOrderController::class, 'index'])->name('po.index');
        Route::get('/po/create', [PurchaseOrderController::class, 'create'])->name('po.create');
        Route::delete('/po/{po}', [PurchaseOrderController::class, 'destroy'])->name('po.destroy');
        Route::get('/po/next-number', [PurchaseOrderController::class, 'getNextPoNumber']);

        // GRN
        Route::get('/grn', [GrnController::class, 'index'])->name('grn.index');
        Route::get('/grn/create', [GrnController::class, 'create'])->name('grn.create');
        Route::post('/grn', [GrnController::class, 'store'])->name('grn.store');
        Route::get('/grn/{grn}', [GrnController::class, 'show'])->name('grn.show');
        Route::get('/grn/{grn}/edit', [GrnController::class, 'edit'])->name('grn.edit');
        Route::put('/grn/{grn}', [GrnController::class, 'update'])->name('grn.update');
        Route::delete('/grn/{grn}', [GrnController::class, 'destroy'])->name('grn.destroy');

        // Expense
        Route::get('/expense/data', [ExpenseController::class, "data"])->name("expense.data");
        Route::resource('/expense', ExpenseController::class);

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
        Route::get('/cashier/drafts', [CashierController::class, 'getDraftSales'])->name('cashier.drafts');

        // Cashier Shift
        Route::prefix('cashier_shifts')->name('cashierShifts.')->group(function () {
        Route::get('/', [CashierShiftController::class, 'index'])->name('index');
        Route::get('/data', [CashierShiftController::class, 'data'])->name('data');
        Route::post('/', [CashierShiftController::class, 'store'])->name('store');
        Route::post('/{id}/close', [CashierShiftController::class, 'close'])->name('close');
        Route::delete('/{id}', [CashierShiftController::class, 'destroy'])->name('destroy');

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
