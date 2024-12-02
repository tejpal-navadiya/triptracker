<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Trip Tracker</title>
</head>

<body>
    <h1>Welcome!</h1>
    <p>Thank you for registering with us. Below are your details:</p>
    <p> Your unique ID is: <strong>{{ $uniqueId }}</strong></p>
    <p><strong>Email:</strong> {{ $userEmail }}</p>
    <p><strong>Login Link:</strong> <a href="{{ $loginUrl }}">Click here to login</a></p>

    <!-- <p>Your invoice is ready. You can download it using the link below:</p>
    <p><a href="{{ $invoiceUrl }}" target="_blank">Download Invoice</a></p> -->

    <p>Thank you for choosing our service!</p>
</body>
</html>