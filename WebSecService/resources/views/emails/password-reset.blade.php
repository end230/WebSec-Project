<!DOCTYPE html>
<html>
<head>
    <title>Reset Your Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; border-radius: 5px; padding: 20px; margin-bottom: 20px;">
        <h2 style="color: #0d6efd; margin-top: 0;">Reset Your Password</h2>
    </div>
    
    <p>Hello {{ $user->name }},</p>
    
    <p>You are receiving this email because we received a password reset request for your account.</p>
    
    <div style="margin: 30px 0;">
        <a href="{{ $resetUrl }}" style="background-color: #0d6efd; color: white; padding: 12px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Reset Password</a>
    </div>
    
    <p>This password reset link will expire in 60 minutes.</p>
    
    <p>If you did not request a password reset, no further action is required.</p>
    
    <p>Regards,<br>WebSec Service Team</p>
    
    <hr style="border: none; border-top: 1px solid #e5e5e5; margin: 30px 0;">
    
    <p style="font-size: 12px; color: #6c757d;">If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
    <p style="font-size: 12px; color: #6c757d; word-break: break-all;">{{ $resetUrl }}</p>
</body>
</html> 