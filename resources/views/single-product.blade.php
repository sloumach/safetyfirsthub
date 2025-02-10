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
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <!-- Title -->
    <title>Eduon - Online Courses & Training HTML Template</title>
</head>

<body>
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

    <!-- Start Navbar Area -->
    @include('navbar')
    <!-- End Navbar Area -->

    <!-- Start Page Title Area -->
    <div class="page-title-area bg-23">
        <div class="container">
            <div class="page-title-content">
                <h2>Single product</h2>
                <ul>
                    <li>
                        <a href="index.html">
                            Home
                        </a>
                    </li>

                    <li class="active">Single product</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Title Area -->

    <!-- Start Product Details Area -->
    <section class="product-details-area ptb-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="product-details-image">
                        <img src="{{ asset('storage/' . $product->cover) }}" alt="Image">
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="product-details-desc">
                        <h3>{{ $product->name }}</h3>

                        <div class="price">
                            <span>Price:</span>
                            <span class="new-price">${{ $product->price }}</span>
                        </div>

                        {{-- <div class="product-review">
                            <div class="rating">
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star'></i>
                                <i class='bx bxs-star-half'></i>
                            </div>
                            <a href="#" class="rating-count">(5 reviews)</a>
                        </div> --}}

                        <p>{{ $product->description }}</p>

                        <ul class="product-summery">
                            {{-- <li>SUK <span>:132</span></li> --}}
                            <li>Category <span>{{ $product->category }}</span></li>
                            {{-- <li>Tags <span>:Book</span></li> --}}
                            {{-- <li>10 in stock</li> --}}
                        </ul>

                        {{-- <ul class="social-wrap">
								<li>
									<span>Share:</span>
								</li>
								<li>
									<a href="#" target="_blank">
										<i class="bx bxl-twitter"></i>
									</a>
								</li>
								<li>
									<a href="#" target="_blank">
										<i class="bx bxl-instagram"></i>
									</a>
								</li>
								<li>
									<a href="#" target="_blank">
										<i class="bx bxl-facebook"></i>
									</a>
								</li>
							</ul>

							<div class="product-add-to-cart">
								<h3>Quantities:</h3>
								<div class="input-counter">
									<span class="minus-btn">
										<i class='bx bx-minus' ></i>
									</span>
									<input type="text" value="1">
									<span class="plus-btn">
										<i class='bx bx-plus' ></i>
									</span>
								</div>
						</div> --}}
                        @if (auth()->user() && auth()->user()->courses->contains($product->id))
                            <a href="{{ route('dashboard') }}" type="button" class="default-btn mt-5">
                                Owned
                                <i class="flaticon-right"></i>
                            </a>
                        @else
                            <button type="button" class="default-btn add-to-cart mt-5" data-id="{{ $product->id }}">
                                Add to Cart
                                <i class="flaticon-right"></i>
                            </button>
                        @endif
                        {{-- <button type="button" class="default-btn add-to-cart mt-5" data-id="{{ $product->id }}">
                                Add to Cart
                                <i class="flaticon-right"></i>
                            </button> --}}
                    </div>
                </div>

                <div class="col-lg-12 col-md-12">
                    <div class="tab products-details-tab">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <ul class="tabs">
                                    <li>
                                        <a href="#">
                                            Description
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="#">
                                            Reviews
                                        </a>
                                    </li> --}}
                                </ul>
                            </div>

                            <div class="col-lg-12 col-md-12">
                                <div class="tab_content">
                                    <div class="tabs_item">
                                        <div class="products-details-tab-content">
                                            <h3 class="mb-2">Description</h3>
                                            <p>{{ $product->description }}</p>
                                        </div>
                                    </div>

                                    {{-- <div class="tabs_item">
                                        <div class="products-details-tab-content">
                                            <div class="product-review-form">
                                                <h3>Customer reviews</h3>

                                                <div class="review-title">
                                                    <div class="rating">
                                                        <i class='bx bxs-star'></i>
                                                        <i class='bx bxs-star'></i>
                                                        <i class='bx bxs-star'></i>
                                                        <i class='bx bxs-star'></i>
                                                        <i class='bx bxs-star'></i>
                                                    </div>
                                                    <p>Based on 2 reviews</p>
                                                </div>

                                                <div class="review-comments">
                                                    <div class="review-item">
                                                        <div class="rating">
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                        </div>
                                                        <h3>Good</h3>
                                                        <span><strong>Admin</strong> on <strong>July 21,
                                                                2020</strong></span>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                                            do eiusmod tempor incididunt ut labore et dolore magna
                                                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                                                        </p>

                                                        <a href="#" class="review-report-link">Reply</a>
                                                    </div>

                                                    <div class="review-item">
                                                        <div class="rating">
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                            <i class='bx bxs-star'></i>
                                                        </div>
                                                        <h3>Good</h3>
                                                        <span><strong>Admin</strong> on <strong>July 21,
                                                                2020</strong></span>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                                                            do eiusmod tempor incididunt ut labore et dolore magna
                                                            aliqua. Ut enim ad minim veniam, quis nostrud exercitation.
                                                        </p>

                                                        <a href="#" class="review-report-link">Reply</a>
                                                    </div>
                                                </div>

                                                <div class="review-form">
                                                    <h3>Write a Review</h3>

                                                    <form>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>Name</label>
                                                                    <input type="text" id="name"
                                                                        name="name" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="form-group">
                                                                    <label>Email</label>
                                                                    <input type="email" id="email"
                                                                        name="email" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-12 col-md-12">
                                                                <div class="form-group">
                                                                    <label>Review title</label>
                                                                    <input type="text" id="review-title"
                                                                        name="review-title" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-12 col-md-12">
                                                                <div class="form-group">
                                                                    <label>Body of review (1500)</label>
                                                                    <textarea name="review-body" id="review-body" cols="30" rows="8" class="form-control"></textarea>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-12 col-md-12">
                                                                <button type="submit"
                                                                    class="btn default-btn two">Submit Review</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Product Details Area -->

    <!-- Start Related Product Area -->

    <!-- End Related Product Area -->

    <!-- Start Product View One Area -->
    {{-- <div class="modal fade product-view-one" id="product-view-one">
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
									<img src="{{ asset('assets/img/shop/shop-img-1.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-2.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-3.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-4.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-5.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-6.jpg') }}" alt="Image">
									</div>
								</div>

								<div id="thumbs" class="owl-carousel owl-theme">
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-1.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-2.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-3.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-4.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-5.jpg') }}" alt="Image">
									</div>
									<div class="item">
									<img src="{{ asset('assets/img/shop/shop-img-6.jpg') }}" alt="Image">
									</div>
								</div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="product-content">
                                <h3>
									<a href="#">transform: scaleY(1)</a>
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

                                <div class="product-color-switch">
									<h4>Color:</h4>

                                    <ul>
                                        <li>
											<a href="#" title="Black" class="color-black"></a>
										</li>
                                        <li>
											<a href="#" title="White" class="color-white"></a>
										</li>
                                        <li class="active">
											<a href="#" title="Green" class="color-green"></a>
										</li>
                                        <li>
											<a href="#" title="Yellow Green" class="color-yellowgreen"></a>
										</li>
                                        <li>
											<a href="#" title="Teal" class="color-teal"></a>
										</li>
                                    </ul>
								</div>

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
                                    @if (auth()->user() && auth()->user()->courses->contains($product->id))
                                    <a href="{{ route('dashboard') }}" type="button" class="default-btn " >
                                        Owned
                                        <i class="flaticon-right"></i>
                                    </a>
                                    @else
                                    <button type="button" class="default-btn " >
                                        Add to Cart
                                        <i class="flaticon-right"></i>
                                    </button>
                                    @endif



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
        </div> --}}
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        $(document).on('click', '.add-to-cart', function() {
            let courseId = $(this).data('id'); // Récupérer l'ID du cours
            let cartCountSpan = $('.cart-icon span'); // Sélectionne les compteurs du panier

            $.ajax({
                url: "{{ route('add.to.cart') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    course_id: courseId, // Renommé pour être plus clair
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            customClass: { confirmButton: "default-btn" },
                            title: 'Added to the cart.',
                            icon: 'success',
                            confirmButtonText: 'Cart',
                            showCloseButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = "/student/dashboard";
                            }
                        });

                        // Mettre à jour dynamiquement le compteur du panier
                        cartCountSpan.text(response.cart_count);
                    }
                },
                error: function(xhr) {
                    let response = xhr.responseJSON;
                    if (response && response.message) {
                        Swal.fire({
                            customClass: { confirmButton: "default-btn" },
                            title: response.message,
                            icon: 'warning',
                            confirmButtonText: response.message === "Product already in your cart!" ? 'Cart' : 'Dashboard',
                            showCloseButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = response.message === "Product already in your cart!" ? "/cart" : "/dashboard";
                            }
                        });
                    }
                }
            });
        });
    </script>


</body>

</html>
