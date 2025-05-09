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
		<!-- Dark CSS -->
		<link rel="stylesheet" href="assets/css/dark.css">
		<!-- Responsive CSS -->
		<link rel="stylesheet" href="assets/css/responsive.css">
		<link rel="stylesheet" href="assets/css/cart.css">
		<link rel="stylesheet" href="assets/css/navbar.css">
		<link rel="stylesheet" href="assets/css/footer.css">
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

		<!-- Start Page Title Area -->
		<div class="page-title-area bg-20">
			<div class="container">
				<div class="page-title-content">
					<h1>Cart</h1>
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
                                        <input type="text" class="form-control" placeholder="Coupon code" id="coupon_code">
                                        <button type="button" class="coupon-apply-btn">Apply</button>
                                    </div>
                                    <div id="coupon-message" class="mt-2"></div>
                                </div>
                            </div>

                            <!-- Affichage du total mis à jour -->
                            <div class="cart-totals">
                                <ul>
                                    <li>Subtotal: <span id="subtotal">${{ $subtotal }}</span></li>
                                    <li>Discount: <span id="discount">$0</span></li>
                                    <li><b>Payable Total</b>: <span id="payable_total"><b>${{ $subtotal }}</b></span></li>
                                </ul>
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

    </body>
    @include('layouts.scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelector('.coupon-apply-btn').addEventListener('click', function() {
            let couponCode = document.querySelector('#coupon_code').value;
            fetch("{{ route('admin.apply.coupon') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ coupon_code: couponCode })
            })
            .then(response => response.json())
            .then(data => {
                let messageBox = document.querySelector('#coupon-message');
                console.log(data);
                if (data.success) {
                    Swal.fire({
                        title: "Coupon Applied!",
                        icon: "success",
                    });
                    // Just update the totals
                    document.querySelector('#discount').innerText = `$${data.discount}`;
                    document.querySelector('#payable_total').innerHTML = `<b>$${data.new_total}</b>`;
                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong with the coupon!"
                    });
                    messageBox.innerHTML = `<span class="text-danger">${data.message}</span>`;
                }
            });
        });
        </script>


    <script src="adminassets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

</html>
