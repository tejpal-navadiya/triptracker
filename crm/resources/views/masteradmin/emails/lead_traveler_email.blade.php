<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['subject'] }}</title>
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
            background-color: #3f51b5;
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
            font-size: 26px;
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
            background-color: #f1f1f1;
            padding: 15px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }

        .email-footer a {
            color: #3f51b5;
            text-decoration: none;
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
        <div class="email-header">
        <img src="{{url('public/dist/img/logo.png')}}" alt="Trip Tracker Logo" class="brand-image">
           <h1>Library Details for {{ $data['subject'] }}</h1>

        </div>   
        <div class="email-body">
        <strong>Hello {{ $data['travelerName'] }},</strong>

        <p>Here are the details of your traveler:</p>

        <p>
            <strong>Library Name:</strong> {{ $data['subject'] }}<br>
            <strong>Category:</strong> {{ $data['category'] }}<br>
            <strong>Basic Information:</strong>{!! html_entity_decode($data['basicinformation'] ?? '<p>No content available</p>', ENT_QUOTES, 'UTF-8') !!}<br>
        </p>

        <!-- Optional: Attachments Section (if required) -->
        @if(!empty($data['attachments']))
            <p><strong>Attachments:</strong></p>
            <ul>
                @foreach($data['attachments'] as $attachment)
                    <li>{{ $attachment }}</li>
                @endforeach
            </ul>
        @endif
        </div>
        <div class="email-footer">
            <!-- <p>Best regards,<br>The Trip Tracker Team</p> -->
            <p>&copy; {{ date('Y') }} Trip Tracker. All rights reserved.</p>
            <p>If you have any questions, <a href="mailto:support@yourdomain.com">contact support</a>.</p>
        </div>
    </div>
</body>
</html>
