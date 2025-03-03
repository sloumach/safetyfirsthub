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
		<link rel="stylesheet" href="assets/css/navbar.css">
		<!-- DataTables CSS -->
		<link rel="stylesheet" href="adminassets/vendor/datatables/dataTables.bootstrap4.min.css">
		<!-- Favicon -->
		<link rel="stylesheet" href="assets/css/wishlist.css">
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
		<div class="page-title-area bg-22">
			<div class="container">
				<div class="page-title-content">
					<h2 style="color: black !important;">Wishlist</h2>
					<ul>
						<li>
						<a href="{{ route('home') }}">
								Home
							</a>
						</li>

						<li class="active" style="color: black !important;">Wishlist</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- End Page Title Area -->

		<!-- Start Cart Area -->
		<div class="cart-area ptb-100">
			<div class="container">
				<form>
					<div class="cart-wraps wishlist-wrap">
						<div class="cart-table table-responsive">
							<table class="table table-bordered" id="wishlistTable">
								<thead>
									<tr>
										<th scope="col">Image</th>
										<th scope="col">Product name</th>
										<th scope="col">Price</th>
										<th scope="col">Total</th>
										<th scope="col">Actions</th>
									</tr>
								</thead>

								<tbody>
                                    @foreach($wishlistedCourses as $wishlistItem)
                                        <tr data-id="{{ $wishlistItem->course->id }}" 
                                            data-add-url="{{ route('add.to.cart') }}"
                                            data-remove-url="{{ route('remove.from.wishlist') }}">
                                            <td class="product-thumbnail" data-label="Image">
                                                <a href="#">
                                                    <img src="{{ asset('storage/' . $wishlistItem->course->cover) }}" alt="Image">
                                                </a>
                                            </td>

                                            <td class="product-name" data-label="Product Name">
                                                <a href="#">{{ $wishlistItem->course->name }}</a>
                                            </td>

                                            <td class="product-price" data-label="Price">
                                                <span class="unit-amount">${{ $wishlistItem->course->price }}</span>
                                            </td>

                                            <td class="product-subtotal" data-label="Total">
                                                <span class="subtotal-amount">${{ $wishlistItem->course->price }}</span>
                                            </td>

                                            <td class="product-actions" data-label="Actions">
                                                <button type="button" class="wishlist-cart-btn">
                                                    <i class="flaticon-shopping-cart"></i>
                                                </button>
                                                <button type="button" class="wishlist-remove-btn">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
								</tbody>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
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
		<!-- Jarallax Min JS -->
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
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- DataTables JS -->
        <script src="adminassets/vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="adminassets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <!-- Wishlist JS -->
        <script src="{{ asset('assets/js/wishlist.js') }}"></script>
		<script src="assets/js/navbar.js"></script>
        <script>
            $(document).ready(function() {
                $('#wishlistTable').DataTable({
                    pageLength: 4,
                    lengthMenu: [4, 8, 12],
                    language: {
                        lengthMenu: "Show _MENU_ items per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ items",
                        infoEmpty: "No items available",
                        search: "Search:",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    }
                });
            });
        </script>
    </body>
</html>
