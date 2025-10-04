<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: #28a745; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .success-box { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; color: #155724; }
        .button { display: inline-block; padding: 12px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Password Reset Approved</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->username }}</strong>,</p>
            
            <div class="success-box">
                Your password reset request has been approved by an administrator.
            </div>
            
            <p>A new password has been set for your account. You should have received the new password from the administrator.</p>
            
            <p><strong>For your security:</strong></p>
            <ul>
                <li>Change this password immediately after logging in</li>
                <li>Use a strong, unique password</li>
                <li>Enable Two-Factor Authentication</li>
                <li>Never share your password with anyone</li>
            </ul>
            
            <a href="{{ url('/login') }}" class="button">Login Now</a>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from UP STORE</p>
            <p>If you did not request this password reset, contact support immediately</p>
        </div>
    </div>
</body>
</html>