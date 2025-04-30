<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Expiration Notice</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>

    <p>
        We hope you're doing well. This is a friendly reminder that your access to the course
        <strong>{{ $course->name }}</strong> will expire on
        <strong>{{ \Carbon\Carbon::now()->addDays($daysRemaining)->format('F j, Y') }}</strong>.
    </p>

    @if($hasNotPassedExam)
        <p>
            As of today, we’ve noticed that you haven’t passed the final exam required to receive your certification.
            To obtain your certificate, please make sure to complete and pass the exam before your access ends.
        </p>
    @endif

    <p>
        If you need support or have any questions, feel free to reach out.
    </p>

    <p>Best regards,</p>
    <p><strong>Your Training Team</strong></p>
</body>
</html>
