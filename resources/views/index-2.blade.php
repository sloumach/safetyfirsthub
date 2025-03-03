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
		<title>SafetyFirstHub</title>
		<!-- Add this line in the head section -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body>
		<!-- Start Preloader Area -->

		<!-- End Preloader Area -->

		<!-- Start Newsletter Modal-->
		<div class="modal-newsletter-area">
			<div class="modal fade" id="exampleModal" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<button type="button" class="close" data-bs-dismiss="modal">
							<i class="bx bx-x"></i>
						</button>

						<div class="modal-body">
							<div class="row">
								<div class="col-lg-5 col-sm-5 p-0">
									<div class="newsletter-img">
										<img src="assets/img/newsletter-img.jpg" alt="Image">
									</div>
								</div>

								<div class="col-lg-7 col-sm-7 pl-0">
									<div class="modal-newsletter-wrap">
										<h3>Subscribe to our newsletter</h3>
										<p>Sign up for our mailing list to get the latest updates & offers.</p>

										<form class="newsletter-form" data-toggle="validator">
											<input type="email" class="form-control" placeholder="Enter email address" name="EMAIL" required autocomplete="off">

											<button class="default-btn" type="submit">
												Subscribe Now
											</button>

											<div id="validator-newsletter-2" class="form-result"></div>

											<div class="agree-label">
												<input type="checkbox" id="dontShowAgain">
												<label for="dontShowAgain">
													Do not show this popup again
												</label>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Newsletter Modal -->

		<!-- Start Navbar Area -->
            @include('navbar')
		<!-- End Navbar Area -->

		<!-- Start Banner Area -->
		<section class="banner-area-two">
			<div class="container">
				<!-- Add the floating icons container -->
				<div class="floating-icons">
					<div class="icon icon-1">
						<i class="fas fa-hard-hat"></i>
					</div>
					<div class="icon icon-2">
						<i class="fas fa-first-aid"></i>
					</div>
					<div class="icon icon-3">
						<i class="fas fa-fire-extinguisher"></i>
					</div>
					<div class="icon icon-4">
						<i class="fas fa-shield-alt"></i>
					</div>
				</div>

				<div class="row align-items-center">
					<div class="col-lg-6">
						<div class="banner-content">
							<h1 class="wow fadeInLeft" data-wow-delay="0.3s">Enhance Your Workplace Safety Skills with Expert Online Training</h1>
							<p class="wow fadeInLeft" data-wow-delay="0.6s">SafetyFirstHub offers professional online certifications covering RF Awareness, Bloodborne Pathogens, Heat Stress, MEWP Training, and more to ensure workplace safety and compliance.</p>
							<a href="{{ route('courses') }}" class="default-btn wow fadeInLeft" data-wow-delay="0.9s">
								View Certifications
							</a>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="banner-img wow fadeInRight" data-wow-delay="0.3s">
							<img src="assets/img/banner-img/banner-img-2.png" alt="Students learning online">
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Banner Area -->

		<!-- Start Affordable Area -->
		<section class="affordable-area pt-100 pb-70">
			<div class="container">
				<div class="section-title">
				<span>Our Affordable</span>	
				<h2>Why Choose SafetyFirstHub?</h2>
					<img src="assets/img/section-title-shape.png" alt="Image">
				</div>

				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="single-affordable one">
							<i class="fas fa-tags size-icon"></i>
							<div class="affordable-content">
								<h3>Affordable Pricing</h3>
								<p class="description">We offer competitive rates with special discounts for bulk orders.</p>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-affordable two">
							<i class="fas fa-laptop-house size-icon"></i>
							<div class="affordable-content">
								<h3>Learn at Your Convenience</h3>
								<p class="description">Access certifications online and study at your own pace, from anywhere with an internet connection.</p>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-affordable three">
							<i class="fas fa-certificate size-icon"></i>
							<div class="affordable-content">
								<h3>Instant Access to Certificates</h3>
								<p class="description">Download and print your certificate as soon as you complete your course.</p>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-affordable four">
							<i class="fas fa-tasks size-icon"></i>
							<div class="affordable-content">
								<h3>Exam Flexibility</h3>
								<p class="description">You have three attempts to pass the final exam, with a required minimum score of 70%.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Affordable Area -->

		<!-- Start Popular Courses Area -->
		<section class="courses-two-area f5f6fa-bg-color ptb-100">
			<div class="container position-relative">
				<!-- Add floating background icons -->
				<div class="course-bg-icons">
					<i class="bx bx-book-open icon-1"></i>
					<i class="bx bx-certification icon-2"></i>
					<i class="bx bx-bulb icon-3"></i>
					<i class="bx bx-line-chart icon-4"></i>
					<i class="bx bx-calendar-check icon-5"></i>
					<i class="bx bx-target-lock icon-6"></i>
				</div>

				<div class="section-title">
					<span>Certifications</span>
					<h2>Online Certifications</h2>
					<img src="assets/img/section-title-shape.png" alt="Image">
				</div>

				<!-- Add Category Filter -->
				<div class="course-filter mb-4">
					<button class="filter-btn active" data-category="all">All Courses</button>
					@php
						$categories = $courses->pluck('category')->unique();
					@endphp
					@foreach($categories as $category)
						<button class="filter-btn" data-category="{{ $category }}">
							{{ $category }}
						</button>
					@endforeach
				</div>

				@if(count($courses) > 0)
					<div class="courses-container">
						<div class="{{ count($courses) > 3 ? 'courses-slider-two owl-theme owl-carousel' : 'row justify-content-center' }}">
							@foreach ($courses as $course)
								<div class="{{ count($courses) <= 3 ? 'col-lg-4 col-md-6' : '' }} course-item" data-category="{{ $course->category }}">
									<div class="single-course wow fadeInUp" data-wow-delay="{{ $loop->index * 0.2 }}s">
										<div class="course-img" style="height: 200px; position: relative; overflow: hidden;">
											<img 
												src="{{ asset('storage/' . $course->cover) }}" 
												alt="{{ $course->name }}"
												style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;"
											>
											<div class="price">${{ round($course->price) }}</div>
											<span class="tag">{{ $course->category }}</span>
										</div>
										<div class="course-content">
											<h3 style="min-height: 48px;">{{ $course->name }}</h3>
											<div class="course-footer">
												<div class="course-info">
													<i class="bx bx-video-recording"></i> {{ $course->total_videos }} Lessons
													<i class="bx bx-user ms-2"></i> {{ $course->students }} Students
												</div>
												<a href="{{ route('singlecourse', ['id' => $course->id]) }}" class="read-more">Learn More <i class="bx bx-right-arrow-alt"></i></a>
											</div>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				@else
					<div class="text-center">
						<p>No certifications available at the moment.</p>
					</div>
				@endif
			</div>
		</section>
		<!-- Start Popular Courses Area -->

		<!-- End Categories Area -->
		{{-- <section class="categories-area ptb-100">
			<div class="container">
				<div class="section-title">
					<span>Categories</span>
					<h2>Top categories</h2>
					<img src="assets/img/section-title-shape.png" alt="Image">
				</div>

				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-1.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>Design</h3>
									</a>
									<span>Over 200+ certifications</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-2.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>Development</h3>
									</a>
									<span>Over 300 certifications</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-3.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>Business</h3>
									</a>
									<span>Over 150 certifications</span>	
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-4.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>Marketing</h3>
									</a>
									<span>Over 200+ certifications</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-5.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>IT & Software</h3>
									</a>
									<span>Over 250 certifications</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-6.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>Data Science</h3>
									</a>
									<span>Over 50 certifications</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-7.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>Photography</h3>
									</a>
									<span>Over 700 certifications</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-categories">
							<img src="assets/img/categories-img/categories-img-8.jpg" alt="Image">

							<div class="categories-content-wrap">
								<div class="categories-content">
									<a href="courses.html">
										<h3>Music</h3>
									</a>
									<span>Over 150 certifications</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12">
						<div class="text-center">
							<a href="courses.html" class="default-btn">All Catagories</a>
						</div>
					</div>
				</div>
			</div>
		</section> --}}
		<!-- End Categories Area -->

		<!-- Start Feature Area -->
		<section class="feature-area py-5">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-6">
						<div class="feature-content-wrapper">
							<div class="feature-card">
								<div class="feature-icon">
									<i class="fas fa-clock"></i>
								</div>
								<div class="feature-content">
									<h2>Learn at Your Own Pace</h2>
									<p>Gain lifetime access to expert-led certifications and advance your skills whenever and wherever you want.</p>
									<div class="feature-line"></div>
								</div>
							</div>

							<div class="feature-card">
								<div class="feature-icon">
									<i class="fas fa-infinity"></i>
								</div>
								<div class="feature-content">
									<h2>Flexible Learning, Unlimited Possibilities</h2>
									<p>Enjoy lifetime access to a wide range of certifications and take control of your learning journey.</p>
									<div class="feature-line"></div>
								</div>
							</div>

							<div class="feature-card">
								<div class="feature-icon">
									<i class="fas fa-chart-line"></i>
								</div>
								<div class="feature-content">
									<h2>Empower Your Growth</h2>
										<p>Access high-quality certifications anytime and build expertise at your own convenience.</p>
									<div class="feature-line"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="feature-img-wrapper position-relative">
							<div class="feature-img wow fadeInRight" data-wow-delay="0.3s">
								<img src="assets/img/banner-img/feature-img.png" alt="Feature image" class="img-fluid">
							</div>
							<!-- Add floating elements for visual interest -->
							<div class="floating-shape-1"></div>
							<div class="floating-shape-2"></div>
						</div>
					</div>
				</div>
			</div>

			
		</section>
		<!-- End Feature Area -->

		<!-- Start Testimonials Area -->
		<div class="testimonials-section">
			<!-- Add background elements -->
			<div class="animated-bg-element element-1"></div>
			<div class="animated-bg-element element-2"></div>
			<div class="animated-bg-element element-3"></div>
			<div class="animated-bg-element element-4"></div>
			
			<!-- Add floating squares -->
			<div class="square square-1"></div>
			<div class="square square-2"></div>

			<div class="feedback-slider owl-theme owl-carousel">
				<div class="feedback-item">
					<div class="quote-mark">"</div>
					<p class="feedback-text">I've been using this platform for a few months now, and I've learned so much. The certifications are well-structured and the instructors are knowledgeable. I highly recommend it!</p>
					<div class="feedback-author">
						<h3>Jessica</h3>
						<span>Designer</span>
					</div>
				</div>

				<div class="feedback-item">
					<div class="quote-mark">"</div>
					<p class="feedback-text">I've been using this platform for a while, and it's been a great experience. The certifications are easy to follow, and the instructors really know their stuff. I highly recommend it!</p>
					<div class="feedback-author">
						<h3>Juhon</h3>
						<span>Marketer</span>
					</div>
				</div>

				<div class="feedback-item">
					<div class="quote-mark">"</div>
					<p class="feedback-text">I've been on this platform for a few months now, and it's been incredibly helpful. The certifications are clear, engaging, and the instructors are experts in their field. I highly recommend giving it a try!</p>
					<div class="feedback-author">
						<h3>Kilva</h3>
						<span>Designer</span>
					</div>
				</div>
			</div>
		</div>
		<!-- End Testimonials Area -->

		<!-- Start Events Area -->
		{{-- <section class="event-area-two ptb-100">
			<div class="container">
				<div class="section-title">
					<span>Education Events</span>
					<h2>Upcoming events</h2>
				</div>

				<div class="row align-items-center">
					<div class="col-lg-6">
						<div class="row">
							<div class="col-lg-12 col-sm-6">
								<div class="single-event">
									<a href="single-event.html">
										<img src="assets/img/event-img/event-img-1.png" alt="Image">
									</a>

									<div class="event-content">
										<ul>
											<li>
												<i class="bx bx-calendar"></i>
												Aug 13, 2020
											</li>
											<li>
												<i class="bx bx-time"></i>
												Monday 3:00AM - 5:00PM
											</li>
										</ul>

										<a href="single-event.html">
											<h3>Comprehensive literacy and reading recovery conference</h3>
										</a>

										<span>
											<i class="bx bxs-location-plus"></i>
											Washington, DC
										</span>
									</div>
								</div>
							</div>

							<div class="col-lg-12 col-sm-6">
								<div class="single-event">
									<a href="single-event.html">
										<img src="assets/img/event-img/event-img-2.png" alt="Image">
									</a>

									<div class="event-content">
										<ul>
											<li>
												<i class="bx bx-calendar"></i>
												Sep 14, 2020
											</li>
											<li>
												<i class="bx bx-time"></i>
												Monday 4:00AM - 6:00PM
											</li>
										</ul>

										<a href="single-event.html">
											<h3>Future of higher education: an invitation to lead</h3>
										</a>

										<span>
											<i class="bx bxs-location-plus"></i>
											New Orleans, LA
										</span>
									</div>
								</div>
							</div>

							<div class="col-lg-12 col-sm-6 offset-sm-3 offset-lg-0">
								<div class="single-event">
									<a href="single-event.html">
										<img src="assets/img/event-img/event-img-3.png" alt="Image">
									</a>

									<div class="event-content">
										<ul>
											<li>
												<i class="bx bx-calendar"></i>
												Oct 01, 2020
											</li>
											<li>
												<i class="bx bx-time"></i>
												Monday 3:00AM - 5:00PM
											</li>
										</ul>

										<a href="single-event.html">
											<h3>National reading recovery & literacy conference</h3>
										</a>

										<span>
											<i class="bx bxs-location-plus"></i>
											Bellevue, WA
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="event-img">
							<img src="assets/img/event-img/event-img-5.png" alt="Image">

							<div class="video-content">
								<a href="https://www.youtube.com/watch?v=iLS_YP1uEK8" class="video-btn popup-youtube">
									<i class="flaticon-play-button"></i>
								</a>
							</div>

							<div class="event-shape-1 rotated">
								<img src="assets/img/event-img/event-shape-1.png" alt="Image">
							</div>

							<div class="event-shape-2">
								<img src="assets/img/event-img/event-shape-2.png" alt="Image">
							</div>
						</div>
					</div>
				</div>
			</div>
		</section> --}}
		<!-- End Events Area -->

		<!-- Start Teachers Area -->
		{{-- <section class="teachers-area-two pt-100 pb-70">
			<div class="container">
				<div class="section-title">
					<span>Our Teachers</span>
					<h2>Our international teachers</h2>
					<img src="assets/img/section-title-shape.png" alt="Image">
				</div>

				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="single-teachers">
							<img src="assets/img/teachers-img/teachers-img-1.jpg" alt="Image">

							<div class="teachers-content">
								<ul>
									<li>
										<a href="#"><i class="bx bxl-instagram"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-facebook"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-linkedin-square"></i></a>
									</li>
								</ul>

								<h3>Earl McGowan</h3>
								<span>IT & Software</span>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-teachers">
							<img src="assets/img/teachers-img/teachers-img-2.jpg" alt="Image">

							<div class="teachers-content">
								<ul>
									<li>
										<a href="#"><i class="bx bxl-instagram"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-facebook"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-linkedin-square"></i></a>
									</li>
								</ul>

								<h3>Chris Miller</h3>
								<span>Mathematic</span>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-teachers">
							<img src="assets/img/teachers-img/teachers-img-3.jpg" alt="Image">

							<div class="teachers-content">
								<ul>
									<li>
										<a href="#"><i class="bx bxl-instagram"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-facebook"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-linkedin-square"></i></a>
									</li>
								</ul>

								<h3>Mark Dent</h3>
								<span>Programmer</span>
							</div>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-teachers">
							<img src="assets/img/teachers-img/teachers-img-4.jpg" alt="Image">

							<div class="teachers-content">
								<ul>
									<li>
										<a href="#"><i class="bx bxl-instagram"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-facebook"></i></a>
									</li>
									<li>
										<a href="#"><i class="bx bxl-linkedin-square"></i></a>
									</li>
								</ul>

								<h3>Lena Bodie</h3>
								<span>English</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section> --}}
		<!-- End Teachers Area -->

		<!-- Start Counter Area -->
		<!-- <section class="counter-area pt-100 pb-70">
			<div class="container">
				<div class="row">
					<div class="col-lg-3 col-sm-6">
						<div class="single-counter">
							<div class="counter-icon">
								<i class="bx bx-book-open"></i>
							</div>
							<h2>
								<span class="odometer" data-count="100">00</span>
								<span class="target">+</span>
							</h2>
							<p>Available Courses</p>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-counter">
							<div class="counter-icon">
								<i class="bx bx-user-plus"></i>
							</div>
							<h2>
								<span class="odometer" data-count="5253">00</span>
							</h2>
							<p>Active Students</p>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-counter">
							<div class="counter-icon">
								<i class="bx bx-certification"></i>
							</div>
							<h2>
								<span class="odometer" data-count="15000">00</span>
								<span class="target">+</span>
							</h2>
							<p>Certificates Delivered</p>
						</div>
					</div>

					<div class="col-lg-3 col-sm-6">
						<div class="single-counter">
							<div class="counter-icon">
								<i class="bx bx-time-five"></i>
							</div>
							<h2>
								<span class="odometer" data-count="2000">00</span>
								<span class="target">+</span>
							</h2>
							<p>Hours of Content</p>
						</div>
					</div>
				</div>
			</div>
		</section> -->
		<!-- End Counter Area -->

		<!-- Start News Area -->
		{{-- <section class="news-area-two pt-100 pb-70">
			<div class="container">
				<div class="section-title">
					<span>Our News</span>
					<h2>Explore recent news</h2>
				</div>

				<div class="row">
					<div class="col-lg-4 col-md-6">
						<div class="single-news">
							<a href="single-blog.html">
								<img src="assets/img/news-img/news-img-5.jpg" alt="Image">
							</a>

							<div class="news-content">
								<span class="tag">Career Advice</span>

								<a href="single-blog.html">
									<h3>Leading the way with lifelong learning</h3>
								</a>

								<ul class="lessons">
									<li>By: <a href="#">Admin</a></li>
									<li class="float">Posted aon 13/07/2020</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6">
						<div class="single-news">
							<a href="single-blog.html">
								<img src="assets/img/news-img/news-img-6.jpg" alt="Image">
							</a>

							<div class="news-content">
								<span class="tag">Market Trends</span>

								<a href="single-blog.html">
									<h3>All aspire students are now student card eligible!</h3>
								</a>

								<ul class="lessons">
									<li>By: <a href="#">Admin</a></li>
									<li class="float">Posted aon 12/07/2020</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 offset-md-3 offset-lg-0">
						<div class="single-news">
							<a href="single-blog.html">
								<img src="assets/img/news-img/news-img-7.jpg" alt="Image">
							</a>

							<div class="news-content">
								<span class="tag">Research</span>

								<a href="single-blog.html">
									<h3>Determining the true goal of a good education is difficult</h3>

								</a>

								<ul class="lessons">
									<li>By: <a href="#">Admin</a></li>
									<li class="float">Posted aon 11/07/2020</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section> --}}
		<!-- End News Area -->

		<!-- Start Partner Area -->
		{{-- <div class="partner-area f5f6fa-bg-color ptb-100">
			<div class="container">
				<div class="partner-wrap owl-theme owl-carousel">
					<div class="partner-item">
						<a href="#">
							<img src="assets/img/partner-logo/partner-logo-1.png" alt="Image">
						</a>
					</div>

					<div class="partner-item">
						<a href="#">
							<img src="assets/img/partner-logo/partner-logo-2.png" alt="Image">
						</a>
					</div>

					<div class="partner-item">
						<a href="#">
							<img src="assets/img/partner-logo/partner-logo-3.png" alt="Image">
						</a>
					</div>

					<div class="partner-item">
						<a href="#">
							<img src="assets/img/partner-logo/partner-logo-4.png" alt="Image">
						</a>
					</div>

					<div class="partner-item">
						<a href="#">
							<img src="assets/img/partner-logo/partner-logo-5.png" alt="Image">
						</a>
					</div>
				</div>
			</div>
		</div> --}}
		<!-- End Partner Area -->
		<!-- <section class="community-section">
			<div class="blob-background"></div>
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-6">
						<div class="community-content">
							<span class="sub-title">SUPPORT FOR ALL EDUCATORS</span>
							<h2>Join a <span class="highlight">Collaborative</span> Community</h2>
							<p class="description">Our unique partnerships and content help districts solve important challenges like:</p>
							
							<ul class="benefits-list">
								<li>Adapting to the changing education landscape</li>
								<li>Retaining top talent</li>
								<li>Fostering career exploration</li>
							</ul>

							<p class="support-text">
								With connections forged through the Discovery Educator Network (DEN) and 
								<a href="#" class="link">Courageous Leaders</a>, regular feedback sessions, and embedded Educator 
								Supports like tested strategies, on-demand <a href="#" class="link">professional learning</a>, and guides, 
								Discovery Education is here to meet the unique needs of every educator.
							</p>

							<a href="#" class="explore-btn">
								Explore the DEN 
								<svg width="24" height="24" viewBox="0 0 24 24">
									<path d="M13.1714 12.0007L8.22168 7.05093L9.63589 5.63672L15.9999 12.0007L9.63589 18.3646L8.22168 16.9504L13.1714 12.0007Z"/>
								</svg>
							</a>
						</div>
					</div>
					
					<div class="col-lg-6">
						<div class="floating-images">
							<img src="assets/img/community/img1.jpg" alt="Teacher" class="float-img img1">
							<img src="assets/img/community/img2.jpg" alt="Teacher working" class="float-img img2">
							<img src="assets/img/community/img3.jpg" alt="Teacher discussion" class="float-img img3">
							<img src="assets/img/community/img4.jpg" alt="Students" class="float-img img4">
						</div>
					</div>
				</div>
			</div>
		</section> -->
		<!-- Start Footer  Area -->
		@include('footer')
		<!-- End Footer  Area -->

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

		<script src="assets/js/popup.js"></script>
		<script src="assets/js/navbar.js"></script>
		<script src="assets/js/course-filter.js"></script>

    </body>
</html>
