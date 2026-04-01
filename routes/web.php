<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Add more routes here that need the same middleware
    // Route::get('/profile', function () {
    //     return Inertia::render('Profile');
    // })->name('profile');

    Route::prefix('expense')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('expense');
        ROute::post('/add', [ExpenseController::class, 'addExpense'])->name('expense.add');
        Route::delete('/{expense}', [ExpenseController::class, 'destroy']);
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
