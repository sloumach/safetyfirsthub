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

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">

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
							<h3>{{ $course->name }}</h3>

							<div class="row align-items-center">
								{{-- <div class="col-lg-4 col-sm-4">
									<div class="course-rating">
										<img src="{{ asset('assets/img/single-course/rating-img-1.jpg') }}" alt="Image">

										<h4><a href="#">Instructor:</a></h4>
										<span>Jeremy Cioara</span>
									</div>
								</div> --}}

								<div class="col-lg-6 col-sm-4">
									<div class="course-rating pl-0 text-center">
										<h4>Categories:</h4>
										<span>{{ $course->category }}</span>
									</div>
								</div>

								{{-- <div class="col-lg-4 col-sm-4">
									<div class="course-rating star pl-0">
										<h4>Reviews</h4>

										<div class="product-review">
											<div class="rating">
												<i class='bx bxs-star'></i>
												<i class='bx bxs-star'></i>
												<i class='bx bxs-star'></i>
												<i class='bx bxs-star'></i>
												<i class='bx bxs-star-half'></i>
											</div>
											<a href="#" class="rating-count">2 reviews</a>
										</div>
									</div>
								</div> --}}
							</div>
                            <img src="{{ asset('storage/' . $course->cover) }}" alt="Image">						</div>

						<div class="tab single-course-tab">
							<ul class="tabs">
								<li>
									<a href="#">Overview</a>
								</li>
								{{-- <li>
									<a href="#">Curriculum</a>
								</li>
								<li>
									<a href="#"> Instructor</a>
								</li>
								<li>
									<a href="#">Reviews</a>
								</li> --}}
							</ul>

							<div class="tab_content">
								<div class="tabs_item">
									<h3>Course Description</h3>

									<p>{{ $course->description }}</p>

									{{-- <h3>Certification Online</h3> --}}

									{{-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis  sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p> --}}

{{-- 									<h3>What you’ll learn?</h3>

									<ul class="course-list">
										<li>
											<i class="bx bx-check"></i>
											Lorem ipsum dolor sit amet, consectetur adipiscing elit,
										</li>
										<li>
											<i class="bx bx-check"></i>
											Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
										</li>
										<li>
											<i class="bx bx-check"></i>
											Quis ipsum suspendisse ultrices gravida.
										</li>
										<li>
											<i class="bx bx-check"></i>
											Risus commodo viverra maecenas accumsan lacus vel facilisis.
										</li>
									</ul>

									<h3>Who This Course is for</h3>

									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis  sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
 --}}								</div>

								<div class="tabs_item">
									<div class="curriculum-content">
										<h3>Education introduction</h3>

										<div class="curriculum-list">
											<h4>Section 1</h4>

											<ul>
												<li>
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Introduction
													</a>
													<a href="#" class="meet-time">
														<span class="min">01 hour</span>
														<span class="preview">Preview</span>
													</a>
												</li>

												<li class="transparent">
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Environment setup
													</a>
													<a href="#" class="meet-time">
														<span class="min">02 hours</span>
														<span class="preview">Preview</span>
													</a>
												</li>

												<li>
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Quiz one
													</a>
													<a href="#" class="meet-time">
														<span class="min">01 min</span>
														<span class="preview">4 question</span>
														<i class="bx bxs-lock-alt"></i>
													</a>
												</li>
											</ul>
										</div>

										<div class="curriculum-list">
											<h4>Section 2</h4>

											<ul>
												<li>
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Utility modules
													</a>
													<a href="#" class="meet-time">
														<span class="min">03 hours</span>
														<i class="bx bxs-lock-alt"></i>
													</a>
												</li>

												<li class="transparent">
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Express framework
													</a>
													<a href="#" class="meet-time">
														<span class="min">05 min</span>
														<i class="bx bxs-lock-alt"></i>
													</a>
												</li>

												<li>
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Web module
													</a>
													<a href="#" class="meet-time">
														<span class="min">01 hour</span>
														<i class="bx bxs-lock-alt"></i>
													</a>
												</li>
											</ul>
										</div>

										<div class="curriculum-list">
											<h4>Section 3</h4>

											<ul>
												<li>
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Video introduction
													</a>
													<a href="#" class="meet-time">
														<span class="min">30 min</span>
														<i class="bx bxs-lock-alt"></i>
													</a>
												</li>

												<li class="transparent">
													<a href="#" class="meet-title">
														<i class="bx bx-right-arrow"></i>
														Web module
													</a>
													<a href="#" class="meet-time">
														<span class="min">05 hours</span>
														<i class="bx bxs-lock-alt"></i>
													</a>
												</li>
											</ul>
										</div>
									</div>
								</div>

								<div class="tabs_item">
									<div class="instructor-content">
										<div class="row align-items-center">
											<div class="col-lg-4">
												<div class="advisor-img">
													<img src="{{ asset('assets/img/instructor-img.jpg') }}" alt="Image">
												</div>
											</div>

											<div class="col-lg-8">
												<div class="advisor-content">
													<a href="#">
														<h3>Anna Dew</h3>
													</a>

													<span>Agile Project Expert</span>
													<p>Jone Smit is a celebrated photographer, author, and writer who brings passion to everything he does.</p>

													<ul>
														<li>
															<a href="#">
																<i class='bx bxl-facebook'></i>
															</a>
														</li>
														<li>
															<a href="#">
																<i class='bx bxl-twitter' ></i>
															</a>
														</li>
														<li>
															<a href="#">
																<i class='bx bxl-youtube'></i>
															</a>
														</li>
														<li>
															<a href="#">
																<i class='bx bxl-behance'></i>
															</a>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="tabs_item">
									<div class="review-content">
										<h3>Course rating</h3>

										<ul class="rating-star">
											<li>
												<i class='bx bxs-star'></i>
											</li>
											<li>
												<i class='bx bxs-star'></i>
											</li>
											<li>
												<i class='bx bxs-star'></i>
											</li>
											<li>
												<i class='bx bxs-star'></i>
											</li>
											<li>
												<i class='bx bxs-star'></i>
											</li>
										</ul>

										<span>5.00 average based on 1 rating</span>

										<div class="rating-bar-content">
											<div class="single-bar">
												<p class="start">Star</p>
												<div class="rating-bar">
													<div class="skills html"></div>
												</div>
												<p class="percent">90%</p>
											</div>

											<div class="single-bar">
												<p class="start">Star</p>
												<div class="rating-bar">
													<div class="skills css"></div>
												</div>
												<p class="percent">80%</p>
											</div>

											<div class="single-bar">
												<p class="start">Star</p>
												<div class="rating-bar">
													<div class="skills js"></div>
												</div>
												<p class="percent">65%</p>
											</div>

											<div class="single-bar">
												<p class="start">Star</p>
												<div class="rating-bar">
													<div class="skills php"></div>
												</div>
												<p class="percent">60%</p>
											</div>
										</div>

										<div class="course-reviews-content">
											<h3>Reviews</h3>
											<ul class="course-reviews">
												<li>
													<img src="{{ asset('assets/img/course-reviews-img.jpg') }}" alt="Image">

													<h3>Anna Dew</h3>
													<span>Cover all my needs</span>
													<p>The course identify things we want to change and then figure out the things that need to be done to create the desired outcome. The course helped me in clearly define problems and generate a wider variety of quality solutions. Support more structures analysis of options.</p>
												</li>
											</ul>
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

    </body>
</html>
