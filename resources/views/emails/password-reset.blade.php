<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>
        body { font-family: sans-serif; background-color: #f9fafb; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h1 { color: #9c6644; font-size: 24px; margin-top: 0; }
        p { color: #4b5563; line-height: 1.6; }
        .btn-box { text-align: center; margin: 25px 0; }
        .btn { display: inline-block; background-color: #9c6644; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .btn:hover { background-color: #7f5539; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>
        <p>Hi {{ $name }},</p>
        <p>You are receiving this email because we received a password reset request for your account. Please click the button below to reset your password:</p>
        
        <div class="btn-box">
            <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
        </div>
        
        <p>This password reset link will expire in <strong>60 minutes</strong>. If you did not request a password reset, no further action is required.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bossku House. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
