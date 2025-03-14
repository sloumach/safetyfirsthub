<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Quiz Management</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/coupon-form.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="{{ asset('adminassets/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
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
                        <h1 class="h3 mb-0 text-gray-800">Quiz Management</h1>
                        <a href="{{ route('admin.quizzes.create') }}" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="text">Add New Quiz</span>
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

                    <!-- Quiz List Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Quiz List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="quizTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Section</th>
                                            <th>Passing Score</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quizzes as $quiz)
                                            <tr>
                                                <td>{{ $quiz->id }}</td>
                                                <td>{{ $quiz->section->title }}</td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $quiz->passing_score }}%
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.quizzes.show', $quiz->id) }}" 
                                                       class="btn btn-info btn-sm btn-icon-split">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-eye"></i>
                                                        </span>
                                                        <span class="text">View</span>
                                                    </a>
                                                    <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" 
                                                       class="btn btn-warning btn-sm btn-icon-split">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                        <span class="text">Edit</span>
                                                    </a>
                                                    <a href="{{ route('admin.questions.index', $quiz->id) }}" 
                                                       class="btn btn-primary btn-sm btn-icon-split">
                                                        <span class="icon text-white-50">
                                                            <i class="fas fa-question"></i>
                                                        </span>
                                                        <span class="text">Questions</span>
                                                    </a>
                                                    <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf 
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm btn-icon-split">
                                                            <span class="icon text-white-50">
                                                                <i class="fas fa-trash"></i>
                                                            </span>
                                                            <span class="text">Delete</span>
                                                        </button>
                                                    </form>
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#quizTable').DataTable({
                "pageLength": 10,
                "language": {
                    "search": "Search quizzes:",
                    "lengthMenu": "Show _MENU_ quizzes per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ quizzes"
                }
            });

            // SweetAlert2 Delete Confirmation
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.method.toLowerCase() === 'post' && this.querySelector('button[type="submit"].btn-danger')) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Delete Quiz?',
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
        });
    </script>
</body>
</html>
