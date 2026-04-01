<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
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
        $query = Expense::where('user_id', $user->id);

        $expenses = (clone $query)
            ->orderBy('id', 'desc')
            ->get();

        $dailyTotalExpense = (clone $query)
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        // Weekly total expense (from Monday to Sunday)
        $weeklyTotalExpense = (clone $query)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('total');

        // Monthly total expense (current month)
        $monthlyTotalExpense = (clone $query)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->sum('total');

        $data = [
            'expenses' => $expenses,
            'daily_total_expense' => $dailyTotalExpense,
            'weekly_total_expense' => $weeklyTotalExpense,
            'monthly_total_expense' => $monthlyTotalExpense,
        ];

        return Inertia::render('expense/Index', ['data' => $data]);
    }

    public function addExpense(ExpenseRequest $request)
    {
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

    public function destroy(Expense $expense)
    {
        // for use policies
        // $this->authorize('delete', $expense); 
        if ($expense->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $expense->delete();
            Toastr::success('Expense deleted successfully');
        } catch (\Exception $e) {
            Toastr::error('Failed to delete expense');
        }

        return back();
    }
}
