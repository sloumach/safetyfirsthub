<!DOCTYPE html>
<html lang="en">
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
        <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
        <!-- Title -->
        <title>Reset Password - Safety FirstHUB</title>
    </head>
    <body>
        <!-- Start Navbar Area -->
        @include('navbar')
        <!-- End Navbar Area -->

        <!-- Start Page Title Area -->
        <div class="page-title-area bg-10">
            <div class="container">
                <div class="page-title-content">
                    <h2>Reset Password</h2>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li class="active">Reset Password</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Page Title Area -->

        <!-- Start Reset Password Area -->
        <section class="user-area-style recover-password-area ptb-100">
            <div class="container">
                <div class="contact-form-action recover">
                    <div class="section-title">
                        <h2>Reset Password</h2>
                        <p>Please enter your new password below</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email', $request->email) }}" 
                                           readonly
                                           placeholder="Email Address">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" 
                                           type="password" 
                                           name="password" 
                                           required 
                                           placeholder="New Password">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" 
                                           type="password" 
                                           name="password_confirmation" 
                                           required 
                                           placeholder="Confirm Password">
                                </div>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <a class="now-log-in font-q" href="{{ route('login') }}">Back to login</a>
                            </div>
                            
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <p class="now-register">
                                    Not a member?
                                    <a class="font-q" href="{{ route('register') }}">Register now</a>
                                </p>
                            </div>

                            <div class="col-12">
                                <button class="default-btn btn-two" type="submit">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- End Reset Password Area -->

        <!-- Start Footer Area -->
        @include('footer')
        <!-- End Footer Area -->
        
        <!-- Start Go Top Area -->
        <div class="go-top">
            <i class='bx bx-chevrons-up'></i>
            <i class='bx bx-chevrons-up'></i>
        </div>
        <!-- End Go Top Area -->

        <!-- JavaScript Resources -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/meanmenu.min.js') }}"></script>
        <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/js/wow.min.js') }}"></script>
        <script src="{{ asset('assets/js/nice-select.min.js') }}"></script>
        <script src="{{ asset('assets/js/magnific-popup.min.js') }}"></script>
        <script src="{{ asset('assets/js/jarallax.min.js') }}"></script>
        <script src="{{ asset('assets/js/appear.min.js') }}"></script>
        <script src="{{ asset('assets/js/odometer.min.js') }}"></script>
        <script src="{{ asset('assets/js/form-validator.min.js') }}"></script>
        <script src="{{ asset('assets/js/contact-form-script.js') }}"></script>
        <script src="{{ asset('assets/js/ajaxchimp.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
    </body>
</html>
