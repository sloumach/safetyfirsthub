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
        </style>
		<!-- Title -->
		<title>Eduon - Online Courses & Training HTML Template</title>
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
        <div class="container">
            <div class="success-message">
                <i class="checkmark">&#10004;</i>
                <h1>Payment Successful!</h1>
<p>Thank you for your purchase. Your payment has been processed successfully.</p>
<p>You can now access your user account or return to the homepage.</p>

<div class="buttons">
    <a href="{{ route('dashboard') }}" class="default-btn">Access My Dashboard</a>
</div>
            </div>
        </div>



		<!-- Start Payment Area -->
<section class="main-contact-area pb-100 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="contact-wrap contact-pages mb-0">
                    <div class="contact-form">
                        <div class="section-title">
                            <h2>Payment Information</h2>
                        </div>
                        <form id="payment-form">
                            <div class="payment-section">
                                <div class="form-section">
                                    <!-- Cardholder Name -->
                                    <div class="form-group">
                                        <label for="cardholder-name">Cardholder Name</label>
                                        <input type="text" id="cardholder-name" name="cardholderName" required>
                                    </div>

                                    <!-- Card Number -->
                                    <div class="form-group">
                                        <label for="card-number">Card Number</label>
                                        <input type="text" id="card-number" name="cardNumber" required maxlength="16">
                                    </div>

                                    <!-- Expiration Date -->
                                    <div class="form-group">
                                        <label for="expiration-date">Expiration Date</label>
                                        <input type="text" id="expiration-date" name="expirationDate" placeholder="MM/YY" required>
                                    </div>

                                    <!-- CVV -->
                                    <div class="form-group">
                                        <label for="cvv">CVV</label>
                                        <input type="text" id="cvv" name="cvv" required maxlength="3">
                                    </div>

                                    <!-- Billing Address -->
                                    <div class="form-group">
                                        <label for="billing-address">Billing Address</label>
                                        <input type="text" id="billing-address" name="billingAddress" required>
                                    </div>

                                    <!-- City -->
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" id="city" name="city" required>
                                    </div>

                                    <!-- Postal Code -->
                                    <div class="form-group">
                                        <label for="postal-code">Postal Code</label>
                                        <input type="text" id="postal-code" name="postalCode" required>
                                    </div>

                                    <!-- Country -->
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select id="country" name="country" required>
                                            <option value="Spain">Spain</option>
                                            <option value="USA">USA</option>
                                            <option value="Canada">Canada</option>
                                            <!-- Add more countries as needed -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button type="button" id="cancel-button">CANCEL</button>
                                <button type="submit" id="pay-button">PAY NOW</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Payment Area -->



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
		<script src="assets/js/user.js"></script>

    </body>
</html>
