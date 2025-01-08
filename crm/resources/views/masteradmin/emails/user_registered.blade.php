<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Trip Tracker!</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }

        h1 {
            color: #3f51b5;
        }

        p {
            margin: 10px 0;
            line-height: 1.5;
        }

        a {
            color: #3f51b5;
            text-decoration: none;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
    <img src="{{url('public/dist/img/logo.png')}}" alt="Trip Tracker Logo" class="brand-image">
        <h1>Welcome to Trip Tracker!</h1>
        <p>We're thrilled to have you join us on this journey of adventure!</p>

        <p>Here are your account details:</p>
        <ul>
            <li><strong>Your Unique ID:</strong> {{ $uniqueId }}</li>
            <li><strong>Email:</strong> {{ $userEmail }}</li>
        </ul>

        <p>To get started, <a href="{{ $loginUrl }}">click here to log in</a>.</p>

        <p>We can't wait to see where your travels take you!</p>
    </div>
</body>
</html>