<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
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

// Admin Routes (separate from auth group)
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/users/{id}/unlock', [AdminController::class, 'unlockUser'])->name('users.unlock');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('users.reset-password');
    
    // Games
    Route::get('/games', [AdminController::class, 'games'])->name('games');
    Route::get('/games/create', [AdminController::class, 'createGame'])->name('games.create');
    Route::post('/games', [AdminController::class, 'storeGame'])->name('games.store');
    Route::get('/games/{id}/edit', [AdminController::class, 'editGame'])->name('games.edit');
    Route::put('/games/{id}', [AdminController::class, 'updateGame'])->name('games.update');
    Route::delete('/games/{id}', [AdminController::class, 'deleteGame'])->name('games.delete');
    
    // Topup Options
    Route::get('/topup-options', [AdminController::class, 'topupOptions'])->name('topup-options');
    Route::get('/topup-options/create', [AdminController::class, 'createTopupOption'])->name('topup-options.create');
    Route::post('/topup-options', [AdminController::class, 'storeTopupOption'])->name('topup-options.store');
    Route::get('/topup-options/{id}/edit', [AdminController::class, 'editTopupOption'])->name('topup-options.edit');
    Route::put('/topup-options/{id}', [AdminController::class, 'updateTopupOption'])->name('topup-options.update');
    Route::delete('/topup-options/{id}', [AdminController::class, 'deleteTopupOption'])->name('topup-options.delete');
    
    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Transactions
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');

    // Audit Logs
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
});