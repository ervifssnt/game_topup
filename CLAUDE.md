# Game Top-Up System - Project Context

## Recent Changes (October 2025)

### Password Reset System Overhaul
**Date**: October 23, 2025

**What Changed:**
- ❌ **Removed**: Admin-approved password reset system
  - Old flow required admin intervention for all password resets
  - Used `password_reset_requests` table (now dropped)
  - Users had to submit requests and wait for admin approval
  - Admin would manually set new passwords

- ✅ **Implemented**: Standard Laravel token-based password reset
  - Self-service flow with 60-minute token expiration
  - Uses `password_reset_tokens` table
  - Secure token generation and validation
  - Generic responses prevent user enumeration
  - Email notifications (currently logged, ready for SMTP)

- 🔍 **Added**: Password Reset Activity monitoring
  - New admin panel page: `/admin/password-reset-activity`
  - View all password reset events with timestamps
  - Track IPs, user agents, and status
  - Color-coded badges for event types
  - Comprehensive audit logging

**Benefits:**
- ✅ Better user experience (instant password reset)
- ✅ Reduced admin workload (no manual intervention)
- ✅ Industry-standard security practices
- ✅ Comprehensive audit trail for compliance
- ✅ Maintains security while improving usability

**Technical Details:**
- Dropped table: `password_reset_requests`
- Removed files: `PasswordResetRequest.php` model, 3 view files
- New migration: `2025_10_23_155526_drop_password_reset_requests_table.php`
- New controller method: `AdminController::passwordResetActivity()`
- New view: `admin/password-reset-activity.blade.php`

---

## Project Overview

A **Laravel 12-based secure game top-up/credit purchase system** developed as a cybersecurity coursework project. This application demonstrates comprehensive security implementations including authentication, authorization, input validation, XSS/CSRF/SQL injection protection, audit logging, and 2FA.

**Purpose**: Academic project showcasing secure coding practices and defense-in-depth architecture for a web application handling financial transactions.

---

## Technology Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.2)
- **Database**: MySQL 8.0 (Production/Docker), SQLite (Development/Testing)
- **Authentication**: Custom implementation with 2FA (Google Authenticator)
- **Session Management**: Database-backed sessions
- **Queue System**: Database queue for background jobs
- **Caching**: Database/File cache driver

### Frontend
- **CSS Framework**: TailwindCSS 4.0
- **JavaScript**: Vanilla JS with Axios
- **Build Tool**: Vite 7.0
- **Template Engine**: Blade (Laravel)

### DevOps
- **Containerization**: Docker with multi-stage builds
- **Container Orchestration**: Docker Compose
- **Web Server**: Nginx (in container)
- **Process Manager**: Supervisor (manages nginx + PHP-FPM)
- **Database Admin**: phpMyAdmin (development only)

### Security Tools
- **2FA**: pragmarx/google2fa-laravel ^2.3
- **QR Code**: bacon/bacon-qr-code ^3.0
- **Password Hashing**: Bcrypt (cost factor 12)

### Testing
- **Framework**: PHPUnit 11.5.3
- **Test Types**: Unit and Feature tests
- **CI/CD**: Configured with composer scripts

---

## Project Structure

```
game_topup/
├── app/
│   ├── Helpers/
│   │   └── InputSanitizer.php          # Input sanitization utilities
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── AdminController.php # Admin panel controller
│   │   │   ├── AuthController.php      # Authentication & login
│   │   │   ├── GameController.php      # Game listings
│   │   │   ├── TransactionController.php # Transaction processing
│   │   │   ├── TopupController.php     # Top-up requests
│   │   │   ├── TwoFactorController.php # 2FA management
│   │   │   ├── PasswordResetController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   ├── SecurityHeaders.php     # CSP, HSTS, X-Frame-Options
│   │   │   ├── IsAdmin.php            # Admin authorization
│   │   │   ├── SessionTimeout.php     # Auto-logout
│   │   │   └── ForceHttps.php         # HTTPS enforcement
│   │   └── Requests/
│   │       ├── StoreGameRequest.php
│   │       ├── StoreUserRequest.php
│   │       ├── StoreTopupRequest.php
│   │       └── StoreTransactionRequest.php
│   ├── Models/
│   │   ├── User.php                   # User with 2FA & lockout
│   │   ├── Game.php
│   │   ├── TopupOption.php
│   │   ├── Transaction.php
│   │   ├── TopupRequest.php
│   │   └── AuditLog.php              # Security audit trail
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/                    # 17 migration files
│   │   ├── *_create_users_table.php
│   │   ├── *_create_games_table.php
│   │   ├── *_create_transactions_table.php
│   │   ├── *_create_audit_logs_table.php
│   │   ├── *_add_two_factor_fields_to_users_table.php
│   │   └── ... (complete database schema)
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   └── GameSeeder.php            # Seeds games and options
│   └── factories/
├── resources/
│   ├── views/
│   │   ├── admin/                    # Admin panel views
│   │   │   ├── dashboard.blade.php
│   │   │   ├── games/, users/, transactions/
│   │   │   ├── audit-logs.blade.php
│   │   │   ├── password-reset-activity.blade.php
│   │   │   └── topup-requests/
│   │   ├── transactions/
│   │   │   ├── checkout.blade.php
│   │   │   └── receipt.blade.php
│   │   ├── topup/
│   │   │   ├── request.blade.php
│   │   │   └── history.blade.php
│   │   ├── layouts/
│   │   │   └── main.blade.php
│   │   └── welcome.blade.php
│   ├── css/
│   └── js/
├── routes/
│   ├── web.php                       # All application routes
│   └── console.php
├── tests/
│   ├── Feature/
│   │   └── ExampleTest.php
│   └── Unit/
│       └── ExampleTest.php
├── docker/
│   ├── nginx/
│   │   ├── nginx.conf
│   │   └── default.conf
│   ├── supervisor/
│   │   └── supervisord.conf
│   └── mysql/
│       └── my.cnf
├── config/                           # Laravel configuration files
├── storage/                          # File uploads, logs, cache
├── public/                           # Public web root
├── vendor/                           # Composer dependencies
├── docker-compose.yml                # Container orchestration
├── Dockerfile                        # Multi-stage container build
├── docker-setup.sh                   # Automated setup script
├── composer.json                     # PHP dependencies
├── package.json                      # Node dependencies
├── phpunit.xml                       # Test configuration
├── vite.config.js                    # Frontend build config
├── .env.example                      # Environment template
├── .env.docker.example               # Docker environment template
├── README.md                         # Project documentation
├── PENTEST_REPORT.md                 # Security assessment report
└── DOCKER-SECURITY.md                # Docker security documentation
```

---

## Core Features

### 1. User Management
- User registration with validation
- Email, username, phone number fields
- Password complexity requirements (uppercase, lowercase, numbers, special chars)
- Account lockout after 5 failed login attempts (30-minute auto-unlock)
- User balance management
- Profile viewing and transaction history

### 2. Authentication & Security
- **Password Hashing**: Bcrypt with cost factor 12
- **Two-Factor Authentication (2FA)**:
  - Google Authenticator integration
  - QR code generation for setup
  - 8 recovery codes per user
  - Recovery code usage tracking
- **Password Reset**:
  - Token-based self-service reset (60-minute expiration)
  - Secure token generation and validation using `password_reset_tokens` table
  - Email notification (currently logged, ready for SMTP integration)
  - Generic responses prevent user enumeration
  - Admin monitoring via "Password Reset Activity" page
  - Comprehensive audit logging of all reset events
- **Session Security**:
  - HttpOnly, Secure, SameSite=strict cookies
  - Database-backed sessions
  - Configurable timeout (120 minutes default)
- **Rate Limiting**:
  - Login: 5 attempts per 60 seconds (per email + IP)
  - API endpoints: 60 requests per minute
  - Top-up requests: 5 per minute
- **Account Lockout**:
  - Automatic after 5 failed logins
  - 30-minute auto-unlock
  - Manual admin unlock capability

### 3. Authorization
- **Role-Based Access Control**:
  - Regular users
  - Admin users (`is_admin` flag)
- **Middleware Protection**:
  - `auth` - Requires authentication
  - `is_admin` - Requires admin role
  - All admin routes protected with both

### 4. Game & Top-Up System
- **Games Management** (Admin):
  - CRUD operations for games
  - Game name, description, image
  - Active/inactive status
- **Top-Up Options** (Admin):
  - Multiple price points per game
  - Amount and price configuration
  - Enable/disable options
- **Top-Up Flow** (Users):
  1. Browse games
  2. Select game and amount
  3. Upload payment proof
  4. Admin approval/rejection
  5. Balance credit on approval

### 5. Transaction System
- Transaction creation and tracking
- Transaction types: purchase, transfer, refund
- Status tracking: pending, completed, failed, refunded
- Detailed transaction history
- Receipt generation

### 6. Admin Panel
Complete administrative interface with:
- **Dashboard**: System overview
- **User Management**:
  - View all users
  - Edit user details
  - Lock/unlock accounts
  - Reset user passwords
  - View user activity
- **Game Management**: CRUD for games
- **Top-Up Options**: CRUD for pricing
- **Transaction Monitoring**: View all transactions
- **Top-Up Request Management**:
  - Approve/reject requests
  - View payment proof images
- **Password Reset Activity**:
  - Monitor all password reset events
  - View audit logs with timestamps, IPs, and user agents
  - Track successful and failed reset attempts
  - Color-coded status badges
- **Audit Logs**:
  - Complete activity tracking
  - User actions, IP addresses
  - Old/new value comparison

### 7. Security Features

#### Input Validation & Sanitization
- **Custom InputSanitizer Helper**:
  - `sanitizeString()` - Strip HTML tags
  - `sanitizeEmail()` - Email validation
  - `sanitizeUsername()` - Alphanumeric + underscore only
  - `sanitizePhone()` - Digits only
  - `sanitizeNumeric()` - Numbers only
  - `sanitizeUrl()` - URL validation
- **Validation Rules**:
  - Username: `^[a-zA-Z0-9_]+$` (regex)
  - Email: Laravel email validation
  - Phone: Format validation
  - Password: Complexity requirements enforced

#### Protection Against Attacks
- **SQL Injection**: Eloquent ORM with prepared statements
- **XSS (Cross-Site Scripting)**:
  - Blade template automatic escaping `{{ }}`
  - Input sanitization on all user inputs
  - Content Security Policy headers
- **CSRF (Cross-Site Request Forgery)**:
  - Laravel CSRF middleware on all state-changing routes
  - `@csrf` tokens in all forms
  - 419 response on missing/invalid tokens
- **Clickjacking**: X-Frame-Options: DENY header
- **MIME Sniffing**: X-Content-Type-Options: nosniff

#### Security Headers (SecurityHeaders Middleware)
```php
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=(), payment=()
Strict-Transport-Security: max-age=31536000; includeSubDomains; preload (HTTPS only)
Content-Security-Policy:
  - default-src 'self'
  - script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com
  - style-src 'self' 'unsafe-inline' https://fonts.googleapis.com
  - font-src 'self' https://fonts.gstatic.com
  - img-src 'self' data: https:
  - connect-src 'self'
  - frame-ancestors 'none'
  - base-uri 'self'
  - form-action 'self'
```

#### Sensitive File Protection
Routes explicitly blocking access to:
- `.htaccess` - Returns 403
- `server.php` - Returns 403
- `.env` - Returns 403

#### Audit Logging
- **AuditLog Model** tracks:
  - User ID (who performed action)
  - Action type (e.g., 'login', 'account_locked', 'transaction_created')
  - Model type and ID (what was affected)
  - Description (human-readable summary)
  - Old and new values (for updates)
  - IP address
  - User agent
  - Timestamp
- **Logged Events**:
  - Authentication (login, logout, failed attempts)
  - Account lockout/unlock
  - User creation/update/deletion
  - Transaction creation
  - Admin actions
  - Password resets

---

## Database Schema

### Users Table
```
id, username, email, phone, password_hash, balance,
is_admin, is_locked, locked_at, locked_reason,
failed_login_attempts, google2fa_secret, google2fa_enabled,
recovery_codes, remember_token, created_at, updated_at
```

### Games Table
```
id, name, description, image_url, is_active, created_at, updated_at
```

### Topup Options Table
```
id, game_id, amount, price, is_active, created_at, updated_at
```

### Transactions Table
```
id, user_id, game_id, topup_option_id, amount, price,
status, transaction_type, created_at, updated_at
```

### Topup Requests Table
```
id, user_id, game_id, topup_option_id, game_user_id,
proof_path, status, admin_notes, created_at, updated_at
```

### Audit Logs Table
```
id, user_id, action, model_type, model_id, description,
old_values, new_values, ip_address, user_agent, created_at
```

### Password Reset Requests Table
```
id, user_id, email, token, status, admin_id,
admin_notes, expires_at, created_at, updated_at
```

### Additional Tables
- `password_reset_tokens` - Laravel password reset tokens
- `login_attempts` - Failed login tracking
- `sessions` - Database session storage
- `cache` - Database cache storage
- `promo_codes` - Promo code system (future feature)
- `promo_code_usage` - Promo code usage tracking

---

## Routes Overview

### Public Routes
- `GET /` - Home/game listing
- `GET /api/search-games` - Game search API (rate limited)
- `GET /login`, `POST /login` - Authentication
- `GET /register`, `POST /register` - User registration
- `POST /logout` - Logout
- `GET /forgot-password`, `POST /forgot-password` - Password reset flow
- `GET /reset-password/{token}`, `POST /reset-password`
- `GET /2fa/verify`, `POST /2fa/login` - 2FA verification
- `GET /topup/{id}` - View game top-up page

### Protected Routes (Auth Required)
- `GET /profile` - User profile dashboard
- `GET /profile/history` - Transaction history
- `GET /transaction/{id}` - Transaction details
- `GET /topup/request`, `POST /topup/request` - Top-up request submission
- `GET /topup/history` - Top-up request history
- `POST /transaction` - Create transaction
- `GET /checkout/{id}`, `POST /checkout/process` - Checkout flow
- **2FA Management**:
  - `GET /2fa` - 2FA settings
  - `GET /2fa/enable` - Enable 2FA (QR code)
  - `POST /2fa/verify` - Verify 2FA setup
  - `POST /2fa/disable` - Disable 2FA
  - `GET /2fa/recovery` - View recovery codes
  - `POST /2fa/recovery/regenerate` - Regenerate codes
- `GET /password-reset-status` - View password reset request status

### Admin Routes (Auth + Admin Middleware)
Prefix: `/admin`

- `GET /` - Admin dashboard
- **Games**: CRUD operations
  - `GET /games`, `GET /games/create`, `POST /games`
  - `GET /games/{id}/edit`, `PUT /games/{id}`, `DELETE /games/{id}`
- **Top-Up Options**: CRUD operations
  - `GET /topup-options`, `GET /topup-options/create`, `POST /topup-options`
  - `GET /topup-options/{id}/edit`, `PUT /topup-options/{id}`, `DELETE /topup-options/{id}`
- **Users**: Management
  - `GET /users`
  - `GET /users/{id}/edit`, `PUT /users/{id}`, `DELETE /users/{id}`
  - `POST /users/{id}/unlock` - Unlock account
  - `POST /users/{id}/reset-password` - Admin password reset
- **Transactions**: `GET /transactions`
- **Audit Logs**: `GET /audit-logs`
- **Top-Up Requests**:
  - `GET /topup-requests`
  - `POST /topup-requests/{id}/approve`
  - `POST /topup-requests/{id}/reject`
  - `GET /topup-proof/{id}` - View payment proof
- **Password Reset Requests**:
  - `GET /password-reset-requests`
  - `POST /password-reset-requests/{id}/approve`
  - `POST /password-reset-requests/{id}/reject`

---

## Docker Setup

### Services
1. **app** (Laravel Application)
   - PHP 8.2 FPM on Alpine Linux
   - Nginx web server
   - Supervisor process manager
   - Port: 8000

2. **mysql** (Database)
   - MySQL 8.0
   - Persistent volume for data
   - Health checks enabled
   - Port: 3306 (exposed for development)

3. **phpmyadmin** (Database Admin - Development Only)
   - Latest phpMyAdmin
   - Port: 8080

4. **queue** (Background Job Worker)
   - Runs `php artisan queue:work`
   - Processes background jobs

### Docker Commands
```bash
# Setup (automated)
./docker-setup.sh

# Manual commands
docker compose up -d              # Start containers
docker compose down               # Stop containers
docker compose logs -f app        # View app logs
docker compose exec app bash      # Shell into app container
docker compose exec app php artisan migrate  # Run migrations
```

### Multi-Stage Dockerfile
- **Base stage**: Common setup, dependencies
- **Development stage**: Full dependencies, debug enabled
- **Production stage**: Optimized, cached configs, debug disabled

---

## Environment Configuration

### .env (Local Development)
- Database: SQLite
- Session: Database
- Cache: Database
- Queue: Database
- Debug: Enabled
- Bcrypt rounds: 12

### .env.docker (Docker Environment)
- Database: MySQL (service: mysql)
- All credentials in environment variables
- Passwords should be changed from defaults
- Template provided: `.env.docker.example`

### Key Environment Variables
```
APP_NAME=Laravel
APP_ENV=local|production
APP_KEY=<generated>
APP_DEBUG=true|false
APP_URL=http://localhost

DB_CONNECTION=mysql|sqlite
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_topup
DB_USERNAME=laravel_user
DB_PASSWORD=secure_password

BCRYPT_ROUNDS=12

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## Key Code Locations

### User Authentication
- **Login Logic**: [app/Http/Controllers/AuthController.php](app/Http/Controllers/AuthController.php)
  - Lines 29-90: `login()` method with rate limiting and lockout
  - Lines 92-120: 2FA verification
- **Account Lockout**: [app/Models/User.php:72-85](app/Models/User.php#L72-L85)
- **Failed Attempts**: [app/Models/User.php:105-114](app/Models/User.php#L105-L114)

### 2FA Implementation
- **Controller**: [app/Http/Controllers/TwoFactorController.php](app/Http/Controllers/TwoFactorController.php)
- **User Methods**: [app/Models/User.php:138-166](app/Models/User.php#L138-L166)
- **Recovery Codes**: Generated as 8x 8-character hex strings

### Input Sanitization
- **Helper Class**: [app/Helpers/InputSanitizer.php](app/Helpers/InputSanitizer.php)
- **Usage**: Throughout controllers, called before validation
- **Autoloaded**: Configured in composer.json files array

### Security Headers
- **Middleware**: [app/Http/Middleware/SecurityHeaders.php](app/Http/Middleware/SecurityHeaders.php)
- **Applied**: Globally via middleware stack

### Admin Panel
- **Main Controller**: [app/Http/Controllers/Admin/AdminController.php](app/Http/Controllers/Admin/AdminController.php)
- **Views**: [resources/views/admin/](resources/views/admin/)
- **Authorization**: [app/Http/Middleware/IsAdmin.php](app/Http/Middleware/IsAdmin.php)

### Transaction Processing
- **Controller**: [app/Http/Controllers/TransactionController.php](app/Http/Controllers/TransactionController.php)
- **Model**: [app/Models/Transaction.php](app/Models/Transaction.php)
- **Checkout Flow**: Lines 40-80 in TransactionController

### Audit Logging
- **Model**: [app/Models/AuditLog.php](app/Models/AuditLog.php)
- **Log Method**: `AuditLog::log($action, $description, $modelType, $modelId, $oldValues, $newValues)`
- **Usage**: Throughout application for security-critical actions

---

## Security Testing Results

From [PENTEST_REPORT.md](PENTEST_REPORT.md):

### Vulnerabilities Found & Fixed
1. **Information Disclosure - .htaccess exposure** (Critical - FIXED)
   - Issue: Configuration file accessible via HTTP
   - Fix: Route blocking returns 403 Forbidden

2. **Database Schema Error** (Medium - FIXED)
   - Issue: Missing `remember_token` column
   - Fix: Added via migration

### Security Controls Verified ✓
- **Authentication**: Bcrypt hashing, 2FA working
- **Rate Limiting**: 5 login attempts per 60 seconds enforced
- **Account Lockout**: Activates after 5 failed attempts
- **SQL Injection**: SQLMap testing found no vulnerabilities
- **XSS Protection**: All inputs properly escaped
- **CSRF Protection**: All forms protected, returns 419 on invalid token
- **Security Headers**: All headers present and correct
- **Input Validation**: Regex patterns block malicious input

### Testing Tools Used
- Nmap 7.95 (port scanning)
- Gobuster v3.6 (directory enumeration)
- SQLMap (SQL injection testing)
- Burp Suite Community (request manipulation)
- cURL (manual HTTP testing)

### OWASP Top 10 Coverage
All applicable vulnerabilities tested and mitigated.

---

## Development Workflow

### Local Development (Non-Docker)
```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# Run development server
composer dev  # Runs server, queue, logs, vite concurrently
# OR individually:
php artisan serve
php artisan queue:listen
npm run dev
```

### Docker Development
```bash
# Initial setup
./docker-setup.sh

# Access application
open http://localhost:8000      # Main app
open http://localhost:8080      # phpMyAdmin

# Default admin login
Email: admin@test.com
Password: password
```

### Testing
```bash
# Run all tests
composer test
# OR
php artisan test

# Specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# With coverage
php artisan test --coverage
```

### Code Quality
```bash
# Laravel Pint (code formatting)
./vendor/bin/pint

# View logs
php artisan pail  # Real-time log viewing

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## Important Notes for Claude

### Security Considerations
1. **Never Disable Security Features**: CSRF, XSS escaping, input validation are critical
2. **Always Use Eloquent ORM**: Direct SQL queries bypass protection
3. **Audit Log Everything**: Security-critical actions must be logged
4. **Validate Then Sanitize**: Laravel validation first, then InputSanitizer
5. **Rate Limit Sensitive Endpoints**: Authentication, API calls, state changes
6. **Never Store Plaintext Passwords**: Always use bcrypt/hash facade
7. **Password Reset Flow**: Uses standard Laravel token-based reset. Admin-approved reset system was removed in favor of self-service flow with audit log monitoring via the "Password Reset Activity" admin page.

### Laravel Best Practices
1. **Use Form Requests**: Create Request classes for complex validation
2. **Middleware for Authorization**: Don't check roles in controllers
3. **Eloquent Relationships**: Use `hasMany`, `belongsTo` instead of manual joins
4. **Resource Controllers**: Follow RESTful conventions
5. **Blade Components**: Reuse UI components for consistency

### Docker Considerations
1. **Development vs Production**: Current setup is development-optimized
2. **Secrets Management**: .env.docker not committed, use template
3. **File Permissions**: storage and bootstrap/cache need 775
4. **Database Persistence**: MySQL data in named volume `mysql_data`
5. **Supervisor**: Manages both nginx and PHP-FPM in app container

### Known Limitations
1. **Email**: Currently using `log` mailer (emails written to logs)
2. **File Storage**: Using local disk (not cloud storage)
3. **Promo Codes**: Tables exist but feature not implemented
4. **API**: No RESTful API endpoints (only web routes)
5. **Testing**: Example tests only, not comprehensive coverage

### When Making Changes
1. **Database Changes**: Always create migrations, never edit existing ones
2. **Routes**: Test route order (specific before wildcard)
3. **Middleware**: Test authorization doesn't break user access
4. **Views**: Use `{{ }}` for output (auto-escaping), never `{!! !!}` unless absolutely necessary
5. **Migrations**: Run `php artisan migrate:fresh --seed` to reset database

### Default Credentials (Development)
- **Admin**: admin@test.com / password
- **Regular User**: Create via registration

### Useful Artisan Commands
```bash
php artisan route:list          # List all routes
php artisan migrate:status      # Check migration status
php artisan tinker             # Interactive shell
php artisan db:seed --class=GameSeeder  # Seed specific seeder
php artisan queue:work         # Process queue jobs
php artisan make:controller    # Generate controller
php artisan make:model         # Generate model
php artisan make:migration     # Generate migration
php artisan make:middleware    # Generate middleware
```

---

## Project Goals & Context

This is a **cybersecurity coursework project** demonstrating:

1. **Secure Authentication**: Password hashing, 2FA, account lockout, token-based password reset
2. **Input Security**: Validation, sanitization, XSS/SQL injection prevention
3. **Authorization**: Role-based access control, middleware protection
4. **Audit Trail**: Comprehensive logging of security events
5. **Security Headers**: CSP, HSTS, X-Frame-Options, etc.
6. **Docker Security**: Container isolation, secrets management, network segmentation
7. **Penetration Testing**: Documented testing and remediation

The application successfully passed penetration testing with only 2 configuration issues (both fixed), demonstrating strong security implementation.

---

## Contact & Resources

- **Repository**: /home/ervi/Documents/coding/projects/game_topup
- **Documentation**:
  - [README.md](README.md) - Setup instructions
  - [PENTEST_REPORT.md](PENTEST_REPORT.md) - Security assessment
  - [DOCKER-SECURITY.md](DOCKER-SECURITY.md) - Docker security considerations
- **Laravel Version**: 12.x
- **PHP Version**: 8.2
- **License**: MIT

---

## Quick Reference

### File Upload Locations
- **Payment Proofs**: `storage/app/payment_proofs/`
- **Game Images**: `storage/app/public/games/` (symbolic link to `public/storage`)

### Session Configuration
- Driver: Database
- Lifetime: 120 minutes
- HttpOnly: true
- Secure: true (production)
- SameSite: strict

### Rate Limits
- Login: 5 per 60s per email+IP
- Top-up requests: 5 per minute
- API search: 60 per minute

### Account Lockout
- Trigger: 5 failed login attempts
- Duration: 30 minutes (auto-unlock)
- Manual unlock: Admin only

### Password Requirements
- Minimum 8 characters
- At least one uppercase letter
- At least one lowercase letter
- At least one number
- At least one special character

This documentation provides complete context for understanding, maintaining, and extending the Game Top-Up System.
