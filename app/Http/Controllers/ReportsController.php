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
        $menu = 'Reports';
        return view('reports.index', compact('menu'));
    }

    public function generate(Request $request)
    {
        $reportType = $request->report_type;
        $dateRange = $request->date_range;
        $from = $request->from_date;
        $to = $request->to_date;

        switch ($reportType) {
            case 'daily_sales':
                $data = Sale::whereDate('created_at', today())->get();
                return view('reports.partials.daily_sales', compact('data'));

            case 'stock_report':
                $data = Product::all();
                return view('reports.partials.stock', compact('data'));

            default:
                return "<div class='alert alert-info'>Report not yet implemented.</div>";
        }
    }
}
