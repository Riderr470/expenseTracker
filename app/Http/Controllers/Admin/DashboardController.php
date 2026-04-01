<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    //
    public function index()
    {

        $user = auth()->user();
        $expenses = Expense::where('user_id', $user->id);
        $monthlyAvgExpense = (clone $expenses)->selectRaw('SUM(total) as total, DATE_FORMAT(created_at, "%Y-%m") as month')
            ->groupBy('month')
            ->get()
            ->avg('total');

        $dailyAvgExpense = (clone $expenses)->selectRaw('SUM(total) as total, DATE(created_at) as day')
            ->groupBy('day')
            ->get()
            ->avg('total');

        $weeklyAvgExpense = (clone $expenses)->selectRaw('SUM(total) as total, YEAR(created_at) as year, WEEK(created_at) as week')
            ->groupBy('year', 'week')
            ->get()
            ->avg('total');

        return Inertia::render('Dashboard', [
            'dailyAvgExpense' => $dailyAvgExpense,
            'weeklyAvgExpense' => $weeklyAvgExpense,
            'monthlyAvgExpense' => $monthlyAvgExpense,
        ]);
    }
}
