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
    <link rel="stylesheet" href="assets/css/user.css">

    <!-- Dark CSS -->
    <link rel="stylesheet" href="assets/css/dark.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/success.css">
    <title>Safety FirstHUB</title>
</head>

<body>

    <!-- Start Navbar Area -->
    @include('navbar')
    <!-- End Navbar Area -->
    <!-- Start Preloader Area -->
   
    <!-- End Preloader Area -->
    <div class="container">
        <!-- Add animated background elements -->
        <div class="animated-background">
            <!-- Generate multiple floating circles and X marks -->
            @for ($i = 1; $i <= 15; $i++)
                <div class="floating-circle failed" style="
                    width: {{ rand(20, 100) }}px;
                    height: {{ rand(20, 100) }}px;
                    left: {{ rand(0, 100) }}%;
                    top: {{ rand(0, 100) }}%;
                    animation-delay: -{{ rand(0, 8000) }}ms;
                "></div>
                
                <div class="floating-check failed" style="
                    left: {{ rand(0, 100) }}%;
                    top: {{ rand(0, 100) }}%;
                    animation-delay: -{{ rand(0, 10000) }}ms;
                ">✕</div>
            @endfor
        </div>

        <div class="success-message failed">
            <i class="checkmark failed">✕</i>
            <h1>Payment Failed!</h1>
            <p>Unfortunately, your payment could not be processed.</p>
            <p>Please check your payment details and try again, or contact support for assistance.</p>

            <div class="buttons">
                <a href="{{ route('courses') }}" class="default-btn failed">Try Again</a>
            </div>
        </div>
    </div>






    <!-- Start Footer Top Area -->

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
    <script src="assets/js/user.js"></script>
    <script src="assets/js/navbar.js"></script>
</body>

</html>
