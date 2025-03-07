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
    <link rel="stylesheet" href="assets/css/navbar.css">
    <!-- Dark CSS -->
    <link rel="stylesheet" href="assets/css/dark.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- Contact CSS -->
    <link rel="stylesheet" href="assets/css/contact.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <!-- Favicon -->
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
    <div class="page-title-area bg-5">
        <div class="container">
            <div class="page-title-content">
                <h2 style="color:rgb(0, 0, 0) !important;">Contact</h2>
                <ul>
                    <li>
                        <a href="{{ route('home') }}">
                            Home
                        </a>
                    </li>

                    <li class="active" style="color:rgb(0, 0, 0) !important;">Contact</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Page Title Area -->

    <!-- Start Contact Info Area -->
    <!-- <section class="contact-info-area pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-6">
                    <div class="single-contact-info">
                        <i class="flaticon-call"></i>
                        <h3>Call us</h3>
                        <a href="tel:+1(514)312-5678">Phone :+1 (514) 312-5678</a>
                        <a href="tel:+1(514)312-6688">Tel :+1 (514) 312-6688</a>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6">
                    <div class="single-contact-info">
                        <i class="flaticon-pin"></i>
                        <h3>Our location</h3>
                        <a href="#">6890 Blvd, The Bronx, NY 1058 New York, USA</a>
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 offset-sm-3 offset-lg-0">
                    <div class="single-contact-info">
                        <i class="flaticon-email"></i>
                        <h3>Email</h3>
                      
                        <a href="mailto:Support@safetyfirsthub.com">Support@safetyfirsthub.com</a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- End Contact Info Area -->

    <!-- Start Contact Area -->
    <section class="main-contact-area pb-100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="contact-wrap contact-pages mb-0">
                        <div class="contact-form">
                            <div class="section-title">
                                <h1>Drop us a message for any query</h1>
                                <p>For more information about our courses, get in touch <br> with eduon contacts</p>

                            </div>



                            <form  method="POST" action="{{ route('contact.submit') }}">
                                @csrf <!-- Protection CSRF -->

                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <input type="text" name="subject" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea name="message" class="form-control" cols="30" rows="5" required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <button type="submit" class="default-btn btn-two">Send Message</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Message de succès -->
                            @if (session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Contact Area -->

    <!-- Start Map Area -->
    <!-- <div class="map-area">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.119763973046!3d40.69766374874431!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1595487039539!5m2!1sen!2sbd"></iframe>
    </div> -->
    <!-- End Map Area -->

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
</html>
