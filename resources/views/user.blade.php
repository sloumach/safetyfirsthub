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
        <div class="row my-4 d-flex justify-content-center">
            <ul class="nav nav-tabs border-0" role="tablist">
                <!-- Onglet Profile -->
                <li class="nav-item">
                    <a id="tab4-tab" class="nav-link active" data-bs-toggle="tab" href="#tab4" role="tab"
                        aria-controls="tab4" aria-selected="true">
                        <span class="align-middle">{{ __('Profile') }}</span>
                        <span id="comparaisonsnbr" class="badge bg-red ms-2"></span>
                    </a>
                </li>
                <!-- Onglet Payment History -->
                <li class="nav-item">
                    <a id="tab3-tab" class="nav-link" data-bs-toggle="tab" href="#tab3" role="tab"
                        aria-controls="tab3" aria-selected="false">
                        <span class="align-middle">{{ __('Payment history') }}</span>
                        <span id="prepositionsnbr" class="badge bg-red ms-2"></span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Conteneur des contenus des tabs -->
        <div class="tab-content">
            <!-- Contenu de l'onglet Profile -->
            <div class="tab-pane fade show active" id="tab4" role="tabpanel" aria-labelledby="tab4-tab">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="col-12">
                        <div class="contact-wrap contact-pages mb-0">
                            <div class="contact-form">
                                <div class="section-title">
                                    <h2>Edit Profile</h2>
                                </div>
                                <form id="profile-form">
                                    <div class="profile-section">
                                        <div class="avatar-section">
                                            <div class="avatar-preview">
                                                <img src="default-avatar.jpg" alt="Avatar" id="avatar-preview">
                                                <div class="avatar-overlay">
                                                    <span>Update Image</span>
                                                </div>
                                            </div>
                                            <input type="file" id="avatar-upload" name="avatar" accept="image/*">
                                        </div>
                                        <div class="form-section">
                                            <div class="form-group">
                                                <label for="first-name">First Name *</label>
                                                <input type="text" id="first-name" name="firstname" required
                                                    value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="last-name">Last Name *</label>
                                                <input type="text" id="last-name" name="lastname" required
                                                    value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="country">Country *</label>
                                                <select id="country" name="country" required>
                                                    <option value="United Kingdom" selected>United Kingdom</option>
                                                    <option value="Spain">Spain</option>
                                                    <option value="USA">USA</option>
                                                    <option value="Canada">Canada</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="street-address">Street Address *</label>
                                                <input type="text" id="street-address" name="streetaddress"
                                                    required value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="city">Town / City *</label>
                                                <input type="text" id="city" name="city" required
                                                    value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="state">State *</label>
                                                <input type="text" id="state" name="state" required
                                                    value="">
                                            </div>
                                            <div class="form-group">
                                                <label for="zip">Zip *</label>
                                                <input type="text" id="zip" name="zip" required
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="action-buttons">
                                            <button type="button" id="cancel-button">CANCEL</button>
                                            <button type="submit" id="update-button">UPDATE</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu de l'onglet Payment History -->
            <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3-tab">
                <div class="container py-3">
                    <h3 class="text-center mb-3">Payment History</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover text-center align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Course Name</th> <!-- Remplace Transaction ID -->
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $index => $payment)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($payment->orders->count() > 0)
                                                @foreach($payment->orders as $order)
                                                    <strong>{{ $order->course ? $order->course->name : 'No Course Found' }}</strong><br>
                                                @endforeach
                                            @else
                                                <span class="text-danger">No Orders Found</span>
                                            @endif
                                        </td>


                                        <td>${{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ getStatusColor($payment->status) }}">{{ ucfirst($payment->status) }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($payment->date)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No payment history found.</td>
                                    </tr>
                                @endforelse
                            </tbody>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- End Contact Area -->

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
