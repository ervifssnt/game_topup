<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UP STORE')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background: #1a1a1a;
        color: white;
        min-height: 100vh;
    }
    
    /* ========== Navigation Bar ========== */
    .navbar {
        background: #2a2a2a;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #3a3a3a;
    }
    
    .nav-left {
        display: flex;
        align-items: center;
        gap: 60px;
    }
    
    .logo {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: white;
    }
    
    .logo-icon {
        font-size: 28px;
        font-weight: 900;
        font-style: italic;
        color: #FF8C00;
    }
    
    .logo-text {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .nav-links {
        display: flex;
        gap: 40px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .nav-links li {
        margin: 0;
    }
    
    .nav-links a {
        color: #ccc;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: color 0.3s;
        padding: 8px 0;
        display: block;
    }
    
    .nav-links a:hover,
    .nav-links a.active {
        color: #FF8C00;
    }
    
    .nav-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .balance-display {
        background: linear-gradient(135deg, #2a2a2a 0%, #1f1f1f 100%);
        padding: 12px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
        border: 1px solid rgba(255, 140, 0, 0.2);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
        
    .balance-display span {
        color: #FFB84D;
        font-weight: 700;
        font-size: 15px;
        margin-left: 4px;
    }
    
    .btn-topup {
        padding: 11px 24px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .btn-topup:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.5);
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }
    
    .btn-2fa {
        padding: 11px 18px;
        background: #2a2a2a;
        border: 1px solid rgba(255, 140, 0, 0.3);
        border-radius: 10px;
        color: #FFB84D;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-2fa:hover {
        background: rgba(255, 140, 0, 0.1);
        border-color: #FF8C00;
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(255, 140, 0, 0.3);
    }
    
    .btn-logout {
        padding: 11px 24px;
        background: transparent;
        border: 1px solid #4a4a4a;
        border-radius: 10px;
        color: #ccc;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        white-space: nowrap;
    }
    
    .btn-logout:hover {
        background: rgba(239, 68, 68, 0.15);
        border-color: #ef4444;
        color: #ef4444;
        transform: translateY(-2px);
    }
    
    .btn-signin {
        padding: 10px 24px;
        background: #FF8C00;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .btn-signin:hover {
        background: #ff9d1f;
        transform: translateY(-2px);
    }
    
    /* ========== Main Content ========== */
    .main-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px;
    }
    
    /* ========== Footer ========== */
    .footer {
        background: #0a0a0a;
        padding: 60px 40px 30px;
        margin-top: 80px;
        border-top: 1px solid #2a2a2a;
    }
    
    .footer-content {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 60px;
        margin-bottom: 40px;
    }
    
    .footer-logo {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }
    
    .footer-logo-icon {
        font-size: 28px;
        font-weight: 900;
        font-style: italic;
        color: #FF8C00;
    }
    
    .footer-logo-text {
        font-size: 20px;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .footer-section h3 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 20px;
        color: white;
    }
    
    .footer-section ul {
        list-style: none;
    }
    
    .footer-section ul li {
        margin-bottom: 12px;
    }
    
    .footer-section ul li a {
        color: #999;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s;
    }
    
    .footer-section ul li a:hover {
        color: #FF8C00;
    }
    
    .social-links {
        display: flex;
        gap: 16px;
        margin-top: 20px;
    }
    
    .social-icon {
        width: 40px;
        height: 40px;
        background: #2a2a2a;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        text-decoration: none;
        font-size: 18px;
        transition: all 0.3s;
    }
    
    .social-icon:hover {
        background: #FF8C00;
        color: white;
        transform: translateY(-2px);
    }
    
    .footer-bottom {
        text-align: center;
        padding-top: 30px;
        border-top: 1px solid #2a2a2a;
        color: #666;
        font-size: 14px;
    }
    
    /* ========== Alert Messages ========== */
    .alert {
        padding: 16px 20px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .alert-success {
        background: #1e4620;
        border: 1px solid #2e7d32;
        color: #66bb6a;
    }
    
    .alert-error {
        background: #4a1a1a;
        border: 1px solid #d32f2f;
        color: #ef5350;
    }
    
    /* ========== Responsive Design ========== */
    @media (max-width: 1200px) {
        .footer-content {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            gap: 24px;
            padding: 20px;
        }
        
        .nav-left {
            flex-direction: column;
            gap: 24px;
            width: 100%;
        }
        
        .nav-links {
            flex-direction: column;
            gap: 16px;
            text-align: center;
            width: 100%;
        }
        
        .nav-right {
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }
        
        .balance-display,
        .btn-topup,
        .btn-2fa,
        .btn-logout {
            width: 100%;
            text-align: center;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .footer-content {
            grid-template-columns: 1fr;
            gap: 40px;
        }
    }
</style>
    @yield('styles')
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-icon">UP</span>
                <span class="logo-text">STORE</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('profile.dashboard') }}" class="{{ request()->routeIs('profile.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('profile.history') }}" class="{{ request()->routeIs('profile.history') ? 'active' : '' }}">Riwayat</a></li>
            </ul>
        </div>
        
<div class="nav-right">
    
@auth
    <div class="user-menu">
        <div class="user-balance">
            Balance: <span>Rp {{ number_format(Auth::user()->balance, 0, ',', '.') }}</span>
        </div>
        <a href="{{ route('topup.form') }}" class="btn-topup" style="background: #28a745; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
            + Top-Up
        </a>
        <a href="{{ route('2fa.show') }}" style="padding: 8px 16px; background: transparent; border: 1px solid #4a4a4a; border-radius: 6px; color: white; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.3s;">
            🔒 2FA
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
@else
                <a href="{{ route('login') }}" class="btn-signin">Sign in</a>
            @endauth
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>
                <div class="footer-logo">
                    <span class="footer-logo-icon">UP</span>
                    <span class="footer-logo-text">STORE</span>
                </div>
                <p style="color: #999; font-size: 14px; line-height: 1.6;">
                    Your trusted platform for game top-up and digital products. Fast, secure, and reliable service.
                </p>
                <div class="social-links">
                    <a href="#" class="social-icon">📘</a>
                    <a href="#" class="social-icon">📷</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Site map</h3>
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Riwayat</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Legality</h3>
                <ul>
                    <li><a href="#">Terms & Condition</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Twitter</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 UP STORE. All rights reserved.</p>
        </div>
    </footer>
    
    @yield('scripts')
</body>
</html>