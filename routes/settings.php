<?php

use App\Http\Controllers\Settings\DashboardSettingController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth')->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('appearance');

    //dashboard
    Route::prefix('settings')->name('settings.')->middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardSettingController::class, 'index'])->name('dashboard');
        Route::post('dashboard/widgets', [DashboardSettingController::class, 'store'])->name('dashboard.widgets.store');
        Route::patch('dashboard/widgets/{widget}', [DashboardSettingController::class, 'update'])->name('dashboard.widgets.update');
        Route::delete('dashboard/widgets/{widget}', [DashboardSettingController::class, 'destroy'])->name('dashboard.widgets.destroy');
        Route::post('dashboard/widgets/reorder', [DashboardSettingController::class, 'reorder'])->name('dashboard.widgets.reorder');
    });
});
