<!DOCTYPE html>
<html lang="zxx">
    <head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Safety FirstHUB">
		<meta name="keywords" content="Safety FirstHUB">
		<meta name="csrf-token" content="{{ csrf_token() }}">
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
		<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
        @vite(['resources/js/app.js'])
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
		<!-- Title -->
		<title>Safety FirstHUB</title>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
		<link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">
    </head>
    <body>

        <!-- Start Navbar Area -->
        @include('navbar')
        <!-- End Navbar Area -->
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
</html>


