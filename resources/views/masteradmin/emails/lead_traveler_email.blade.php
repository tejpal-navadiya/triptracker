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

@if (isset($data['attachment']) && is_array($data['attachment']))
    @foreach ($data['attachment'] as $file)
        @php
            $fileName = basename($file);
        @endphp

        @if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $fileName))
            <!-- Display Image Inline -->
            <img src="{{ $message->embed($file) }}" alt="Image" style="max-width: 200px;">
        @elseif (preg_match('/\.pdf$/i', $fileName))
            <!-- Embed PDF -->
            <div style="border: 1px solid #ddd; padding: 10px;">
                <embed src="{{ $message->embed($file) }}" type="application/pdf" width="100%" height="500px" />
            </div>
        @endif

        <br>
        <!-- Provide a download link for the file -->
        <br><br>
    @endforeach
@else
    <p>No attachments found.</p>
@endif
        
    </p>

    <p>Best regards</p>
</body>
</html>
