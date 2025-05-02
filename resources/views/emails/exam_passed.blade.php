<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Passed Notification</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>

    <p>
        Congratulations! You have successfully passed the exam for the course
        <strong>{{ $course->name }}</strong>.
    </p>

    <p>
        Your certificate is now available in your dashboard. Click the button below to view it:
    </p>

    <p style="text-align: center; margin: 20px 0;">
        <a href="https://www.safetyfirsthub.com/dashboard/certificate"
           style="background-color: #FF8A00; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
            View My Certificate
        </a>
    </p>

    <p>Keep up the great work,</p>
    <p><strong>Your Training Team</strong></p>
</body>
</html>
