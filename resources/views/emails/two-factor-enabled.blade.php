<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: #FF8C00; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .info-box { background: #e7f3ff; border-left: 4px solid #0066cc; padding: 15px; margin: 20px 0; }
        .button { display: inline-block; padding: 12px 30px; background: #FF8C00; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 2FA Enabled</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->username }}</strong>,</p>
            
            <div class="info-box">
                Two-Factor Authentication has been successfully enabled on your account!
            </div>
            
            <p>Your account is now protected with an additional layer of security. From now on, you'll need to enter a 6-digit code from your authenticator app when logging in.</p>
            
            <p><strong>What this means:</strong></p>
            <ul>
                <li>Your account is more secure against unauthorized access</li>
                <li>You'll need your phone to login</li>
                <li>You have recovery codes for backup access</li>
                <li>Even if someone knows your password, they can't login without your phone</li>
            </ul>
            
            <p><strong>Important reminders:</strong></p>
            <ul>
                <li>Keep your recovery codes in a safe place</li>
                <li>Don't uninstall your authenticator app</li>
                <li>If you get a new phone, transfer your 2FA first</li>
            </ul>
            
            <a href="{{ url('/2fa') }}" class="button">Manage 2FA Settings</a>
            
            <p style="margin-top: 30px; color: #666; font-size: 13px;">
                <strong>Didn't enable 2FA?</strong> If you didn't perform this action, your account may be compromised. 
                Contact support immediately.
            </p>
        </div>
        
        <div class="footer">
            <p>This is an automated security notification from UP STORE</p>
        </div>
    </div>
</body>
</html>