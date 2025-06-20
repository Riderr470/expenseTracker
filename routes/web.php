<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Add more routes here that need the same middleware
    // Route::get('/profile', function () {
    //     return Inertia::render('Profile');
    // })->name('profile');

    Route::prefix('expense')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('expense');
        ROute::post('/add', [ExpenseController::class, 'addExpense'])->name('expense.add');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
