<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        $menu = 'Reports';
        return view('reports.index', compact('menu'));
    }
}
