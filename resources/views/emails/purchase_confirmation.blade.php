<!DOCTYPE html>
<html>
<head>
    <title>Course Purchase Confirmation</title>
</head>
<body>
    <h1>Thank you for your purchase, {{ $user->name }}!</h1>
    <p>You have successfully purchased the course: <strong>{{ $course->name }}</strong>.</p>
    <p>Start learning now by accessing your course dashboard.</p>
    <a href="{{ url('/dashboard') }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; display: inline-block;">Go to Dashboard</a>
    <p>If you have any questions, feel free to contact us.</p>
</body>
</html>
