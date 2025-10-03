<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes (must be logged in)
Route::middleware('auth')->group(function () {
    // Homepage
    Route::get('/', [GameController::class, 'index'])->name('home');
    
    // Profile & Dashboard
    Route::get('/dashboard', [ProfileController::class, 'index'])->name('profile.dashboard');
    Route::get('/riwayat', [ProfileController::class, 'history'])->name('profile.history');
    
    // Topup
    Route::get('/topup/{id}', [GameController::class, 'topup'])->name('topup');
    Route::post('/topup', [TransactionController::class, 'store'])->name('topup.store');
    
    // Checkout
    Route::get('/checkout/{id}', [TransactionController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [TransactionController::class, 'processCheckout'])->name('checkout.process');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});