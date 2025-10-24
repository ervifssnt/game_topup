# Secure Game Top-Up System

Laravel-based web application with comprehensive security implementations for cybersecurity coursework.

## 🔒 Security Features Implemented

### Authentication & Authorization
- **Bcrypt password hashing** (cost factor 12)
- **Two-Factor Authentication (2FA)** with Google Authenticator (TOTP)
  - QR code generation for easy setup
  - Recovery codes support
  - Compatible with Google Authenticator, Authy, Microsoft Authenticator
- **Token-based password reset** (October 2025 update)
  - Self-service password reset with email verification
  - 60-minute token expiration
  - One-time use tokens
  - Admin monitoring dashboard
- **Account lockout** after 5 failed login attempts (15-minute duration)
- **Rate limiting** (5 attempts per 60 seconds on sensitive endpoints)
- **Session security** (HttpOnly, Secure, SameSite cookies)
- **Session timeout** (30 minutes of inactivity)

### Input Security
- **Input validation and sanitization** using custom InputSanitizer helper
- **CSRF protection** on all forms with token validation
- **XSS prevention** via Laravel Blade auto-escaping
- **SQL injection protection** using Eloquent ORM and parameterized queries
- **Regex patterns** for username/email/phone validation
- **Form Request classes** for centralized validation

### Application Security
- **Security headers**: CSP, X-Frame-Options, X-XSS-Protection, X-Content-Type-Options
- **Comprehensive audit logging** for all critical actions
  - User authentication events
  - Administrative actions
  - Balance changes
  - Password resets
  - Security events (lockouts, 2FA changes)
- **Sensitive file blocking** (.htaccess, .env, server.php)
- **Password complexity requirements** (min 8 chars, uppercase, lowercase, number, special char)

### Admin Features
- User management (view, edit, delete, unlock accounts)
- Game and top-up option CRUD operations
- Transaction monitoring
- Top-up request approval workflow
- **Password reset activity monitoring** (NEW - October 2025)
- Audit log viewer with filtering
- Admin-initiated password resets

## 🚀 Quick Start

### Docker Setup (Recommended)

```bash
# 1. Clone the repository
git clone https://github.com/ervifssnt/game_topup.git
cd game_topup

# 2. Copy environment file
cp .env.example .env

# 3. Run automated setup script
./docker-setup.sh

# 4. Access the application
# - App: http://localhost:8000
# - phpMyAdmin: http://localhost:8080
```

**Default Credentials**:
- Admin: `admin@example.com` / `password123`
- User: `user@example.com` / `password123`

### Local Development Setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Database setup
touch database/database.sqlite
php artisan migrate:fresh --seed

# 4. Run development server
php artisan serve
# Visit: http://localhost:8000
```

## 📋 Technology Stack

- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Database**: MySQL 8.0 (Docker) / SQLite (local)
- **Web Server**: Nginx (Docker)
- **Frontend**: Blade templates, Vite, vanilla JavaScript
- **2FA**: pragmarx/google2fa-laravel
- **Containers**: Docker Compose (app, mysql, phpmyadmin, queue)

## 🎯 Key Features

### For Users
- Browse game catalog
- Purchase game currency top-ups
- Submit payment proof for top-up requests
- View transaction history
- Manage profile and balance
- Enable/disable Two-Factor Authentication
- Self-service password reset

### For Administrators
- Dashboard with statistics
- User management (CRUD, account lockout management)
- Game catalog management
- Top-up option pricing management
- Approve/reject top-up requests
- Monitor all transactions
- View comprehensive audit logs
- Monitor password reset activity

## 🔄 Recent Updates (October 2025)

### Password Reset System Overhaul
- **Changed from**: Admin-approved password reset requests
- **Changed to**: Token-based self-service password reset
- **Benefits**:
  - Improved user experience (no waiting for admin)
  - Industry-standard implementation
  - Enhanced security with time-limited tokens
  - Admin monitoring for security oversight
- **New Features**:
  - Email verification required
  - 60-minute token expiration
  - One-time use tokens
  - Password reset activity dashboard for admins
- **Testing**: All scenarios tested and passing (see TESTING_SUMMARY.md)

### Docker Improvements
- Added custom entrypoint script for nginx auto-start
- Fixed development container startup issues
- Improved file permission handling
- Enhanced container health checks

### Security Enhancements
- Email validation enforced on registration
- Type safety improvements in InputSanitizer
- Enhanced audit logging for password resets
- Removed duplicate password reset systems

## 📚 Documentation

- **README.md** (this file): Quick start and overview
- **PENTEST_REPORT.md**: Comprehensive security assessment results
- **TESTING_SUMMARY.md**: Password reset system test results
- **DOCKER-SECURITY.md**: Docker security considerations for academic review

## 🛠 Development Commands

```bash
# Run tests
php artisan test

# Clear all caches
php artisan optimize:clear

# Code style check/fix
./vendor/bin/pint

# View logs in real-time
php artisan pail

# Database operations
php artisan migrate:fresh --seed  # Fresh database with test data
php artisan tinker                # Interactive REPL
```

### Docker Commands

```bash
# Container management
docker compose up -d              # Start containers
docker compose down               # Stop containers
docker compose logs -f app        # View app logs
docker compose exec app sh        # Access app container shell

# Laravel commands in Docker
docker compose exec app php artisan migrate
docker compose exec app php artisan test
docker compose exec app composer install
```

## 🔐 Security Testing

This application has undergone comprehensive penetration testing:
- **Testing Date**: October 6, 2025
- **Environment**: Kali Linux → Windows 11 (Laravel)
- **Tools Used**: Burp Suite, sqlmap, XSSer, nikto, dirb
- **Results**: 2 vulnerabilities found and remediated
- **Status**: ✅ Production-ready
- **Report**: See `PENTEST_REPORT.md` for full details

### OWASP Top 10 Coverage
✅ All OWASP Top 10 2021 vulnerabilities addressed:
- A01: Broken Access Control
- A02: Cryptographic Failures
- A03: Injection
- A04: Insecure Design
- A05: Security Misconfiguration
- A06: Vulnerable and Outdated Components
- A07: Identification and Authentication Failures
- A08: Software and Data Integrity Failures
- A09: Security Logging and Monitoring Failures
- A10: Server-Side Request Forgery (SSRF)

## 📊 Project Statistics

- **Lines of Code**: ~5,000+
- **Controllers**: 7
- **Models**: 6
- **Middleware**: 4 custom security middleware
- **Migrations**: 17 database migrations
- **Views**: 35+ Blade templates
- **Routes**: 50+ protected endpoints
- **Tests**: 2 passing (more needed for full coverage)

## 🎓 Academic Context

This project was developed as coursework to demonstrate:
- Secure web application development practices
- OWASP Top 10 vulnerability mitigation
- Implementation of modern authentication systems (2FA, token-based password reset)
- Security auditing and monitoring
- Docker containerization for development and deployment
- Laravel framework best practices

## ⚠️ Important Notes

- **Development Environment**: Default credentials are for development only
- **Production Deployment**: Change all default passwords and configure proper email service
- **Email System**: Currently using `log` mailer (emails logged to `storage/logs/laravel.log`)
- **File Storage**: Local filesystem (consider S3 or similar for production)
- **HTTPS**: Force HTTPS middleware included (enable in production)

## 📞 Support

For issues or questions:
1. Check existing documentation files
2. Review Laravel 12 documentation: https://laravel.com/docs/12.x
3. Review OWASP security guidelines: https://owasp.org/

## 📄 License

This project is developed for educational purposes as part of cybersecurity coursework.

## 🔗 Repository

GitHub: https://github.com/ervifssnt/game_topup

---

**Last Updated**: October 2025
**Version**: 1.0.0
**Status**: Production-ready, security tested
