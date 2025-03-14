<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Coupon Details</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/coupon-form.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="{{ asset('adminassets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        @include('adminpanel.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                @include('adminpanel.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Coupon Details</h1>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="text">Back to List</span>
                        </a>
                    </div>

                    <!-- Coupon Info Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-ticket-alt mr-2"></i>
                                Coupon Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <label class="text-primary font-weight-bold">Code</label>
                                        <p class="mb-0 h5">{{ $coupon->code }}</p>
                                    </div>
                                    <div class="info-group mb-4">
                                        <label class="text-primary font-weight-bold">Type</label>
                                        <p class="mb-0">{{ $coupon->discount_type === 'fixed' ? 'Fixed Amount' : 'Percentage' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group mb-4">
                                        <label class="text-primary font-weight-bold">Value</label>
                                        <p class="mb-0 h5">{{ $coupon->discount_type === 'fixed' ? '$' . $coupon->discount_value : $coupon->discount_value . '%' }}</p>
                                    </div>
                                    <div class="info-group mb-4">
                                        <label class="text-primary font-weight-bold">Usage</label>
                                        <p class="mb-0">
                                            <span class="badge badge-info">
                                                <i class="fas fa-users mr-1"></i>
                                                {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usage History Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-history mr-2"></i>
                                Usage History
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="usageTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Times Used</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($coupon->users as $user)
                                            <tr>
                                                <td>
                                                    <i class="fas fa-user mr-2 text-gray-500"></i>
                                                    {{ $user->name }}
                                                </td>
                                                <td>
                                                    <i class="fas fa-envelope mr-2 text-gray-500"></i>
                                                    {{ $user->email }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        {{ $user->pivot->times_used }} times
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2023</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('adminassets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('adminassets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('adminassets/js/sb-admin-2.min.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ asset('adminassets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminassets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#usageTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Search users:",
                    "lengthMenu": "Show _MENU_ users per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ users"
                }
            });
        });
    </script>
</body>
</html>
