<!DOCTYPE html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
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
		<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
		
		<!-- End Preloader Area -->



		<!-- Start Page Title Area -->
		<div class="page-title-area bg-19">
			<div class="container">
				<div class="page-title-content">
					<h2>Shop</h2>
					<ul>
						<li>
							<a href="index.html">
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
			<div class="container">
				<div class="row">
					<div class="col-lg-8">
						<div class="shop-card-wrap">
							{{-- <div class="showing-result">
								<div class="row align-items-center">
									<div class="col-lg-6 col-sm-6">
										<div class="showing-result-count">
											<p>Showing 1-8 of 14 results</p>
										</div>
									</div>

									<div class="col-lg-6 col-sm-6">
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
								</div>
							</div> --}}

							<div class="row">
                                <!-- ici on parcoure les courses	-->
                                @foreach ($products as $product)
                                    <div class="col-lg-4 col-sm-6 mt-5">
                                        <div class="single-shop">
                                            <div class="shop-img">
                                                <img src="{{ asset('storage/' . $product->cover) }}" alt="Image">

                                                <ul>
                                                    <!-- <li>
                                                        <a href="#">
                                                            <a href="#product-view-one{{ $product->id }}" data-bs-toggle="modal">
                                                                <i class="bx bx-show-alt"></i>
                                                            </a>
                                                        </a>
                                                    </li> -->
                                                    <li>
                                                        <a href="#" class="add-to-wishlist" data-course-id="{{ $product->id }}">
                                                            <i class="bx bx-heart"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <h3>{{ $product->name }}</h3>
                                            <span> <del>$49.00</del> ${{ $product->price }}</span>

                                            <a href="{{ route('singleproduct', ['id' => $product->id])  }}" class="default-btn">
                                                View course
                                            </a>
                                        </div>
								    </div>

                                    <div class="modal fade product-view-one" id="product-view-one{{ $product->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <button type="button" class="close" data-bs-dismiss="modal">
                                                    <span aria-hidden="true">
                                                        <i class="bx bx-x"></i>
                                                    </span>
                                                </button>

                                                <div class="row align-items-center">
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="product-view-one-image">
                                                            <div id="big" class="owl-carousel owl-theme">

                                                                <div class="item">
                                                                <img src="{{ asset('storage/' . $product->cover) }}" alt="Image">
                                                                </div>
                                                            </div>

                                                            <div id="thumbs" class="owl-carousel owl-theme">
                                                                <div class="item">
                                                                <img src="{{ asset('storage/' . $product->cover) }}" alt="Image">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="product-content">
                                                            <h3>
                                                                <a href="#">transform: scaleY(1);</a>
                                                            </h3>

                                                            <div class="price">
                                                                <del>$59.00</del> <span class="new-price">$39.00</span>
                                                            </div>

                                                            <div class="product-review">
                                                                <div class="rating">
                                                                    <i class="bx bxs-star"></i>
                                                                    <i class="bx bxs-star"></i>
                                                                    <i class="bx bxs-star"></i>
                                                                    <i class="bx bxs-star"></i>
                                                                    <i class="bx bxs-star"></i>
                                                                </div>
                                                                <a href="#" class="rating-count">3 reviews</a>
                                                            </div>

                                                            <ul class="product-info">
                                                                <li>
                                                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At magnam ad reprehenderit fuga nam, non odit necessitatibus facilis beatae temporibus</p>
                                                                </li>
                                                                <li>
                                                                    <span>Availability:</span> <a href="#">In stock (7 items)</a>
                                                                </li>
                                                                <li>
                                                                    <span>Product Type:</span> <a href="#">Book</a>
                                                                </li>
                                                            </ul>

                                                            <div class="product-add-to-cart">
                                                                <div class="input-counter">
                                                                    <span class="minus-btn">
                                                                        <i class="bx bx-minus"></i>
                                                                    </span>

                                                                    <input type="text" value="1">

                                                                    <span class="plus-btn">
                                                                        <i class="bx bx-plus"></i>
                                                                    </span>
                                                                </div>

                                                                <button type="submit" class="default-btn">
                                                                    Add to Cart
                                                                    <i class="flaticon-right"></i>
                                                                </button>
                                                            </div>

                                                            <div class="share-this-product">
                                                                <h3>Share this product</h3>

                                                                <ul>
                                                                    <li>
                                                                        <a href="#">
                                                                            <i class="bx bxl-facebook"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <i class="bx bxl-twitter"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <i class="bx bxl-instagram"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#">
                                                                            <i class="bx bxl-linkedin"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- pagination	-->
								{{-- <div class="col-lg-12 col-md-12">
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
								</div> --}}
							</div>
						</div>
					</div>

					<div class="col-lg-4">
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
					</div>
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
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="assets/js/shop.js"></script>
    </body>
</html>
