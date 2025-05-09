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
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <!-- Title -->
        <link rel="stylesheet" href="{{ asset('assets/css/single-product.css') }}">
    <title>Safety FirstHUB</title>
</head>

<body>

    <!-- Start Navbar Area -->
    @include('navbar')
    <!-- End Navbar Area -->

    <!-- Start Page Title Area -->
    <div class="page-title-area bg-23">
        <div class="container">
            <div class="page-title-content">
                <h1>Single product</h1>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
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
                        <h3 style="color: #FF8A00 !important;">{{ $product->name }}</h3>

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

                        <div class="product-description">
                            <h4>Description</h4>
                            <div class="description-content" id="productDescription">{{ $product->description }}</div>
                            <span class="read-more-btn" onclick="toggleProductDescription()"></span>
                        </div>

                        <ul class="product-summery">
                            {{-- <li>SUK <span>:132</span></li> --}}
                            <li style="color: black !important;">Category <span style="color: #FF8A00 !important;">{{ $product->category }}</span></li>
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
                            <button type="button" class="default-btn add-to-cart mt-5" 
                                data-id="{{ $product->id }}"
                                data-url="{{ route('add.to.cart') }}">
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

              
            </div>
        </div>
    </section>
    <!-- End Product Details Area -->

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

</body>
    @include('layouts.scripts')
    <!-- Page Specific Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/single-product.js') }}"></script>
    <script>
    function toggleProductDescription() {
        const description = document.getElementById('productDescription');
        const btn = document.querySelector('.read-more-btn');
        
        description.classList.toggle('expanded');
        btn.classList.toggle('expanded');
    }
    </script>
</html>
