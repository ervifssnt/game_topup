<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; }
        .header { background: #dc3545; color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px; }
        .alert-box { background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; }
        .details { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .details-item { margin: 10px 0; }
        .details-label { font-weight: bold; color: #666; }
        .button { display: inline-block; padding: 12px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Security Alert</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->username }}</strong>,</p>
            
            <div class="alert-box">
                <strong>{{ $alertType }}</strong>
            </div>
            
            <p>{{ $details['message'] ?? 'Suspicious activity detected on your account.' }}</p>
            
            <div class="details">
                <div class="details-item">
                    <span class="details-label">Time:</span> {{ now()->format('F d, Y H:i:s') }}
                </div>
                @if(isset($details['ip']))
                <div class="details-item">
                    <span class="details-label">IP Address:</span> {{ $details['ip'] }}
                </div>
                @endif
                @if(isset($details['location']))
                <div class="details-item">
                    <span class="details-label">Location:</span> {{ $details['location'] }}
                </div>
                @endif
            </div>
            
            <p><strong>What should you do?</strong></p>
            <ul>
                <li>If this was you, you can safely ignore this email</li>
                <li>If this wasn't you, change your password immediately</li>
                <li>Enable Two-Factor Authentication for added security</li>
                <li>Contact support if you need assistance</li>
            </ul>
            
            <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
        </div>
        
        <div class="footer">
            <p>This is an automated security notification from UP STORE</p>
            <p>If you did not perform this action, please contact support immediately</p>
        </div>
    </div>
</body>
</html>