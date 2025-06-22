<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        $expenses = Expense::where('user_id', $user->id)->orderBy('id', 'desc')->get();

        $query = Expense::where('user_id', $user->id);

        $dailyTotalExpense = (clone $query)
            ->whereDate('created_at', Carbon::today())
            ->sum('cost');

        // Weekly total expense (from Monday to Sunday)
        $weeklyTotalExpense = (clone $query)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('cost');

        // Monthly total expense (current month)
        $monthlyTotalExpense = (clone $query)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('cost');

        $data = [
            'expenses' => $expenses,
            'daily_total_expense' => $dailyTotalExpense,
            'weekly_total_expense' => $weeklyTotalExpense,
            'monthly_total_expense' => $monthlyTotalExpense,
        ];

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
