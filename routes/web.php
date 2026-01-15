<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Debug route - check configuration (remove after setup)
Route::get('/check-config', function () {
    return '<pre>' .
        'PHP Version: ' . phpversion() . '<br>' .
        'APP_ENV: ' . env('APP_ENV', 'NOT SET') . '<br>' .
        'APP_DEBUG: ' . (env('APP_DEBUG') ? 'true' : 'false') . '<br>' .
        'APP_KEY: ' . (env('APP_KEY') ? 'SET' : 'NOT SET') . '<br>' .
        'DB_HOST: ' . env('DB_HOST', 'NOT SET') . '<br>' .
        'DB_DATABASE: ' . env('DB_DATABASE', 'NOT SET') . '<br>' .
        'DB_USERNAME: ' . env('DB_USERNAME', 'NOT SET') . '<br>' .
        '</pre>';
});

// Setup route for initial deployment (remove after setup)
Route::get('/setup-database', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = Artisan::output();

        Artisan::call('db:seed', ['--force' => true]);
        $seedOutput = Artisan::output();

        return '<pre>Migration Output:<br>' . $migrateOutput . '<br><br>Seed Output:<br>' . $seedOutput . '<br><br>Database setup complete!</pre>';
    } catch (\Exception $e) {
        return '<pre>Error: ' . $e->getMessage() . '<br><br>File: ' . $e->getFile() . '<br>Line: ' . $e->getLine() . '</pre>';
    }
});

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaction Routes
    Route::resource('transactions', App\Http\Controllers\TransactionController::class);
    Route::get('/transactions-export', [App\Http\Controllers\TransactionController::class, 'export'])->name('transactions.export');

    // Recurring Transaction Routes
    Route::resource('recurring-transactions', App\Http\Controllers\RecurringTransactionController::class);
    Route::post('/recurring-transactions/{recurringTransaction}/toggle', [App\Http\Controllers\RecurringTransactionController::class, 'toggleStatus'])->name('recurring-transactions.toggle');

    // Account Routes
    Route::resource('accounts', App\Http\Controllers\AccountController::class);
    Route::post('/accounts/{account}/toggle', [App\Http\Controllers\AccountController::class, 'toggleStatus'])->name('accounts.toggle');

    // Category Routes
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::post('/categories/{category}/toggle', [App\Http\Controllers\CategoryController::class, 'toggleStatus'])->name('categories.toggle');

    // Reports Route
    Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports-export', [App\Http\Controllers\ReportsController::class, 'export'])->name('reports.export');

    Route::get('/settings', function () {
        return redirect()->route('dashboard');
    })->name('settings.index');
});

require __DIR__.'/auth.php';
