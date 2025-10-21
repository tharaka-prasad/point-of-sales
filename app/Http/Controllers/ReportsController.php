<?php

namespace App\Http\Controllers;

use App\Models\{
    CashierShift,
    Category,
    Expense,
    Grn,
    GrnItems,
    Member,
    Product,
    PurchaseOrder,
    PurchaseOrderItem,
    Sale,
    SaleDetail,
    Supplier,
};
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        $test = "test variable";
        $menu = "Reports";
        return view('reports.index', compact("menu","test"));
    }

}
