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

    <!-- Odometer Min CSS-->
    <link rel="stylesheet" href="assets/css/odometer.min.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/user.css">
    <link rel="stylesheet" href="assets/css/user-profile.css">

    <!-- Dark CSS -->
    <link rel="stylesheet" href="assets/css/dark.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="adminassets/vendor/datatables/dataTables.bootstrap4.min.css">
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

    <div class="ud-container">
        <!-- Tabs -->
        <div class="ud-tabs">
            <button class="ud-tab active" onclick="switchTab('profile')">
                Profile
            </button>
            <button class="ud-tab" onclick="switchTab('payment')">
                Payment history
            </button>
        </div>

        <!-- Tab Content -->
        <div id="profile-tab" class="ud-tab-content active">
            <form id="profile-form" method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="ud-form-grid">
                    <div class="ud-form-group">
                        <label class="ud-label">First Name</label>
                        <input type="text" class="ud-input" name="firstname" value="{{ $user->firstname }}" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Last Name</label>
                        <input type="text" class="ud-input" name="lastname" value="{{ $user->lastname }}" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Country</label>
                        <div class="ud-select-wrapper">
                            <select class="ud-select" name="country" required>
                                <option value="" disabled {{ !$user->country ? 'selected' : '' }}>Select a country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ $user->country == $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>


                            <div class="ud-select-arrow"></div>
                        </div>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Street Address</label>
                        <input type="text" class="ud-input" name="address" value="{{ $user->adress }}" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Town / City</label>
                        <input type="text" class="ud-input" name="city" value="{{ $user->city }}" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">State</label>
                        <input type="text" class="ud-input" name="state" value="{{ $user->state }}" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Zip</label>
                        <input type="text" class="ud-input" name="zip" value="{{ $user->zipcode }}" required>
                    </div>
                </div>

                <div class="ud-button-group">
                    <button type="button" class="ud-btn ud-btn-secondary">Cancel</button>
                    <button type="submit" class="ud-btn ud-btn-primary">Update</button>
                </div>
            </form>
        </div>

        <div id="payment-tab" class="ud-tab-content" style="display: none;">
            <div class="ud-table-container">
                <table class="ud-table" id="paymentTable">
                    <thead>
                        <tr>

                            <th>Course Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $index => $payment)
                            <tr>
                                <td data-label="Course Name">
                                    @foreach($payment->orders as $order)
                                        <span>{{ $order->course->name ?? 'No Course Found' }}</span>
                                    @endforeach
                                </td>
                                <td data-label="Amount">${{ number_format($payment->amount, 2) }}</td>
                                <td data-label="Status">
                                    <span class="ud-status-badge {{ strtolower($payment->status) === 'completed' ? 'ud-status-success' : (strtolower($payment->status) === 'failed' ? 'ud-status-failed' : 'ud-status-pending') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td data-label="Payment Date">{{ $payment->date->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No payment history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- End Contact Area -->
    <div class="switch-box">
    <label id="switch" class="switch">
        <input type="checkbox" onchange="toggleTheme()" id="slider">
        <span class="slider round"></span>
    </label>
    </div>
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
    <script src="adminassets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="adminassets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/user.js"></script>
</html>
