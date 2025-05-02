<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Exam Failed Notification</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>

    <p>
        Unfortunately, you did not pass the exam for the course <strong>{{ $course->name }}</strong>.
        You have <strong>{{ $attemptsLeft }}</strong> attempt(s) remaining out of 3.
    </p>

    <p>We encourage you to review the course materials and try again.</p>

    <p>Best of luck,</p>
    <p><strong>Your Training Team</strong></p>
</body>
</html>
