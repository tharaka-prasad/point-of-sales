<?php
namespace App\Http\Controllers;

use App\Models\CashierShift;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CashierShiftController extends Controller
{
    /** List view */
    public function index()
    {
        $menu = "Cashier Shifts";
        return view('cashier_shift.index', compact('menu'));
    }

    /** Data for DataTables */
    public function data()
    {
        $query = CashierShift::with('cashier');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('cashier', fn($row) => $row->cashier?->name ?? '-')
            ->addColumn('start_balance', fn($row) => number_format($row->start_balance, 2))
            ->addColumn('end_balance', fn($row) => $row->end_balance ? number_format($row->end_balance, 2) : '---')
            ->addColumn('total_amount', fn($row) => $row->total_amount ? number_format($row->total_amount, 2) : '---')
            ->addColumn('action', function ($row) {
                $btn = '';
                if (! $row->end_time) {
                    // Pass cashier name, start balance, and start time to JS function
                    $btn .= '<button class="btn btn-success btn-sm" onclick="closeShift(\''
                    . route('cashierShifts.close', $row->id) . '\', \''
                    . addslashes($row->cashier?->name ?? '') . '\', \''
                    . $row->start_balance . '\', \''
                    . $row->start_time . '\')">Close</button> ';
                }
                $btn .= '<button class="btn btn-danger btn-sm" onclick="deleteShift(\''
                . route('cashierShifts.destroy', $row->id) . '\')">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cashier_id'    => 'required|exists:users,id',
            'start_balance' => 'required|numeric',
            'start_time'    => 'required|date',
        ]);

        $shift = CashierShift::create([
            'cashier_id'    => $request->cashier_id,
            'start_balance' => $request->start_balance,
            'start_time'    => $request->start_time,
            'created_by'    => Auth::id(),
        ]);

        return response()->json(['success' => true, 'shift_id' => $shift->id]);
    }
    public function close(Request $request, $id)
    {
        $request->validate([
            'end_balance' => 'required|numeric',
        ]);

        $shift = CashierShift::findOrFail($id);

        $totalSales = Sale::where('user_id', $shift->cashier_id)
            ->whereBetween('created_at', [$shift->start_time, now()])
            ->selectRaw('COALESCE(SUM(total_price),0) as total')
            ->value('total');

        $shift->update([
            'end_time'     => now(),
            'end_balance'  => $request->end_balance,
            'total_amount' => $totalSales,
        ]);

        return response()->json(['success' => true, 'totalSales' => $totalSales]);
    }

    /** Delete shift */
    public function destroy($id)
    {
        $shift = CashierShift::findOrFail($id);
        $shift->delete();

        return response()->json(['success' => true]);
    }
}
