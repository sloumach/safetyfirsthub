<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate Notification</title>
</head>
<body>
    <p>Hello {{ $certificate->examUser->user->name }},</p>

    <p>
        @if(isset($daysRemaining))
            Your certificate for the course <strong>{{ $certificate->examUser->exam->course->name }}</strong>
            will expire in <strong>{{ $daysRemaining }} day(s)</strong>.
        @else
            Your certificate for the course <strong>{{ $certificate->examUser->exam->course->name }}</strong>
            has <strong>expired</strong>.
        @endif
    </p>

    <p>
        Certificate creation date:
        {{ $certificate->created_at ? $certificate->created_at->format('m/d/Y') : 'N/A' }}
    </p>


    <p>Thank you for your understanding.</p>
</body>
</html>
