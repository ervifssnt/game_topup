<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request - UP STORE</title>
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
            max-width: 500px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 40px;
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
            padding: 40px;
        }
        
        h1 {
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 12px;
        }
        
        .subtitle {
            text-align: center;
            color: #999;
            margin-bottom: 32px;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #ccc;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: #1a1a1a;
            border: 1px solid #3a3a3a;
            border-radius: 8px;
            color: white;
            font-size: 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #FF8C00;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
            font-family: 'Inter', sans-serif;
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
        }
        
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .back-link a {
            color: #FF8C00;
            text-decoration: none;
            font-size: 14px;
        }
        
        .back-link a:hover {
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .alert-error {
            background: #4a1a1a;
            border: 1px solid #d32f2f;
            color: #ef5350;
        }
        
        .alert-success {
            background: #1e4620;
            border: 1px solid #2e7d32;
            color: #66bb6a;
        }
        
        .help-text {
            font-size: 13px;
            color: #666;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <div class="logo-wrapper">
                <span class="logo-icon">UP</span>
                <span class="logo-text">STORE</span>
            </div>
        </div>
        
        <div class="form-box">
            <h1>🔑 Password Reset Request</h1>
            <p class="subtitle">
                Submit a request to reset your password. An admin will review and approve your request.
            </p>
            
            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            
            <form method="POST" action="{{ route('password.request.submit') }}">
                @csrf
                
                <div class="form-group">
                    <label>Email Address *</label>
                    <input type="email" 
                           name="email" 
                           class="form-control"
                           value="{{ old('email') }}"
                           placeholder="Enter your registered email"
                           required>
                    <div class="help-text">Enter the email address associated with your account</div>
                </div>
                
                <div class="form-group">
                    <label>Reason for Reset *</label>
                    <textarea name="reason" 
                              class="form-control" 
                              placeholder="Please explain why you need a password reset (e.g., forgot password, account compromised, etc.)"
                              required>{{ old('reason') }}</textarea>
                    <div class="help-text">This helps admins process your request faster</div>
                </div>
                
                <button type="submit" class="btn-submit">Submit Request</button>
            </form>
            
            <div class="back-link">
                <a href="{{ route('login') }}">← Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>