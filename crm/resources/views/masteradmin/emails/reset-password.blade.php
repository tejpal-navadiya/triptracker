<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .email-header {
            background-color: #384150;
            padding: 30px;
            color: white;
        }
        .email-header h1 {
            font-size: 24px;
            margin: 0;
        }
        .email-body {
            padding: 20px;
            text-align: left;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
        .email-button {
            display: inline-block;
            background-color: #3f51b5;
            color: #fff;
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }
        .email-button:hover {
            background-color: #303f9f;
        }
        .email-footer {
            background-color: #384150;
            padding: 15px;
            font-size: 14px;
            color: #fff;
            text-align: center;
        }
        .email-footer a {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Password Reset Request</h1>
        </div>
        <div class="email-body">
            <p>Hello,</p>
            <p>You are receiving this email because we received a password reset request for your account.</p>
            <p style="text-align: center;">
                <a href="{{ $url }}" class="email-button">Reset Password</a>
            </p>
            <p>This password reset link will expire in 60 minutes.</p>
            <p>If you did not request a password reset, no further action is required.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Trip Tracker. All rights reserved.</p>
            <p>If you have any questions, <a href="mailto:support@yourdomain.com">contact support</a>.</p>
        </div>
    </div>
</body>
</html>
