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
    <link rel="stylesheet" href="assets/css/user.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <!-- Dark CSS -->
    <link rel="stylesheet" href="assets/css/dark.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/success.css">
    <!-- Title -->
    <title>Safety FirstHUB</title>
</head>

<body>

    <!-- Start Navbar Area -->
    @include('navbar')
    <!-- End Navbar Area -->

    <div class="container success">
        <!-- Add animated background elements -->
        <div class="animated-background" id="animatedBackgroundSuccess">
            <!-- Elements will be added by JavaScript -->
        </div>

        <div class="success-message">
            <i class="checkmark">✓</i>
            <h1>Payment Successful!</h1>
            <p>Thank you for your purchase. Your payment has been processed successfully.</p>
            <p>You can now access your user account or return to the homepage.</p>

            <div class="buttons">
                <a href="{{ route('dashboard') }}" class="default-btn">Access My Dashboard</a>
            </div>
        </div>
    </div>

    <!-- Start Go Top Area -->
    <div class="go-top">
        <i class='bx bx-chevrons-up'></i>
        <i class='bx bx-chevrons-up'></i>
    </div>
    <!-- End Go Top Area -->
</body>
    @include('layouts.scripts')
    <!-- Page Specific Scripts -->
    <script src="assets/js/user.js"></script>
</html>
