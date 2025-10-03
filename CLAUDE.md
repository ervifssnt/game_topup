# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Game Top-up Laravel Application** - a web platform for purchasing in-game currency/items for various games. Users can register, maintain a balance, browse games, select top-up options, and complete transactions.

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade templates with Tailwind CSS v4
- **Database**: SQLite (default), configurable to MySQL/PostgreSQL
- **Build Tool**: Vite with Laravel plugin
- **Session/Queue/Cache**: Database-backed

## Common Commands

### Development
```bash
# Start development server (runs server, queue, logs, and vite concurrently)
composer dev

# Or individual services:
php artisan serve                    # Start dev server
php artisan queue:listen --tries=1   # Run queue worker
php artisan pail --timeout=0         # View logs
npm run dev                          # Run vite dev server
```

### Testing
```bash
composer test                        # Run full test suite
php artisan test                     # Run tests directly
php artisan test --filter TestName   # Run specific test
```

### Database
```bash
php artisan migrate                  # Run migrations
php artisan migrate:fresh --seed     # Fresh migration with seeding
php artisan db:seed                  # Run seeders
php artisan db:seed --class=GameSeeder  # Run specific seeder
```

### Code Quality
```bash
vendor/bin/pint                      # Run Laravel Pint (code formatter)
```

### Asset Building
```bash
npm run build                        # Build for production
npm run dev                          # Watch and rebuild assets
```

## Architecture & Data Model

### Core Models & Relationships

1. **User** (`app/Models/User.php`)
   - Custom authentication using `password_hash` column (not default `password`)
   - Has wallet balance system with helper methods: `hasEnoughBalance()`, `deductBalance()`, `addBalance()`
   - Uses phone as primary login (unique), email is optional
   - Relationship: `hasMany(Transaction::class)`

2. **Game** (`app/Models/Game.php`)
   - Represents games available for top-up
   - Relationship: `hasMany(TopupOption::class)`
   - Relationship: `hasManyThrough(Transaction::class, TopupOption::class)`

3. **TopupOption** (`app/Models/TopupOption.php`)
   - Different coin/diamond packages for each game
   - Fields: `coins`, `amount`, `price`
   - Relationship: `belongsTo(Game::class)`, `hasMany(Transaction::class)`

4. **Transaction** (`app/Models/Transaction.php`)
   - Tracks user purchases with status workflow: `pending` → `paid` or `failed`
   - Stores `account_id` (user's game account), `coins`, `price`
   - Uses database row locking (`lockForUpdate()`) for payment processing
   - Helper methods: `isPending()`, `isPaid()`, `markAsPaid()`, `markAsFailed()`
   - Relationship: `belongsTo(User::class)`, `belongsTo(TopupOption::class)`

### Transaction Flow

1. User selects game and top-up option → Creates `pending` transaction (`TransactionController::store`)
2. User redirected to checkout page → Shows transaction details and balance check
3. User confirms payment → Transaction processing with DB transaction + row locking:
   - Validates transaction is still `pending`
   - Checks user balance sufficiency
   - Deducts balance from user
   - Marks transaction as `paid`
   - Uses `DB::beginTransaction()` and `lockForUpdate()` for concurrency safety

### Authentication System

- **Custom password field**: Uses `password_hash` instead of Laravel's default `password`
- **User model override**: `getAuthPassword()` returns `password_hash`
- **Login**: Email + password
- **Registration**: Username, phone (unique), password
- **Password Reset**: Full flow implemented with tokens table and email notifications
  - Routes: `/forgot-password`, `/reset-password/{token}`
  - Controller: `PasswordResetController`
  - Migration: `password_reset_tokens` table

### Route Structure

- **Guest routes** (`routes/web.php:10-21`): Login, register, password reset
- **Authenticated routes** (`routes/web.php:24-37`): Homepage, top-up, checkout, logout
- Route groups use Laravel's `guest` and `auth` middleware

## Important Implementation Details

### Database Transactions & Concurrency
- Payment processing uses `DB::beginTransaction()` with `lockForUpdate()` to prevent race conditions
- Always validate transaction status before processing to avoid double-payment
- Example pattern in `TransactionController::processCheckout()`:
  ```php
  $transaction = Transaction::where('id', $id)
      ->lockForUpdate()
      ->firstOrFail();
  if ($transaction->status !== 'pending') {
      throw new \Exception('Transaction already processed.');
  }
  ```

### Balance Management
- User balance operations should always use helper methods: `deductBalance()`, `addBalance()`
- Always check `hasEnoughBalance()` before deducting
- Balance changes must be wrapped in database transactions

### Validation Rules
- Phone: 10-15 digits, unique
- Password: min 6 characters, must be confirmed on registration
- Custom error messages in Indonesian for user-facing forms

### Database Schema
- Uses SQLite by default (check `.env.example`)
- Migrations are timestamped: `2025_10_02_*`
- Key tables: `users`, `games`, `topup_options`, `transactions`, `password_reset_tokens`

## Frontend Structure

- **Layout**: `resources/views/layouts/main.blade.php` (main layout), `resources/views/layout.blade.php` (alternate)
- **Views**:
  - Auth: `resources/views/auth/` (login, register, password reset)
  - Games: `resources/views/games/` (index, topup)
  - Transactions: `resources/views/transactions/checkout.blade.php`
- **Assets**: Entry points in `resources/css/app.css` and `resources/js/app.js`
- **Styling**: Tailwind CSS v4 with Vite plugin

## Development Notes

- XAMPP environment (Windows): Project located in `C:\xampp\htdocs\`
- Default mail driver is `log` - check `storage/logs` for password reset emails during development
- Queue driver is `database` - use `php artisan queue:listen` to process jobs
- Session/cache also use database backend
