<?php

namespace App\Http\Controllers;

use App\Models\{
    Category,
    Expense,
    Product,
    Sale,
    Supplier,
    Member,
    Grn,
    Cus
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $menu = "Dashboard";

        if (Auth::user()->current_team_id == 1) {
            $total_category = Category::count();
            $total_product = Product::count();
            $total_supplier = Supplier::count();
            $total_member = Member::count();
            $today_total_sales = Grn::count();
            $today_total_return = Sale::count();
            $today_total_purchases = Grn::count();
            $today_total_expens = Expense::count();

            $first_date = date("Y-m-01");   # From day 1
            $last_date = date("Y-m-d");     # To day now

            $data_date = [];
            $data_income = [];

            while (strtotime($first_date) <= strtotime($last_date)) {
                $data_date[] = (int) substr($first_date, 8, 2);

                $today_total_purchases = Grn::whereDate('created_at', $first_date)->sum('grn_total');
                $total_expense = Expense::whereDate('created_at', $first_date)->sum('amount');
                $today_total_sales = Sale::whereDate('created_at', $first_date)->sum('total_price');
                $today_total_return = Sale::whereDate('created_at', $first_date)->sum('return_products');

                //HERE YOU CAN DO THE CALCULATIONS LIKE THIS,
                // $income = $total_sale - $total_purchase - $total_expense;
                // $data_income[] = $income;

                $first_date = date("Y-m-d", strtotime("+1 day", strtotime($first_date)));
            }

            return view("admin.dashboard", compact(
                "menu",
                "total_category",
                "total_product",
                "total_supplier",
                "total_member",
                "data_date",
                "data_income",
                "total_expense",
                "today_total_purchases",
                "today_total_sales",
                "today_total_return"

            ));
        } else {
            return view("cashier.dashboard", compact("menu"));
        }
    }
}
