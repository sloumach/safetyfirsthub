<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Coupons Management</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/course-form.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="{{ asset('adminassets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
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
                        <h1 class="h3 mb-4 text-gray-800">Coupons Management</h1>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Create New Coupon</span>
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Coupons List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="couponsTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Expiration</th>
                                            <th>Usage</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <td>{{ $coupon->code }}</td>
                                                <td>{{ $coupon->discount_type }}</td>
                                                <td>
                                                    {{ $coupon->discount_type === 'fixed' ? '$' . $coupon->discount_value : $coupon->discount_value . '%' }}
                                                </td>
                                                <td>{{ $coupon->expiration_date }}</td>
                                                <td>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $coupon->is_active ? 'success' : 'danger' }}">
                                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" style="display:inline-block;">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
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
        <script src="adminassets/js/sb-admin-2.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ asset('adminassets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminassets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Chart.js Graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('adminassets/js/finances.js') }}"></script>

    <!-- Initialize DataTable -->
    <script>
        $(document).ready(function() {
            $('#couponsTable').DataTable({
                "order": [[3, "desc"]], // Sort by expiration date by default
                "pageLength": 10,  // Show 10 entries per page
                "language": {
                    "search": "Search Coupons:",
                    "lengthMenu": "Show _MENU_ coupons per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ coupons"
                }
            });
        });
    </script>

    <!-- Add before closing body tag -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (this.method.toLowerCase() === 'post' && this.querySelector('button[type="submit"].btn-danger')) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Delete Coupon?',
                        text: 'This action cannot be undone',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e74a3b',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            popup: 'small-dialog'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
