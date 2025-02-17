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
        @vite(['resources/js/app.js'])
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
		<!-- Title -->
		<title>Eduon - Online Courses & Training HTML Template</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
		
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
		<section id="app" class="courses-area-style custom-padding">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <courses></courses>
                        <exams></exams>
                        <certificates></certificates>
                    </div>
                </div>
            </div>
        </div>
    </section>

		<!-- End Popular Courses Area -->

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
		<!-- jarallax Min JS -->
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
    </body>
</html>
