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
		<title>Safety FirstHUB</title>
    </head>
    <body>

        <!-- Start Navbar Area -->
        @include('navbar')
        <!-- End Navbar Area -->
		<!-- Start Preloader Area -->
		<div class="loader-wrapper">
			<div class="loader">
				<div class="dot-wrap">
					<span class="dot"></span>
					<span class="dot"></span>
					<span class="dot"></span>
					<span class="dot"></span>
				</div>
			</div>
		</div>
		<!-- End Preloader Area -->



		<!-- Start Page Title Area -->
		<div class="page-title-area bg-4">
			<div class="container">
				<div class="page-title-content">
					<h2>Courses</h2>
					<ul>
						<li>
							<a href="index.html">
								Home
							</a>
						</li>

						<li class="active">courses</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Popular Courses Area -->
		<section class="courses-area-style ptb-100">
			<div class="container">
				<div class="showing-result">
					<div class="row align-items-center">
						<div class="col-lg-6 col-md-4">
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
						</div>

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

				<div class="row">
                    <!-- ici on parcoure les courses -->
                    @foreach ($courses as $course)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-course">
                                <a href="{{ route('singlecourse', ['id' => $course->id]) }}">
                                    <img src="{{ asset('storage/' . $course->cover) }}" class="card-img-top" alt="Cover for {{ $course->category }}">

                                </a>

                                <div class="course-content">
                                    <span class="price">$39</span>
                                    <span class="tag">{{ $course->category }}</span>

                                    <a href="{{ route('singlecourse', ['id' => $course->id]) }}">

                                        <h3>{{ $course->name }}</h3>
                                    </a>

                                    {{-- <ul class="rating">
                                        <li>
                                            <i class="bx bxs-star"></i>
                                        </li>
                                        <li>
                                            <i class="bx bxs-star"></i>
                                        </li>
                                        <li>
                                            <i class="bx bxs-star"></i>
                                        </li>
                                        <li>
                                            <i class="bx bxs-star"></i>
                                        </li>
                                        <li>
                                            <i class="bx bxs-star"></i>
                                        </li>
                                        <li>
                                            <span>0.5</span>
                                            <a href="#">(1 rating)</a>
                                        </li>
                                    </ul> --}}

                                    <p>{{ $course->short_description }}</p>

                                    <ul class="lessons">
                                        {{-- <li>0 Lessons</li> --}}
                                        <li class="float">{{ $course->students }} Students</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- pagination -->
					<div class="col-lg-12 col-md-12">
                        <div class="pagination-area">
							<!--
                            <a href="#" class="prev page-numbers">
								<i class="bx bx-chevron-left"></i>
							</a>
							-->
                            <span class="page-numbers current" aria-current="page">1</span>
                            <a href="#" class="page-numbers">2</a>
                            <a href="#" class="page-numbers">3</a>
							<a href="#" class="page-numbers">4</a>

                            <a href="#" class="next page-numbers">
								<i class="bx bx-chevron-right"></i>
							</a>
                        </div>
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
    </body>
</html>
