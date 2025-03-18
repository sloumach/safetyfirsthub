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
		<link rel="stylesheet" href="assets/css/register.css">
		
    </head>
    <body>

        <!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-10">
			<div class="container">
				<div class="page-title-content">
					<h1>Registration</h1>
					<ul>
						<li>
							<a href="{{ route('home') }}">
								Home
							</a>
						</li>

						<li class="active">Registration</li>
					</ul>
				</div>
			</div>
		</div>
		<section class="user-area-style ptb-100">
			<div class="register-shapes">
				<div class="register-shape register-shape-1"></div>
				<div class="register-shape register-shape-2"></div>
				<div class="register-shape register-shape-3"></div>
			</div>
			<div class="container">
				<div class="registration-container">
					<div class="registration-title">
						<h2 style="color:rgb(0, 0, 0) !important;">Create Account</h2>
					</div>

					<div id="notification-container"></div>

					<form method="POST" action="{{ route('register') }}" id="registrationForm">
						@csrf
						<div class="register-form-group">
							<label>First Name</label>
							<input type="text" name="firstname" required>
						</div>

						<div class="register-form-group">
							<label>Last Name</label>
							<input type="text" name="lastname" required>
						</div>

						<div class="register-form-group">
							<label>Email Address</label>
							<input type="email" name="email" id="email" required>
							<span id="emailError" class="error-message"></span>
						</div>

						<div class="register-form-group">
							<label>Password</label>
							<input type="password" name="password" required>
						</div>

						<div class="register-form-group">
							<label>Confirm Password</label>
							<input type="password" name="password_confirmation" required>
						</div>

						<div class="password-toggle">
							<input id="show-password" type="checkbox">
							<label for="show-password">Show password</label>
						</div>

						<button class="register-button" type="submit">
							Create Account
						</button>

						<div class="login-link">
							<p>Already have an account? <a href="{{ route('login') }}" style="color: #FF8A00 !important;">Log In</a></p>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!-- End Log In Area -->

		<!-- Start Footer Top Area -->
		@include('footer')
		<!-- End Footer Bottom Area -->

		<!-- Start Go Top Area -->
		<div class="go-top">
			<i class='bx bx-chevrons-up'></i>
			<i class='bx bx-chevrons-up'></i>
		</div>
		<!-- End Go Top Area -->

    </body>
    @include('layouts.scripts')
    <!-- Page Specific Scripts -->
    <script>
        function isValidEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        document.getElementById('registrationForm').addEventListener('submit', function(e) {
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
    <script src="assets/js/register.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
