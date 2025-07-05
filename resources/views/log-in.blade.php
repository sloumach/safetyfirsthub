<!DOCTYPE html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Safety FirstHUB">
		<meta name="keywords" content="Safety FirstHUB">
		<!-- Bootstrap Min CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">
		<!-- Owl Theme Default Min CSS -->
		<link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
		<!-- Owl Carousel Min CSS -->
		<link rel="stylesheet" href="assets/css/owl.carousel.min.css">
		<!-- Owl Magnific Popup Min CSS -->
		<link rel="stylesheet" href="assets/css/magnific-popup.min.css">
		<!-- Animate Min CSS -->
		<link rel="stylesheet" href="assets/css/animate.min.css">
		<!-- Boxicons Min CSS -->
		<link rel="stylesheet" href="assets/css/boxicons.min.css">
		<!-- Flaticon CSS -->
		<link rel="stylesheet" href="assets/css/flaticon.css">
		<!-- Meanmenu Min CSS -->
		<link rel="stylesheet" href="assets/css/meanmenu.min.css">
		<!-- Nice Select Min CSS -->
		<link rel="stylesheet" href="assets/css/nice-select.min.css">
		<!-- Odometer Min CSS-->
		<link rel="stylesheet" href="assets/css/odometer.min.css">
		<!-- Style CSS -->
		<link rel="stylesheet" href="assets/css/style.css">
		<!-- Dark CSS -->
		<link rel="stylesheet" href="assets/css/dark.css">
		<!-- Responsive CSS -->
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="stylesheet" href="assets/css/navbar.css">
		<link rel="stylesheet" href="assets/css/footer.css">
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="assets/img/favicon.png">
		<!-- Title -->
		<title>Safety FirstHUB</title>
		<!-- Login CSS -->
		<link rel="stylesheet" href="assets/css/login.css">
    </head>
    <body>

        <!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->


		<!-- Start Page Title Area -->
		<div class="page-title-area bg-8">
			<div class="container">
				<div class="page-title-content">
					<h2>Log in</h2>
					<ul>
						<li>
							<a href="/">
								Home
							</a>
						</li>

						<li class="active">Log in</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Log In Area -->
		<section class="user-area-style ptb-100">
			<div class="background-shapes">
				<div class="shape shape-1"></div>
				<div class="shape shape-2"></div>
				<div class="shape shape-3"></div>
			</div>
			<div class="container">
				<div class="login-container">
					<div class="login-title">
						<h1 style="color:rgb(0, 0, 0) !important;">Login to your account</h1>
					</div>

					<form method="POST" action="{{ route('login') }}" id="loginForm">
						@csrf
						<div class="login-form-group">
							<label>Email</label>
							<input class="@error('email') is-invalid @enderror"
								   type="text"
								   name="email"
								   id="email"
								   value="{{ old('email') }}">
						</div>

						<div class="login-form-group">
							<label>Password</label>
							<input class="@error('password') is-invalid @enderror"
								   type="password"
								   name="password">
						</div>

						<div class="login-actions">
							<span class="forgot-password">
								<a href="{{ route('password.request') }}">Forgot your password?</a>
							</span>
						</div>

						<button class="login-button" type="submit">
							Log In Now
						</button>

						<div class="login-register">
							<p>Don't have an account? <a href="{{ route('register') }}" style="color: #FF8A00 !important;">Register Now!</a></p>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!-- End Log In Area -->

		<!-- Start Footer Top Area -->
		@include('footer')
		<!-- Start Go Top Area -->
		<div class="go-top">
			<i class='bx bx-chevrons-up'></i>
			<i class='bx bx-chevrons-up'></i>
		</div>
		<!-- End Go Top Area -->

    </body>
	<script>
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;

            if (!isValidEmail(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address',
                    confirmButtonColor: '#FF8A00'
                });
                return;
            }

            // If email is valid, submit the form
            this.submit();
        });
    </script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('layouts.scripts')
</html>
