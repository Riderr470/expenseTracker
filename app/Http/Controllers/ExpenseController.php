<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    //
    public function index()
    {
        $data = Expense::all();

        return Inertia::render('expense/Index', ['data' => $data]);
    }

    public function addExpense(Request $request)
    {
        dd($request->all());
        $request->validate([
            'name' => 'nullable|string|max:255',
            'cost' => 'required|string',
        ]);

        $data = Expense::create([
            'name' => $request->name,
            'cost' => $request->cost,
        ]);
        return Inertia::render('expense');
    }
}
