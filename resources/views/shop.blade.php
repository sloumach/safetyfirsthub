<!DOCTYPE html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
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
		<link rel="stylesheet" href="assets/css/navbar.css">
		<link rel="stylesheet" href="assets/css/footer.css">
		<!-- Dark CSS -->
		<link rel="stylesheet" href="assets/css/dark.css">
		<!-- Responsive CSS -->
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="assets/img/favicon.png">
		<link rel="stylesheet" href="assets/css/shop.css">
		<!-- Title -->	
		<title>Safety FirstHUB</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body>

        <!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-19">
			<div class="container">
				<div class="page-title-content">
					<h1>Shop</h1>
					<ul>
						<li>
						<a href="{{ route('home') }}">
								Home
							</a>
						</li>

						<li class="active">Shop</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Shop Area -->
		<div class="shop-area ptb-100">
			<!-- SVG Icons -->
			<svg class="moving-icon icon-1" viewBox="0 0 24 24" width="24" height="24">
				<path fill="currentColor" d="M19 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h13c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 18H6V4h13v16z"/>
				<path fill="currentColor" d="M8 6h8v2H8zm0 4h8v2H8zm0 4h5v2H8z"/>
			</svg>

		

			<svg class="moving-icon icon-3" viewBox="0 0 24 24" width="24" height="24">
				<path fill="currentColor" d="M20.71 5.63l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-3.12 3.12-1.93-1.91-1.41 1.41 1.42 1.42L3 16.25V21h4.75l8.92-8.92 1.42 1.42 1.41-1.41-1.92-1.92 3.12-3.12c.4-.4.4-1.03.01-1.42zM6.92 19H5v-1.92l8.06-8.06 1.92 1.92L6.92 19z"/>
			</svg>

			<svg class="moving-icon icon-4" viewBox="0 0 24 24" width="24" height="24">
				<path fill="currentColor" d="M9 21c0 .55.45 1 1 1h4c.55 0 1-.45 1-1v-1H9v1zm3-19C8.14 2 5 5.14 5 9c0 2.38 1.19 4.47 3 5.74V17c0 .55.45 1 1 1h6c.55 0 1-.45 1-1v-2.26c1.81-1.27 3-3.36 3-5.74 0-3.86-3.14-7-7-7zm2.85 11.1l-.85.6V16h-4v-2.3l-.85-.6C7.8 12.16 7 10.63 7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.63-.8 3.16-2.15 4.1z"/>
			</svg>
			
			<div class="container">
				<!-- Simple Typewriter Intro -->
				<div class="shop-intro-simple">
					<div class="slide-in-text">
						<span class="word">Discover</span>
						<span class="word">Your</span>
						<span class="word">Next</span>
						<span class="word">Course</span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8">
						<div class="shop-card-wrap">
							<div class="row" id="shopGrid">
								@foreach ($products as $product)
									<div class="col-lg-4 col-sm-6 mt-5 shop-item">
										<div class="single-shop">
											<div class="shop-img">
												<img src="{{ asset('storage/' . $product->cover) }}" alt="Image">
												<ul>
													<li>
														<a href="#" class="add-to-wishlist" data-course-id="{{ $product->id }}">
															<i class="bx bx-heart"></i>
														</a>
													</li>
												</ul>
											</div>

											<h3>{{ $product->name }}</h3>
											<span> <del>$49.00</del> ${{ $product->price }}</span>

											<a href="{{ route('singleproduct', ['id' => $product->id]) }}" class="default-btn">
												View course
											</a>
										</div>
									</div>
								@endforeach
							</div>

							<!-- Pagination -->
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

					<!-- <div class="col-lg-4">
						<div class="widget-sidebar">


							{{-- <div class="sidebar-widget popular-post">
								<h3 class="widget-title">Popular Posts</h3>

								<div class="post-wrap">
									<div class="item">
										<a href="single-blog.html" class="thumb">
											<span class="fullimage cover bg1" role="img"></span>
										</a>

										<div class="info">
											<h4 class="title">
												<a href="single-blog.html">We will begin to explore the next two levels</a>

												<span class="date">20-07-2020</span>
											</h4>
										</div>
									</div>

									<div class="item">
										<a href="single-blog.html" class="thumb">
											<span class="fullimage cover bg2" role="img"></span>
										</a>

										<div class="info">
											<h4 class="title">
												<a href="single-blog.html">Determining the true goal of a good education is difficult.</a>

												<span class="date">19-07-2020</span>
											</h4>
										</div>
									</div>

									<div class="item">
										<a href="single-blog.html" class="thumb">
											<span class="fullimage cover bg3" role="img"></span>
										</a>

										<div class="info">
											<h4 class="title">
												<a href="single-blog.html">Implementing peer assessment in online group</a>

												<span class="date">18-07-2020</span>
											</h4>
										</div>
									</div>
								</div>
							</div> --}}


						</div>
					</div> -->
				</div>
			</div>
		</div>
		<!-- End Shop Area -->

		<!-- Start Product View One Area -->

		<!-- End Product View One Area -->

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/shop.js"></script>
</html>
