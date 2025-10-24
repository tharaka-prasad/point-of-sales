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
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index()
    {
        $test = "test variable";
        $menu = "Reports";
        return view('reports.index', compact("menu","test"));
    }

    public function incomeChartData(Request $request)
    {
        $days = 7; // show last 7 days
        $startDate = Carbon::today()->subDays($days - 1);

        // Sales grouped by day
        $sales = DB::table('sales')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total_sales'))
            ->where('status', 'completed')
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total_sales', 'date');

        // Expenses grouped by day
        $expenses = DB::table('expenses')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total_expenses'))
            ->whereDate('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total_expenses', 'date');

        // Build chart data
        $dates = collect();
        $salesData = collect();
        $expenseData = collect();
        $incomeData = collect();

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $dates->push($date);

            $salesValue = $sales[$date] ?? 0;
            $expenseValue = $expenses[$date] ?? 0;
            $incomeValue = $salesValue - $expenseValue;

            $salesData->push($salesValue);
            $expenseData->push($expenseValue);
            $incomeData->push($incomeValue);
        }

        return response()->json([
            'labels' => $dates,
            'sales' => $salesData,
            'expenses' => $expenseData,
            'income' => $incomeData,
        ]);
    }

}
