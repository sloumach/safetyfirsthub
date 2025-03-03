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
		<link rel="stylesheet" href="assets/css/courses.css">
		<link rel="icon" type="image/png" href="assets/img/favicon.png">
		<!-- Title -->
		<title>Safety FirstHUB</title>
    </head>
    <body>

        <!-- Start Navbar Area -->
        @include('navbar')
        <!-- End Navbar Area -->

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-4">
			<div class="container">
				<div class="page-title-content">
					<h2 style="color:rgb(0, 0, 0) !important;">Certified Courses</h2>
					<ul>
						<li>
							<a href="{{ route('home') }}">
								Home
							</a>
						</li>

						<li class="active" style="color:rgb(0, 0, 0) !important;">Certified Courses</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Popular Courses Area -->
		<section class="courses-area-style ptb-100">
			<div class="container">
				<!-- Add welcome section -->
				<div class="courses-welcome">
					<div class="welcome-content">
						<h1>Start Your Learning Journey</h1>
						<p>Browse through our courses and take the first step towards your goals</p>
						
						<div class="welcome-steps">
							<div class="step">
								<span class="step-number">1</span>
								<p>Choose a Certified Course</p>
							</div>
							<div class="step-arrow"><i class="fas fa-arrow-right"></i></div>
							<div class="step">
								<span class="step-number">2</span>
								<p>Enroll Now</p>
							</div>
							<div class="step-arrow"><i class="fas fa-arrow-right"></i></div>
							<div class="step">
								<span class="step-number">3</span>
								<p>Start Learning</p>
							</div>
						</div>
					</div>
				</div>

				<div class="showing-result">
					<div class="row align-items-center">
						<!-- <div class="col-lg-6 col-md-4">
							 <div class="showing-result-count">
								<p>Showing 1-8 of 14 results</p>
							</div> 
						</div>

						<div class="col-lg-3 col-md-4">
							<div class="showing-top-bar-ordering">
								<select>
									<option value="1">Default sorting</option>
									<option value="2">Education</option>
									<option value="0">Accounting</option>
									<option value="3">Language</option>
									<option value="4">Teaching</option>
									<option value="5">Research</option>
									<option value="5">Assessment</option>
								</select>
							</div>
						</div> -->

						{{-- <div class="col-lg-3 col-md-4">
							<form class="search-form">
								<input class="form-control" name="search" placeholder="Search our courses" type="text">
								<button class="search-btn" type="submit">
									<i class="bx bx-search"></i>
								</button>
							</form>
						</div> --}}
					</div>
				</div>

				<div class="courses-container">
					<div class="row courses-grid" id="coursesGrid">
						@foreach ($courses as $course)
							<div class="course-item col-lg-4 col-md-6 col-sm-12">
								<div class="single-course">
									<a href="{{ route('singlecourse', ['id' => $course->id]) }}" class="course-img-wrapper">
										<img src="{{ asset('storage/' . $course->cover) }}" alt="Cover for {{ $course->category }}">
									</a>

									<div class="course-content">
										<span class="price">${{ $course->price }}</span>
										<span class="tag">{{ $course->category }}</span>

										<a href="{{ route('singlecourse', ['id' => $course->id]) }}" style="padding-top: 2%;">
											<h3>{{ $course->name }}</h3>
										</a>

										<p>{{ $course->short_description }}</p>

										<ul class="lessons">
											<li class="float"> <i class="bx bx-user ms-2"></i>{{ $course->students }} Students</li>
										</ul>
									</div>
								</div>
							</div>
						@endforeach
					</div>

					<!-- Simple Pagination -->
					<div class="pagination-container">
						<ul class="pagination" id="pagination">
							<li class="page-item">
								<a class="page-link" href="#" id="prevPage">
									<i class="bx bx-chevron-left"></i>
								</a>
							</li>
							<div id="pageNumbers" class="d-flex"></div>
							<li class="page-item">
								<a class="page-link" href="#" id="nextPage">
									<i class="bx bx-chevron-right"></i>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<!-- End Popular Courses Area -->

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
		<script src="assets/js/navbar.js"></script>
		<script src="assets/js/pagination.js"></script>
    </body>
</html>
