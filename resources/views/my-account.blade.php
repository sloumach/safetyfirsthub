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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
		<title>Safety FirstHUB</title>
    </head>
    <body>

		<!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-7">
			<div class="container">
				<div class="page-title-content">
					<h1>My account</h1>
					<ul>
						<li>
							<a href="{{ route('home') }}">
								Home 
							</a>
						</li>
						
						<li class="active">My account</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Log In Area -->
		<section class="user-area-style ptb-100">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
						<div class="section-title">
							<h2>Log In</h2>
						</div>

						<div class="contact-form-action mb-50">
							<form method="post">
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label>Email</label>
											<input class="form-control" type="text" name="name">
										</div>
									</div>
		
									<div class="col-12">
										<div class="form-group">
											<label>Password</label>
											<input class="form-control" type="password" name="password">
										</div>
									</div>
		
									<div class="col-12">
										<div class="login-action">
											<span class="log-rem">
												<input id="remember" type="checkbox">
												<label for="remember">Remember me!</label>
											</span>
											<span class="forgot-login">
												<a href="recover-password.html">Forgot your password?</a>
											</span>
										</div>
									</div>
		
									<div class="col-12">
										<button class="default-btn" type="submit">
											Log In Now
										</button>
									</div>

									<div class="col-12">
										<p>Have an account? <a href="registration.html">Registration Now!</a></p>
									</div>
								</div>
							</form>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="section-title">
							<h2>Registration</h2>
						</div>

						<div class="contact-form-action">
							<form method="post">
								<div class="row">
									<div class="col-12">
										<div class="form-group">
											<label>Full name</label>
											<input class="form-control" type="text" name="name">
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Email address</label>
											<input class="form-control" type="email" name="email">
										</div>
									</div>

									<div class="col-12">
										<div class="form-group">
											<label>Mobile no.</label>
											<input class="form-control" type="email" name="email">
										</div>
									</div>
		
									<div class="col-12">
										<div class="form-group">
											<label>Password</label>
											<input class="form-control" type="text" name="password">
										</div>
									</div>
		
									<div class="col-12">
										<div class="row align-items-center">
											<div class="col-lg-6 col-sm-6">
												<button class="default-btn register" type="submit">
													Register Now
												</button>
											</div>
		
											<div class="col-lg-6 col-sm-6 text-right">
												<input id="remember-1" type="checkbox">
												<label for="remember">Show password ?</label>
											</div>
										</div>
									</div>

									<div class="col-12">
										<p>Have an account? <a href="log-in.html">Login Now!</a></p>
									</div>
								</div>
							</form>
						</div>
					</div>
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
</html>