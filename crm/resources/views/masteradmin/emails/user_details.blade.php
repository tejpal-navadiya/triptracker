<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Trip Tracker!</title>
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
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            color: white;
        }

        .email-header img {
            max-width: 130px;
            margin-bottom: 20px;
        }

        .email-header h1 {
            font-size: 20px;
            margin: 0;
        }

        .email-body {
            padding: 20px;
            text-align: left;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
            background: #F2F8FF;
        }

        .email-button {
            display: inline-block;
            background-color: #3f51b5; /* Dark blue button */
            color: #fff; /* White text color */
            padding: 15px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none; /* Remove underline */
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

        /* Media Queries for Responsive Design */
        @media (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 10px auto;
            }

            .email-header h1 {
                font-size: 22px;
            }

            .email-body {
                font-size: 15px;
            }

            .email-button {
                padding: 12px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Logo Section -->
        <div class="email-header">
        <img src="{{url('public/dist/img/logo.png')}}" alt="Trip Tracker Logo" class="brand-image">
            <h1>Password Reset</h1>
        </div>       
        <div class="email-body">
        <!-- Body Content -->
        <p>Dear User,</p>
        <p>Thank you for registering with Trip Tracker. Your account has been successfully created.</p>
        <p>Here are your account details:</p>
        <ul>
            <li><strong>Unique ID:</strong> {{ $uniqueId }}</li>
            <li><strong>Email Address:</strong> {{ $userEmail }}</li>
        </ul>

        <p>To set your password and access your account, please click the button below:</p>
        <p><a href="{{ $loginUrl }}" class="button">Set Your Password</a></p>

        <!-- <p>This link will expire in <strong>[Time Limit, e.g., 24 hours]</strong>. If you do not set your password within this time, you will need to request a new password reset link.</p> -->

        <p>If you did not create this account, please disregard this email.</p>
        </div>
        <!-- Footer Section -->
        <div class="email-footer">
            <!-- <p>Best regards,<br>The Trip Tracker Team</p> -->
            <p>&copy; {{ date('Y') }} Trip Tracker. All rights reserved.</p>
            <p>If you have any questions, <a href="mailto:support@tatriptracker.com">contact support</a>.</p>
        </div>
    </div>
</body>
</html>
