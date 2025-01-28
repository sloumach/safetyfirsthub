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
		<title>Eduon - Online Courses & Training HTML Template</title>
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
		<div class="page-title-area bg-20">
			<div class="container">
				<div class="page-title-content">
					<h2>Cart</h2>
					<ul>
						<li>
							<a href="index.html">
								Home
							</a>
						</li>

						<li class="active">Cart</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Cart Area -->
		<section class="cart-area ptb-100">
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-md-12">
						<form>
							<div class="cart-wraps">
								<div class="cart-table table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<th scope="col">Image</th>
												<th scope="col">Product name</th>
												<th scope="col">Price</th>
												<th scope="col">Total</th>
											</tr>
										</thead>

										<tbody>
                                            @if(!$courses->isEmpty())


                                            <!-- ici on parcoure le contenu de la cart	-->
                                            @foreach($courses as $course)
                                                <tr>
                                                    <td class="product-thumbnail">
                                                        <a href="#">
                                                            <img src="{{ asset('storage/' . $course->cover) }}" alt="Image">
                                                        </a>
                                                    </td>

                                                    <td class="product-name">
                                                        <a href="#">{{ $course->name }}</a>
                                                    </td>

                                                    <td class="product-price">
                                                        <span class="unit-amount">${{ $course->price }}</span>
                                                    </td>


                                                    <td class="product-subtotal">
                                                        <span class="subtotal-amount">${{ $course->price }}</span>

                                                        <a href="#" class="remove">
                                                            <i class="bx bx-x"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @endif

										</tbody>
									</table>
								</div>

								<div class="coupon-cart">
									<div class="row">
										{{-- <div class="col-lg-8 col-sm-7">
											<div class="form-group mb-0">
												<input type="text" class="form-control" placeholder="Coupon code">

												<a href="#" class="default-btn">Apply coupon</a>
											</div>
										</div> --}}
                                        @if(!$courses->isEmpty())
                                            <div class="col-lg-4 col-sm-5 text-right">
                                                <a href="#" class="default-btn update">
                                                    Update cart
                                                </a>
                                            </div>
                                        @endif

									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="col-lg-4">
						<h3 class="cart-checkout-title">Checkout summary</h3>
						<div class="cart-totals">
							<ul>
								<li>Subtotal <span>${{ $subtotal = $courses->sum('price'); }}</span></li>
								<li>Total <span>${{ $subtotal = $courses->sum('price'); }}</span></li>
								<li><b>Payable Total</b> <span><b>${{ $subtotal = $courses->sum('price'); }}</b></span></li>
							</ul>

							<a href="{{ route('checkout') }}" class="default-btn two">
								Buy now
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Cart Area -->

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
