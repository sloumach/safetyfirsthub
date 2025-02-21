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
		
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="assets/img/favicon.png">
		<!-- Title -->
		<title>Recover Password - Safety FirstHUB</title>
    </head>
    <body>
		<!-- Start Preloader Area -->
		
		<!-- End Preloader Area -->
 
		<!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-10">
			<div class="container">
				<div class="page-title-content">
					<h2>Recover Password</h2>
					<ul>
						<li><a href="{{ route('home') }}">Home</a></li>
						<li class="active">Recover Password</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Recover Password Area -->
		<section class="user-area-style recover-password-area ptb-100">
			<div class="container" style="">
				<div class="contact-form-action recover">
					<div class="section-title">
						<h2>Forgot Password</h2>
						<p>Enter your email and we'll send you instructions to reset your password</p>
					</div>

					@if (session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
					@endif

					@if ($errors->any())
						<div class="alert alert-danger">
							<ul class="mb-0">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<form method="POST" action="{{ route('password.email') }}">
						@csrf
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<input class="form-control" type="email" name="email" placeholder="Enter Email Address" value="{{ old('email') }}" required>
								</div>
							</div>
							
							<div class="col-lg-6 col-md-6 col-sm-6">
								<a class="now-log-in font-q" href="{{ route('login') }}">Log In to your account</a>
							</div>
							
							<div class="col-lg-6 col-md-6 col-sm-6">
								<p class="now-register">
									Not a member?
									<a class="font-q" href="{{ route('register') }}">Registration</a>
								</p>
							</div>

							<div class="col-12">
								<button class="default-btn btn-two" type="submit">
									Reset Password
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</section>
		<!-- End Recover Password Area -->

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
    </body>
</html>