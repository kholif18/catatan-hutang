<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebtController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    
    Route::resource('customers', CustomerController::class);
    Route::resource('debts', DebtController::class);

    Route::resource('payments', PaymentController::class)->only(['create', 'store']);
    Route::get('/payments/detail', [PaymentController::class, 'detail'])->name('payments.detail');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/admin/database', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/admin/database/export', [BackupController::class, 'export'])->name('backup.export');
    Route::post('/admin/database/import', [BackupController::class, 'import'])->name('backup.import');

});

require __DIR__.'/auth.php';
