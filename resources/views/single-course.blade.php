<!DOCTYPE html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
        <!-- Owl Theme Default Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}">
        <!-- Owl Carousel Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
        <!-- Owl Magnific Popup Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.min.css') }}">
        <!-- Animate Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}">
        <!-- Boxicons Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/boxicons.min.css') }}">
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
        <!-- Meanmenu Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/meanmenu.min.css') }}">
        <!-- Nice Select Min CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/nice-select.min.css') }}">
        <!-- Odometer Min CSS-->
        <link rel="stylesheet" href="{{ asset('assets/css/odometer.min.css') }}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <!-- Dark CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/dark.css') }}">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
		<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
<link rel="stylesheet" href="{{ asset('assets/css/single-course.css') }}">
		<!-- Title -->
		<title>Safety FirstHUB</title>
    </head>
    <body>
		<!-- Start Preloader Area -->
	
		<!-- End Preloader Area -->

		<!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-25">
			<div class="container">
				<div class="page-title-content">
					<h2>Single course</h2>
					<ul>
						<li>
							<a href="{{ route('home') }}">
								Home
							</a>
						</li>

						<li class="active">Single course</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Single Course Area -->
		<section class="single-course-area ptb-100">
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<div class="single-course-content">
							<div class="row">
								<h3>
									{{ $course->name }}
									<div class="course-rating">
										<h4>Categories:</h4>
										<span>{{ $course->category }}</span>
									</div>
								</h3>
							</div>

							<div class="course-content-wrapper">
								<div class="course-image-container">
									<img src="{{ asset('storage/' . $course->cover) }}" alt="Image">
								</div>
								<div class="course-description-container">
									<div class="tab">
										<ul class="tabs">
											<li>
												<a href="#">Overview</a>
											</li>
										</ul>
										<div class="tab_content">
											<div class="tabs_item">
												<h4>Course Description</h4>
												<p>{{ $course->description }}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						
					</div>

					<div class="col-lg-4">
						<div class="account-wrap">
							<ul>
                                @if(auth()->user() && auth()->user()->courses->contains($course->id))
                                <li>
									Product: <span class="bold bg">Owned</span>
								</li>
                                @else
                                <li>
                                    Price <span class="bold">${{ $course->price }}</span>
                                </li>
                                @endif

								{{-- <li>
									Start <span>Sep 01, 2020</span>
								</li>
								<li>
									End <span>Sep 02, 2020</span>
								</li> --}}
								<li>
									Event Category <span>{{ $course->category }}</span>
								</li>
								<li>
									Total videos: <span>{{ $course->total_videos }}</span>
								</li>
								{{-- <li>
									Booked Slot: <span>00</span>
								</li> --}}
								{{-- <li>
									Website: <a href="#">www.eduon.com</a>
								</li> --}}
							</ul>

							@if(auth()->user() && auth()->user()->courses->contains($course->id))
                                <a href="{{ route('dashboard') }}" class="default-btn">Go to Dashboard</a>
                            @else
                                <a href="{{ route('singleproduct', ['id' => $course->id]) }}" class="default-btn">Book now</a>
                            @endif

							{{-- <div class="social-content">
								<p>
									Share this course
									<i class="bx bxs-share-alt"></i>
								</p>

								<ul>
									<li>
										<a href="#">
											<i class='bx bxl-facebook'></i>
										</a>
									</li>
									<li>
										<a href="#">
											<i class='bx bxl-twitter'></i>
										</a>
									</li>
									<li>
										<a href="#">
											<i class='bx bxl-instagram'></i>
										</a>
									</li>
									<li>
										<a href="#">
											<i class='bx bxl-behance'></i>
										</a>
									</li>
								</ul>
							</div> --}}
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Single Course Area -->

		<!-- Start Popular Courses Area -->
		{{-- <section class="courses-area-style pb-70">
			<div class="container">
				<div class="section-title">
					<h2>Related Courses</h2>
				</div>

				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="single-course">
							<a href="single-course.html">
								<img src="{{ asset('assets/img/course-img/course-img-1.jpg') }}" alt="Image">
							</a>

							<div class="course-content">
								<span class="price">$39</span>
								<span class="tag">Education</span>

								<a href="single-course.html">
									<h3>Developing strategies for online teaching and learning</h3>
								</a>

								<ul class="rating">
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
								</ul>

								<p>Lorem ipsum dolor sit amet, consectetur adip iscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>

								<ul class="lessons">
									<li>0 Lessons</li>
									<li class="float">44 Students</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6">
						<div class="single-course">
							<a href="single-course.html">
								<img src="{{ asset('assets/img/course-img/course-img-2.jpg') }}" alt="Image">
							</a>

							<div class="course-content">
								<span class="price">$59</span>
								<span class="tag">Accounting</span>

								<a href="single-course.html">
									<h3>Introduction to cybersecurity for teachers</h3>
								</a>

								<ul class="rating">
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
										<a href="#">(3 rating)</a>
									</li>
								</ul>

								<p>Lorem ipsum dolor sit amet, consectetur adip iscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>

								<ul class="lessons">
									<li>0 Lessons</li>
									<li class="float">24 Students</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 offset-md-3 offset-lg-0">
						<div class="single-course">
							<a href="single-course.html">
								<img src="{{ asset('assets/img/course-img/course-img-3.jpg') }}" alt="Image">
							</a>

							<div class="course-content">
								<span class="price">$29</span>
								<span class="tag">Language</span>

								<a href="single-course.html">
									<h3>English: spelling, punctuation, and grammar</h3>
								</a>

								<ul class="rating">
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
										<a href="#">(5 rating)</a>
									</li>
								</ul>

								<p>Lorem ipsum dolor sit amet, consectetur adip iscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>

								<ul class="lessons">
									<li>0 Lessons</li>
									<li class="float">39 Students</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section> --}}
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
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <!-- Bootstrap Bundle Min JS -->
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <!-- Meanmenu Min JS -->
        <script src="{{ asset('assets/js/meanmenu.min.js') }}"></script>
        <!-- Owl Carousel Min JS -->
        <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
        <!-- Wow Min JS -->
        <script src="{{ asset('assets/js/wow.min.js') }}"></script>
        <!-- Nice Select Min JS -->
        <script src="{{ asset('assets/js/nice-select.min.js') }}"></script>
        <!-- Magnific Popup Min JS -->
        <script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
        <!-- Jarallax Min JS -->
        <script src="{{ asset('assets/js/jarallax.min.js') }}"></script>
        <!-- Appear Min JS -->
        <script src="{{ asset('assets/js/appear.min.js') }}"></script>
        <!-- Odometer JS -->
        <script src="{{ asset('assets/js/odometer.min.js') }}"></script>
        <!-- Form Validator Min JS -->
        <script src="{{ asset('assets/js/form-validator.min.js') }}"></script>
        <!-- Contact JS -->
        <script src="{{ asset('assets/js/contact-form-script.js') }}"></script>
        <!-- Ajaxchimp Min JS -->
        <script src="{{ asset('assets/js/ajaxchimp.min.js') }}"></script>
        <!-- Custom JS -->
        <script src="{{ asset('assets/js/custom.js') }}"></script>
		<script src="{{ asset('assets/js/navbar.js') }}"></script>
    </body>
</html>
