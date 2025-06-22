<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    //
    public function index()
    {
        $data = Expense::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
        // dd($data);

        return Inertia::render('expense/Index', ['data' => $data]);
    }

    public function addExpense(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'cost' => 'required',
        ]);

        $cost = $request->cost;
        $qty = $request->qty;

        $data = Expense::create([
            'name' => $request->name,
            'user_id' => auth()->user()->id,
            'cost' => $cost,
            'qty' => $qty ?? 1,
            'total' =>  $cost * $qty ?? '',
        ]);
        // dd($data);
        Toastr::success('Expense added successfully');
        return redirect('expense');
    }
}
