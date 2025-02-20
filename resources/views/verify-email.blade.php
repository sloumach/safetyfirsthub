<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verify Email - Safety FirstHUB</title>

    <!-- Bootstrap Min CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <!-- Boxicons Min CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/boxicons.min.css') }}">
    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/verifymail.css') }}">

</head>
<body>
    <div class="verify-email-container">
        <div class="verify-card">
            <i class='bx bx-envelope mb-4' style="font-size: 50px; color: var(--main-color);"></i>
            <h2 class="mb-4">Verify Your Email</h2>
            
            <div class="buttons-container">
                <form method="POST" action="{{ route('verification.send') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-resend">
                        <i class='bx bx-mail-send'></i> Resend Verification Email
                    </button>
                </form>

                <a href="{{ route('logout') }}" class="btn-logout">
                    <i class='bx bx-log-out'></i> Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
