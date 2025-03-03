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
            <form id="profile-form">
                <div class="ud-form-grid">
                    <div class="ud-form-group">
                        <label class="ud-label">First Name</label>
                        <input type="text" class="ud-input" name="firstname" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Last Name</label>
                        <input type="text" class="ud-input" name="lastname" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Country</label>
                        <div class="ud-select-wrapper">
                            <select class="ud-select" name="country" required>
                                <option value="" disabled selected>Select a country</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="Spain">Spain</option>
                                <option value="USA">USA</option>
                                <option value="Canada">Canada</option>
                            </select>
                            <div class="ud-select-arrow"></div>
                        </div>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Street Address</label>
                        <input type="text" class="ud-input" name="address" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Town / City</label>
                        <input type="text" class="ud-input" name="city" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">State</label>
                        <input type="text" class="ud-input" name="state" required>
                    </div>
                    <div class="ud-form-group">
                        <label class="ud-label">Zip</label>
                        <input type="text" class="ud-input" name="zip" required>
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

    <script>
    function switchTab(tab) {
        // Hide all tab contents
        document.querySelectorAll('.ud-tab-content').forEach(content => {
            content.style.display = 'none';
        });
        
        // Remove active class from all tabs
        document.querySelectorAll('.ud-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Show selected tab content and activate tab
        if (tab === 'profile') {
            document.getElementById('profile-tab').style.display = 'block';
            document.querySelector('.ud-tab:first-child').classList.add('active');
        } else {
            document.getElementById('payment-tab').style.display = 'block';
            document.querySelector('.ud-tab:last-child').classList.add('active');
        }
    }
    </script>

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
    <script src="assets/js/navbar.js"></script>

    <!-- DataTables JS -->
    <script src="adminassets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="adminassets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#paymentTable').DataTable({
                pageLength: 4,
                lengthMenu: [4, 8, 12],
                language: {
                    lengthMenu: "Show _MENU_ items per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ items",
                    infoEmpty: "No items available",
                    search: "Search:",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
</body>

</html>
