<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\PasswordResetController;

// Block sensitive files
Route::get('/.htaccess', function () {
    abort(403, 'Forbidden');
});

Route::get('/server.php', function () {
    abort(403, 'Forbidden');
});

Route::get('/.env', function () {
    abort(403, 'Forbidden');
});

// Public routes
Route::get('/', [GameController::class, 'index'])->name('home');
Route::get('/api/search-games', [GameController::class, 'searchGames'])
    ->middleware('throttle:60,1')
    ->name('api.search-games');
    
// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// 2FA Login Verification
Route::get('/2fa/verify', [AuthController::class, 'show2FAVerify'])->name('2fa.verify');
Route::post('/2fa/login', [AuthController::class, 'verify2FA'])->name('2fa.login');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.dashboard');
    Route::get('/profile/history', [ProfileController::class, 'history'])->name('profile.history');
    
    // Top-up requests (MUST be before /topup/{id})
    Route::get('/topup/request', [TopupController::class, 'showForm'])->name('topup.form');
    Route::post('/topup/request', [TopupController::class, 'submitRequest'])->name('topup.submit');
    Route::get('/topup/history', [TopupController::class, 'history'])->name('topup.history');
    
    // Transactions
    Route::post('/transaction', [TransactionController::class, 'store'])->name('topup.store');
    Route::get('/checkout/{id}', [TransactionController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [TransactionController::class, 'processCheckout'])->name('checkout.process');
    
    // Two-Factor Authentication
    Route::get('/2fa', [TwoFactorController::class, 'show'])->name('2fa.show');
    Route::get('/2fa/enable', [TwoFactorController::class, 'enable'])->name('2fa.enable');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify'])->name('2fa.verify.post');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable'])->name('2fa.disable');
    Route::get('/2fa/recovery', [TwoFactorController::class, 'showRecoveryCodes'])->name('2fa.recovery');
    Route::post('/2fa/recovery/regenerate', [TwoFactorController::class, 'regenerateRecoveryCodes'])->name('2fa.recovery.regenerate');

    // Password Reset Status
    Route::get('/password-reset-status', [PasswordResetController::class, 'viewStatus'])->name('password.reset.status');
});

// Admin routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Games
    Route::get('/games', [AdminController::class, 'games'])->name('admin.games');
    Route::get('/games/create', [AdminController::class, 'createGame'])->name('admin.games.create');
    Route::post('/games', [AdminController::class, 'storeGame'])->name('admin.games.store');
    Route::get('/games/{id}/edit', [AdminController::class, 'editGame'])->name('admin.games.edit');
    Route::put('/games/{id}', [AdminController::class, 'updateGame'])->name('admin.games.update');
    Route::delete('/games/{id}', [AdminController::class, 'deleteGame'])->name('admin.games.delete');
    
    // Topup Options
    Route::get('/topup-options', [AdminController::class, 'topupOptions'])->name('admin.topup-options');
    Route::get('/topup-options/create', [AdminController::class, 'createTopupOption'])->name('admin.topup-options.create');
    Route::post('/topup-options', [AdminController::class, 'storeTopupOption'])->name('admin.topup-options.store');
    Route::get('/topup-options/{id}/edit', [AdminController::class, 'editTopupOption'])->name('admin.topup-options.edit');
    Route::put('/topup-options/{id}', [AdminController::class, 'updateTopupOption'])->name('admin.topup-options.update');
    Route::delete('/topup-options/{id}', [AdminController::class, 'deleteTopupOption'])->name('admin.topup-options.delete');
    
    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    Route::post('/users/{id}/unlock', [AdminController::class, 'unlockUser'])->name('admin.users.unlock');
    Route::post('/users/{id}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset-password');
    
    // Transactions
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
    
    // Audit Logs
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.audit-logs');
    
    // Top-up Requests
    Route::get('/topup-requests', [AdminController::class, 'topupRequests'])->name('admin.topup-requests');
    Route::post('/topup-requests/{id}/approve', [AdminController::class, 'approveTopup'])->name('admin.topup-requests.approve');
    Route::post('/topup-requests/{id}/reject', [AdminController::class, 'rejectTopup'])->name('admin.topup-requests.reject');

    // Admin Password Reset Requests
    Route::get('/password-reset-requests', [AdminController::class, 'passwordResetRequests'])->name('admin.password-reset-requests');
    Route::post('/password-reset-requests/{id}/approve', [AdminController::class, 'approvePasswordReset'])->name('admin.password-reset-requests.approve');
    Route::post('/password-reset-requests/{id}/reject', [AdminController::class, 'rejectPasswordReset'])->name('admin.password-reset-requests.reject');
});

// Wildcard route MUST be at the end to avoid catching specific routes
Route::get('/topup/{id}', [GameController::class, 'topup'])->name('topup');