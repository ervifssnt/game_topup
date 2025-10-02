<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UP STORE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #2a2a2a 0%, #1a1a1a 100%);
            min-height: 100vh;
            display: flex;
            color: white;
        }
        
        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Left Side - Logo */
        .left-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1a1a1a;
            padding: 40px;
        }
        
        .logo-section {
            text-align: center;
        }
        
        .logo-section h2 {
            color: #888;
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 20px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .logo-icon {
            font-size: 48px;
            font-weight: 900;
            font-style: italic;
            letter-spacing: -2px;
        }
        
        .logo-text {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
        }
        
        /* Right Side - Form */
        .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #2a2a2a;
            padding: 40px;
        }
        
        .form-container {
            width: 100%;
            max-width: 440px;
        }
        
        .form-container h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #ccc;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background: #3a3a3a;
            border: 1px solid #4a4a4a;
            border-radius: 8px;
            color: white;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #FF8C00;
            background: #404040;
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #bbb;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        
        .forgot-password {
            color: #FF8C00;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #FF8C00;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background: #ff9d1f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.4);
        }
        
        .divider {
            text-align: center;
            margin: 24px 0;
            color: #888;
            font-size: 14px;
        }
        
        .btn-google {
            width: 100%;
            padding: 14px;
            background: #3a3a3a;
            border: 1px solid #4a4a4a;
            border-radius: 8px;
            color: white;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-google:hover {
            background: #404040;
            border-color: #5a5a5a;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #bbb;
        }
        
        .register-link a {
            color: white;
            text-decoration: underline;
            font-weight: 500;
        }
        
        .error-message {
            background: #ff4444;
            color: white;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        @media (max-width: 968px) {
            .container {
                flex-direction: column;
            }
            
            .left-side {
                min-height: 200px;
            }
            
            .right-side {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Side - Logo -->
        <div class="left-side">
            <div class="logo-section">
                <h2>Welcome to</h2>
                <div class="logo">
                    <span class="logo-icon">UP</span>
                    <span class="logo-text">STORE</span>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="right-side">
            <div class="form-container">
                <h1>Log In Member</h1>
                
                @if($errors->any())
                    <div class="error-message">
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" 
                               name="email" 
                               value="{{ old('email') }}"
                               placeholder="Enter your username or email"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                    </div>
                    
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="btn-login">Login</button>
                </form>
                
                <div class="divider">Don't have an account? <a href="{{ route('register') }}" style="color: white; text-decoration: underline;">Register</a></div>
                
                <button class="btn-google" onclick="alert('Google Sign-in coming soon!')">
                    Log in with Google
                </button>
            </div>
        </div>
    </div>
</body>
</html>