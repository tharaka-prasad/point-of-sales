<?php

namespace App\Http\Controllers;

use App\Models\Kitchen;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $menu = 'Kitchen';
        $kitchen = Kitchen::latest()->get();
        return view('kitchen.index', compact('kitchen','menu'));
    }

    public function create()
    {
        return view('kitchen.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'qty' => 'required|numeric',
            'unit' => 'nullable',
            'issue_date' => 'required|date',
        ]);

        Kitchen::create([
            'item_name' => $request->item_name,
            'qty' => $request->qty,
            'unit' => $request->unit,
            'issue_date' => $request->issue_date,
            'issued_by' => auth()->user()->name ?? 'Admin',
        ]);

        return redirect()->route('kitchen.index')->with('success', 'Kitchen issue saved successfully');
    }

}
