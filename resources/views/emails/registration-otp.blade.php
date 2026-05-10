<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration Code</title>
    <style>
        body { font-family: sans-serif; background-color: #f9fafb; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        h1 { color: #9c6644; font-size: 24px; margin-top: 0; }
        p { color: #4b5563; line-height: 1.6; }
        .otp-box { background: #f3f4f6; padding: 20px; text-align: center; border-radius: 8px; margin: 25px 0; border: 2px dashed #d1d5db; }
        .otp-code { font-size: 32px; font-weight: bold; color: #111827; letter-spacing: 5px; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Bossku House!</h1>
        <p>Hi {{ $name }},</p>
        <p>Thank you for registering an account with us. To complete your registration and start earning loyalty points, please use the 6-digit verification code below:</p>
        
        <div class="otp-box">
            <span class="otp-code">{{ $otp }}</span>
        </div>
        
        <p>This code will expire in <strong>10 minutes</strong>. If you did not request this code, you can safely ignore this email.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bossku House. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
