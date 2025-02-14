<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Blank</title>

    <!-- Custom fonts for this template-->
    <!-- CSS principal -->
<link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">

<!-- Font Awesome -->
<link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->


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
                <div class="container mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-4">Exams Management</h2>
                        <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Exam
                        </a>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if ($exams->isEmpty())
                                <div class="alert alert-warning">No exams available.</div>
                            @endif
                            <div class="table-responsive">
                                <table id="examsTable" class="table table-striped table-bordered text-center">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Course</th>
                                            <th>Questions</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($exams as $index => $exam)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $exam->title }}</td>
                                                <td>{{ $exam->course->name }}</td>
                                                <td>{{ $exam->questions->count() }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $exam->is_active ? 'success' : 'danger' }}">
                                                        {{ $exam->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.exams.edit', $exam->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="{{ route('admin.exams.questions', $exam->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-list"></i> Questions
                                                    </a>

                                                    <form action="{{ route('admin.exams.toggle', $exam->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-{{ $exam->is_active ? 'danger' : 'success' }} btn-sm">
                                                            <i class="fas fa-toggle-{{ $exam->is_active ? 'on' : 'off' }}"></i>
                                                            {{ $exam->is_active ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('admin.exams.delete', $exam->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i> Delete
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
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    {{-- <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Bootstrap core JavaScript-->
    <!-- jQuery -->
<script src="{{ asset('adminassets/vendor/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap Bundle -->
<script src="{{ asset('adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript -->
<script src="{{ asset('adminassets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages -->
<script src="{{ asset('adminassets/js/sb-admin-2.min.js') }}"></script>




</body>

</html>
