# Secure Game Top-Up System

Laravel-based web application with comprehensive security implementations for cybersecurity coursework.

## Security Features Implemented

### Authentication & Authorization
- Bcrypt password hashing (cost factor 12)
- Two-Factor Authentication (2FA) with Google Authenticator
- Account lockout after 5 failed login attempts
- Rate limiting (5 attempts per 60 seconds)
- Session security (HttpOnly, Secure, SameSite cookies)

### Input Security
- Input validation and sanitization
- CSRF protection on all forms
- XSS prevention (Laravel Blade escaping)
- SQL injection protection (Eloquent ORM)
- Regex patterns for username/email/phone validation

### Application Security
- Security headers (CSP, X-Frame-Options, X-XSS-Protection)
- Audit logging for all critical actions
- Sensitive file blocking (.htaccess, .env)
- Password complexity requirements

## Installation
```bash