<!DOCTYPE html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
		<!-- Start Preloader Area -->
	
		<!-- End Preloader Area -->


		<!-- Start Page Title Area -->
		<div class="page-title-area bg-10">
			<div class="container">
				<div class="page-title-content">
					<h2>Registration</h2>
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

					<form method="POST" action="{{ route('register') }}">
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
							<input type="email" name="email" required>
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


        <!-- Jquery Min JS -->
        <script src="assets/js/jquery.min.js"></script>
        <!-- Bootstrap Bundle Min JS -->
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <!-- Meanmenu Min JS -->
		<script src="assets/js/meanmenu.min.js"></script>
		<!-- Owl Carousel Min JS -->
		<script src="assets/js/owl.carousel.min.js"></script>
		<!-- Wow Min JS -->
        <script src="assets/js/wow.min.js"></script>
        <!-- Nice Select Min JS -->
		<script src="assets/js/nice-select.min.js"></script>
        <!-- Magnific Popup Min JS -->
		<script src="assets/js/magnific-popup.min.js"></script>
		<!-- jarallax Min JS -->
		<script src="assets/js/jarallax.min.js"></script>
		<!-- Appear Min JS -->
        <script src="assets/js/appear.min.js"></script>
		<!-- Odometer JS -->
		<script src="assets/js/odometer.min.js"></script>
		<!-- Form Validator Min JS -->
		<script src="assets/js/form-validator.min.js"></script>
		<!-- Contact JS -->
		<script src="assets/js/contact-form-script.js"></script>
		<!-- Ajaxchimp Min JS -->
		<script src="assets/js/ajaxchimp.min.js"></script>
        <!-- Custom JS -->
		<script src="assets/js/custom.js"></script>
        <!-- Register JS -->
        <script src="assets/js/register.js"></script>
		<script src="assets/js/navbar.js"></script>
    </body>
</html>
