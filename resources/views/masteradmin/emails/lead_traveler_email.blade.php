<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['subject'] }}</title>
</head>
<body>
    <strong>Hello {{ $data['travelerName'] }},</strong>

    <p>Here are the details of your travelers:</p>

    <p>
        <strong>Library Name:</strong> {{ $data['subject'] }}<br>
        <strong>Category:</strong> {{ $data['category'] }}<br>
        <strong>Basic Information:</strong>{{ strip_tags($data['basicinformation']) }}<br>
        <strong>Attachment</strong><br>
        
        <h3>Attachments:</h3>

        
    </p>

    <p>Best regards</p>
</body>
</html>
