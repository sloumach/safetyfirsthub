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
		<link rel="stylesheet" href="assets/css/cart.css">
		<link rel="stylesheet" href="assets/css/navbar.css">
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="assets/img/favicon.png">
		<!-- Title -->
		<title>Safety FirstHUB</title>
		<!-- DataTables CSS -->
		<link rel="stylesheet" href="adminassets/vendor/datatables/dataTables.bootstrap4.min.css">
    </head>
    <body>

        <!-- Start Navbar Area -->
		@include('navbar')
		<!-- End Navbar Area -->
		<!-- Start Preloader Area -->

		<!-- End Preloader Area -->



		<!-- Start Page Title Area -->
		<div class="page-title-area bg-20">
			<div class="container">
				<div class="page-title-content">
					<h2>Cart</h2>
					<ul>
						<li>
						<a href="{{ route('home') }}">
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
					<div class="col-lg-8">
						<form>
							<div class="cart-wraps">
								<div class="cart-table table-responsive">
									<table class="table table-bordered" id="cartTable">
										<thead>
											<tr>
												<th>IMAGE</th>
												<th>PRODUCT NAME</th>
												<th>PRICE</th>
												<th>ACTIONS</th>
											</tr>
										</thead>

										<tbody>
											@if(!$courses->isEmpty())
												@foreach($courses as $course)
													<tr data-id="{{ $course->id }}" data-remove-url="{{ route('remove.from.cart') }}">
														<td class="product-thumbnail" data-label="Image">
															<img src="{{ asset('storage/' . $course->cover) }}" alt="Image">
														</td>
														<td class="product-name" data-label="Product Name">
															{{ $course->name }}
														</td>
														<td class="product-price" data-label="Price">
															${{ $course->price }}
														</td>
														<td class="remove-column" data-label="Actions">
															<button class="remove">×</button>
														</td>
													</tr>
												@endforeach
											@endif
										</tbody>
									</table>
								</div>

								<div class="coupon-cart">
									<div class="row">
										
                                        @if(!$courses->isEmpty())
                                            <!-- <div class="col-lg-4 col-sm-5 text-right">
                                                <a href="#" class="default-btn update">
                                                    Update cart
                                                </a>
                                            </div> -->
                                        @endif

									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="col-lg-4">
						<div class="cart-checkout-title">
							Checkout summary
						</div>
						<div class="cart-totals">
							<ul>
								<li>Subtotal <span>${{ $subtotal = $courses->sum('price'); }}</span></li>
								<li>Total <span>${{ $subtotal = $courses->sum('price'); }}</span></li>
								<li>Payable Total <span>${{ $subtotal = $courses->sum('price'); }}</span></li>
							</ul>
							
							<!-- Coupon Section -->
							<div class="coupon-wrapper">
								<div class="coupon-container">
									<button type="button" class="coupon-trigger">
										Have a coupon?
									</button>
									<div class="coupon-input-group">
										<input type="text" class="form-control" placeholder="Coupon code">
										<button type="button" class="coupon-apply-btn">Apply</button>
									</div>
								</div>
							</div>
							
							<a href="{{ route('checkout') }}" class="default-btn two">
								Buy Now
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- DataTables JS -->
        <script src="adminassets/vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="adminassets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#cartTable').DataTable({
                    pageLength: 4,
                    lengthMenu: [4, 8, 12],
                    language: {
                        lengthMenu: "Show _MENU_ items per page",
                        info: "",
                        infoEmpty: "",
                        search: "Search:",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        },
                        zeroRecords: "No data available in table"
                    },
                    columnDefs: [
                        { orderable: false, targets: [0, 3] }
                    ],
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12"p>>'
                });
            });
        </script>
    </body>
    <script src="{{ asset('assets/js/cart.js') }}"></script>
    <script src="assets/js/navbar.js"></script>
</html>
