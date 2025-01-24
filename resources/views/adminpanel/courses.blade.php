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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Courses managements</h1>
                    <div class="row">

                        <div class="col-lg-6">

                            <!-- Default Card Example -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    Add new course:
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('addcourse') }}" method="POST" enctype="multipart/form-data">
                                        <!-- Ajout du token CSRF pour la sécurité (obligatoire dans Laravel) -->
                                        @csrf

                                        <!-- Champ pour le prix -->
                                        <div class="form-group">
                                          <label for="price">Price</label>
                                          <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Enter course price" required>
                                        </div>

                                        <!-- Champ pour la catégorie -->
                                        <div class="form-group">
                                          <label for="category">Category</label>
                                          <input type="text" class="form-control" id="category" name="category" placeholder="Enter course category" required>
                                        </div>

                                        <!-- Champ pour le nombre total de vidéos -->
                                        <div class="form-group">
                                          <label for="total_videos">Total Videos</label>
                                          <input type="number" class="form-control" id="total_videos" name="total_videos" placeholder="Enter total number of videos" required>
                                        </div>

                                        <!-- Champ pour la description -->
                                        <div class="form-group">
                                          <label for="description">Description</label>
                                          <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter course description" required></textarea>
                                        </div>
                                        <!-- Champ pour l'image de couverture -->
                                        <div class="form-group">
                                            <label for="cover">Upload Course Cover</label>
                                            <input type="file" class="form-control-file" id="cover" name="cover" accept="image/*" required>
                                          </div>

                                        <!-- Champ pour l'URL -->
                                        {{-- <div class="form-group">
                                            <label for="file">Upload Course Video</label>
                                            <input type="file" class="form-control-file" id="file" name="file" accept="video/*" required>
                                          </div> --}}

                                        <!-- Bouton de soumission -->
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                      </form>

                                </div>
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
    <script src="adminassets/vendor/jquery/jquery.min.js"></script>
    <script src="adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="adminassets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="adminassets/js/sb-admin-2.min.js"></script>

</body>

</html>
