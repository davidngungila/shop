<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - {{ $companyName }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #009245;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #009245;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .password-box {
            background-color: #f9fafb;
            border: 2px solid #009245;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .password {
            font-size: 24px;
            font-weight: bold;
            color: #009245;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 4px;
            display: inline-block;
            min-width: 200px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #009245;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #007a38;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="company-name">{{ $companyName }}</div>
            <p style="color: #666; margin: 0;">Password Reset Request</p>
        </div>

        <div class="content">
            <p>Hello {{ $user->name }},</p>
            
            <p>You have requested to reset your password for your {{ $companyName }} account.</p>
            
            <p>Your new password has been generated. Please use the following password to log in:</p>
            
            <div class="password-box">
                <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">Your New Password:</p>
                <div class="password">{{ $newPassword }}</div>
            </div>
            
            <div class="warning">
                <strong>⚠️ Security Notice:</strong><br>
                For your security, please change this password immediately after logging in. This password was automatically generated and should be kept confidential.
            </div>
            
            <p>To log in:</p>
            <ol>
                <li>Go to the login page</li>
                <li>Enter your email: <strong>{{ $user->email }}</strong></li>
                <li>Enter the new password shown above</li>
                <li>After logging in, go to your profile settings to change your password</li>
            </ol>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Go to Login Page</a>
            </div>
            
            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                If you did not request this password reset, please contact our support team immediately.
            </p>
        </div>

        <div class="footer">
            <p>This is an automated email from {{ $companyName }}.</p>
            <p>Please do not reply to this email.</p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} {{ $companyName }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

