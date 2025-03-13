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
		<title>Safety FirstHUB</title>
		<!-- Add this in the head section -->
		<link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
    </head>
    <body>
		<!-- Start Preloader Area -->

		<!-- End Preloader Area -->

		<!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-21">
			<div class="container">
				<div class="page-title-content">
					<h1>Checkout</h1>
					<ul>
						<li>
							<a href="{{ route('home') }}">
								Home
							</a>
						</li>

						<li class="active">Checkout</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Checkout Area -->
		<section class="checkout-area ptb-100">
			<div class="container">
                <form method="POST" action="{{ route('charge') }}">
                    @csrf <!-- Protection CSRF -->

                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            <div class="billing-details">
                                <h3 class="title">Billing details</h3>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>First name <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="first_name" value="{{ auth()->user()->firstname }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="form-group">
                                            <label>Last name <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="last_name" value="{{ auth()->user()->lastname }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Country <span class="required">*</span></label>
                                            <select class="form-control" name="country" id="countrySelect" required>
                                                <option value="">Select a country</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-6">
                                        <div class="form-group">
                                            <label>Street address <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="street_address" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-6">
                                        <div class="form-group">
                                            <label>Town / City <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="city" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>State <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="state" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Zip <span class="required">*</span></label>
                                            <input type="text" class="form-control" name="zip" required>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="order-details">
                                <!-- Checkout summary -->
                                <div class="cart-totals">
                                    <h3>Checkout summary</h3>
                                    <ul>
                                        <li>Total: <span>${{ $subtotal }}</span></li>
                                        <li>Discount Applied: <span>${{ session('discount', 0) }}</span></li>
                                        <li><b>Payable Total</b>: <span><b>${{ session('payable_total', $subtotal) }}</b></span></li>
                                    </ul>
                                </div>


                                <!-- Champs cachés pour transmettre le discount -->
                                <input type="hidden" name="discount" value="{{ session('discount', 0) }}">
                                <input type="hidden" name="coupon_code" value="{{ session('coupon_code', '') }}">


                                <!-- Champ caché pour envoyer le subtotal -->
                                <input type="hidden" name="subtotal" value="{{ $subtotal }}">

                                <div class="place-order">
                                    <button type="submit" class="default-btn two">Payment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

			</div>
		</section>
		<!-- End Checkout Area -->

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
    <script src="assets/js/countries.js"></script>
</html>
