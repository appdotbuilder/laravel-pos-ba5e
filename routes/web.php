<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\PosController::class, 'show'])->name('dashboard');
    
    // POS Routes
    Route::get('pos', [App\Http\Controllers\PosController::class, 'index'])->name('pos.index');
    Route::post('pos', [App\Http\Controllers\PosController::class, 'store'])->name('pos.store');
    
    // Product Management (Admin & Owner only)
    Route::middleware('can:manage-products')->group(function () {
        Route::resource('products', App\Http\Controllers\ProductController::class);

    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
