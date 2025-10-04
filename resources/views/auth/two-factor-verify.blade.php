<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification - UP STORE</title>
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
            max-width: 450px;
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
        
        .verify-box {
            background: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 12px;
            padding: 40px;
        }
        
        .verify-icon {
            text-align: center;
            font-size: 64px;
            margin-bottom: 24px;
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
            font-size: 15px;
        }
        
        .form-group {
            margin-bottom: 24px;
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
            padding: 16px;
            background: #1a1a1a;
            border: 1px solid #3a3a3a;
            border-radius: 8px;
            color: white;
            font-size: 24px;
            text-align: center;
            letter-spacing: 12px;
            font-weight: 700;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #FF8C00;
        }
        
        .form-control::placeholder {
            letter-spacing: 8px;
            color: #444;
        }
        
        .btn-submit {
            width: 100%;
            padding: 16px;
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
        
        .divider {
            text-align: center;
            margin: 24px 0;
            color: #666;
            font-size: 13px;
            position: relative;
        }
        
        .divider:before,
        .divider:after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #3a3a3a;
        }
        
        .divider:before {
            left: 0;
        }
        
        .divider:after {
            right: 0;
        }
        
        .recovery-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .recovery-link button {
            background: none;
            border: none;
            color: #FF8C00;
            cursor: pointer;
            font-size: 14px;
            text-decoration: underline;
        }
        
        .recovery-link button:hover {
            color: #ff9d1f;
        }
        
        .alert-error {
            background: #4a1a1a;
            border: 1px solid #d32f2f;
            color: #ef5350;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .help-text {
            text-align: center;
            color: #666;
            font-size: 13px;
            margin-top: 24px;
            line-height: 1.6;
        }
        
        .recovery-input {
            display: none;
        }
        
        .recovery-input.show {
            display: block;
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
        
        <!-- Verification Box -->
        <div class="verify-box">
            <div class="verify-icon">🔒</div>
            <h1>Two-Factor Authentication</h1>
            <p class="subtitle">Enter the 6-digit code from your authenticator app</p>
            
            @if($errors->any())
                <div class="alert-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('2fa.login') }}">
                @csrf
                
                <div class="form-group">
                    <label id="codeLabel">Authentication Code</label>
                    <input type="text" 
                           name="code" 
                           id="codeInput"
                           class="form-control" 
                           placeholder="000000" 
                           maxlength="8" 
                           inputmode="numeric"
                           required 
                           autofocus>
                    <div class="help-text" id="helpText">
                        Open your authenticator app and enter the current code
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">Verify</button>
            </form>
            
            <div class="divider">OR</div>
            
            <div class="recovery-link">
                <button type="button" onclick="toggleRecoveryMode()">Use a recovery code instead</button>
            </div>
            
            <div class="help-text" style="margin-top: 32px; padding-top: 20px; border-top: 1px solid #3a3a3a;">
                <strong>Lost your phone?</strong><br>
                Use one of your 8-character recovery codes to log in
            </div>
        </div>
    </div>
    
    <script>
    let isRecoveryMode = false;
    
    function toggleRecoveryMode() {
        isRecoveryMode = !isRecoveryMode;
        const input = document.getElementById('codeInput');
        const label = document.getElementById('codeLabel');
        const helpText = document.getElementById('helpText');
        
        if (isRecoveryMode) {
            input.placeholder = 'XXXXXXXX';
            input.maxLength = 8;
            input.style.letterSpacing = '6px';
            label.textContent = 'Recovery Code';
            helpText.textContent = 'Enter one of your 8-character recovery codes';
            document.querySelector('.recovery-link button').textContent = 'Use authenticator app instead';
        } else {
            input.placeholder = '000000';
            input.maxLength = 6;
            input.style.letterSpacing = '12px';
            label.textContent = 'Authentication Code';
            helpText.textContent = 'Open your authenticator app and enter the current code';
            document.querySelector('.recovery-link button').textContent = 'Use a recovery code instead';
        }
        
        input.value = '';
        input.focus();
    }
    
    // Auto-format input
    document.getElementById('codeInput').addEventListener('input', function(e) {
        if (!isRecoveryMode) {
            this.value = this.value.replace(/[^0-9]/g, '');
        } else {
            this.value = this.value.replace(/[^0-9A-Za-z]/g, '').toUpperCase();
        }
    });
    </script>
</body>
</html>