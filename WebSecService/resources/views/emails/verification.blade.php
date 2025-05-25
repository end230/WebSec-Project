<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #0d6efd;
            margin-top: 0;
        }
        .button {
            display: inline-block;
            background-color: #0d6efd;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Verify Your Email Address</h2>
    </div>
    
    <p>Hello {{ $name }},</p>
    
    <p>Thank you for registering! Please click the button below to verify your email address:</p>
    
    <div style="text-align: center;">
        <a href="{{ $link }}" class="button" target="_blank">Verify Email Address</a>
    </div>
    
    <p>If you did not create an account, no further action is required.</p>
    
    <p>Best regards,<br>WebSec Service Team</p>
    
    <div class="footer">
        <p>If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:</p>
        <p style="word-break: break-all;">{{ $link }}</p>
    </div>
</body>
</html> 