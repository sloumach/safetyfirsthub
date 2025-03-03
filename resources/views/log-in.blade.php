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
		<!-- Login CSS -->
		<link rel="stylesheet" href="assets/css/login.css">
    </head>
    <body>

        <!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->
		<!-- Start Preloader Area -->
	
		<!-- End Preloader Area -->


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
						<h2 style="color:rgb(0, 0, 0) !important;">Login to your account</h2>
					</div>

					<form method="POST" action="{{ route('login') }}">
						@csrf
						<div class="login-form-group">
							<label>Email or Phone</label>
							<input class="@error('email') is-invalid @enderror" 
								   type="text" 
								   name="email" 
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
    </body>
</html>
