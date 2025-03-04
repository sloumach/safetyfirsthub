<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin-safetyfirstHUB</title>

    <!-- Custom fonts for this template-->
    <link href="adminassets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="adminassets/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="adminassets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
                    <h1 class="h3 mb-4 text-gray-800">Courses List</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Manage Courses</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="coursesTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Category</th>
                                            <th>Total Videos</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courses as $course)
                                            <tr>
                                                <td>{{ $course->name }}</td>
                                                <td>${{ $course->price }}</td>
                                                <td>{{ $course->category }}</td>
                                                <td>{{ $course->total_videos }}</td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm edit-btn"
                                                        data-id="{{ $course->id }}" 
                                                        data-name="{{ $course->name }}"
                                                        data-price="{{ $course->price }}"
                                                        data-category="{{ $course->category }}"
                                                        data-total_videos="{{ $course->total_videos }}"
                                                        data-short_description="{{ $course->short_description }}"
                                                        data-description="{{ $course->description }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    
                                                    <form action="{{ route('delete.course', $course->id) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this course?')">
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

                    <!-- Edit Course Modal -->
                    <div class="modal fade" id="editCourseModal" tabindex="-1" aria-labelledby="editCourseModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editCourseModalLabel">Edit Course</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="editCourseForm" action="" method="POST">
                                        @csrf
                                        <input type="hidden" id="edit-course-id" name="course_id">

                                        <div class="form-group">
                                            <label for="edit-name">Course Name</label>
                                            <input type="text" class="form-control" id="edit-name" name="name" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="edit-price">Price</label>
                                            <input type="number" step="0.01" class="form-control" id="edit-price" name="price" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="edit-category">Category</label>
                                            <input type="text" class="form-control" id="edit-category" name="category" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="edit-total-videos">Total Videos</label>
                                            <input type="number" class="form-control" id="edit-total-videos" name="total_videos" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="edit-short-description">Short Description</label>
                                            <textarea class="form-control" id="edit-short-description" name="short_description" rows="2" required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="edit-description">Full Description</label>
                                            <textarea class="form-control" id="edit-description" name="description" rows="3" required></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success">Update Course</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="adminassets/vendor/jquery/jquery.min.js"></script>
    <script src="adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="adminassets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="adminassets/js/sb-admin-2.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="adminassets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="adminassets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#coursesTable').DataTable();

            // Edit button click handler
            $('.edit-btn').click(function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let price = $(this).data('price');
                let category = $(this).data('category');
                let totalVideos = $(this).data('total_videos');
                let shortDescription = $(this).data('short_description');
                let description = $(this).data('description');

                $('#edit-course-id').val(id);
                $('#edit-name').val(name);
                $('#edit-price').val(price);
                $('#edit-category').val(category);
                $('#edit-total-videos').val(totalVideos);
                $('#edit-short-description').val(shortDescription);
                $('#edit-description').val(description);

                $('#editCourseForm').attr('action', '/admin/course/update/' + id);
                $('#editCourseModal').modal('show');
            });
        });
    </script>
</body>
</html> 