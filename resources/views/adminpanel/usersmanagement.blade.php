<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="adminassets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="adminassets/css/sb-admin-2.min.css" rel="stylesheet">

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
                <!-- Begin Page Content -->
                <div class="container-fluid">


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>email</th>
                                            <th>status</th>
                                            <th>Account created at:</th>
                                            <th>actions</th>

                                        </tr>
                                    </thead>
                                    {{-- <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>email</th>
                                            <th>status</th>
                                            <th>Start course</th>
                                            <th>courses</th>
                                        </tr>
                                    </tfoot> --}}
                                    <tbody>
                                        @foreach($users as $index => $user)
                                            <tr>
                                                <td>{{ $user->firstname }}, {{ $user->lastname }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    {{ $user->status }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}

                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning toggle-status" data-id="{{ $user->id }}">
                                                        {{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}
                                                    </button>
                                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#userDetailModal" data-id="{{ $user->id }}">
                                                        View
                                                    </button>

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
                        <span>Copyright &copy; Your Website 2021</span>
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
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
    </div>

    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="user-detail-content">
                <!-- contenu chargé dynamiquement en JS -->
            </div>
        </div>
    </div>


</body>
<!-- Bootstrap core JavaScript -->
<script src="{{ asset('adminassets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript -->
<script src="{{ asset('adminassets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages -->
<script src="{{ asset('adminassets/js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('adminassets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminassets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('adminassets/js/demo/datatables-demo.js') }}"></script>
<script>
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(res => res.json())
              .then(data => location.reload());
        });
    });
</script>{{-- w9eft hne el modal il affiche les details de l'utilisateur --}}
<script>
    document.querySelectorAll('[data-target="#userDetailModal"]').forEach(button => {
        button.addEventListener('click', function () {
            console.log('clicked');
            const userId = this.getAttribute('data-id');
            fetch(`/admin/users/${userId}/details`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('user-detail-content').innerHTML = html;
                });
        });
    });
</script>



</html>
