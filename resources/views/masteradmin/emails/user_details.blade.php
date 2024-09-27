<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Profityo</title>
</head>
<body>
    <p>Dear User,</p>
    <p>Thank you for registering with us. Your unique ID is: <strong>{{ $uniqueId }}</strong></p>
    <p>You can log in using the following link: <a href="{{ $loginUrl }}">Change Password Here</a></p>
    <p>Your email address: <strong>{{ $userEmail }}</strong></p>
    <p>Best regards,<br>The Team</p>
</body>
</html>