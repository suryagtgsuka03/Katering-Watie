<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Rute halaman utama
Route::get('/', action: function () {
    return view('auth.login');
});

// Rute dashboard
Route::get('/dashboard', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rute untuk pengelolaan transaksi
Route::middleware('auth')->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transaction.store');
    Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transaction.edit');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transaction.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');
});

// Rute untuk pengelolaan profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::delete('/transaction/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');


// Rute otentikasi Laravel
require __DIR__ . '/auth.php';
