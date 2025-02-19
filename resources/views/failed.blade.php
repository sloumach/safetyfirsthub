



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

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <style>
        /* Styles généraux */
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .success-message {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .checkmark {
            color: #28a745;
            font-size: 50px;
            font-weight: bold;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-top: 10px;
        }

        p {
            color: #666;
            font-size: 16px;
            margin: 10px 0;
        }

        .buttons {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .success-message {
                padding: 20px;
            }

            h1 {
                font-size: 20px;
            }

            p {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }
        }
    </style> <!-- // ?? -->
    <!-- Title -->
    <title>Safety FirstHUB</title>
</head>

<body>

    <!-- Start Navbar Area -->
    @include('navbar')
    <!-- End Navbar Area -->
    <!-- Start Preloader Area -->
   
    <!-- End Preloader Area -->
    <div class="container">
        <div class="success-message">
            <i class="crossmark">&#10008;</i>
            <h1>Payment Failed!</h1>
            <p>Unfortunately, your payment could not be processed.</p>
            <p>Please check your payment details and try again, or contact support for assistance.</p>

            <div class="buttons">
                <a href="{{ route('courses') }}" class="default-btn">Try Again</a>
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

</body>

</html>
