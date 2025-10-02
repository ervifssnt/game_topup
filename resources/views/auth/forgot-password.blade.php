<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - UP STORE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #1a1a1a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 520px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .logo-icon {
            font-size: 32px;
            font-weight: 900;
            font-style: italic;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .form-box {
            background: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 12px;
            padding: 50px 40px;
        }
        
        h1 {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
        }
        
        .form-group {
            margin-bottom: 24px;
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
        
        .btn-submit {
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
        
        .btn-submit:hover {
            background: #ff9d1f;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 140, 0, 0.4);
        }
        
        .back-link {
            text-align: center;
            margin-top: 24px;
        }
        
        .back-link a {
            color: #FF8C00;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
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
        
        .alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        @media (max-width: 600px) {
            .form-box {
                padding: 40px 30px;
            }
            
            h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <div class="logo-wrapper">
                <span class="logo-icon">UP</span>
                <span class="logo-text">STORE</span>
            </div>
        </div>
        
        <!-- Form Box -->
        <div class="form-box">
            <h1>Forgot Password</h1>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Enter your email address"
                           required>
                </div>
                
                <button type="submit" class="btn-submit">Forgot Password</button>
            </form>
            
            <div class="back-link">
                <a href="{{ route('login') }}">← Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>