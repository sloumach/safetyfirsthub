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
                    <h1 class="h3 mb-4 text-gray-800">Finances</h1>
                    <div class="row">
                        <div class="container">
                            <h2 class="text-center">Financial Summary</h2>

                            <!-- 🔹 DIV 1 : Graphique des ventes par cours et par mois -->
                            <div class="card mb-4">
                                <div class="card-header">Sales Per Course (Last 6 Months)</div>
                                <div class="card-body">
                                    <canvas id="salesChart"></canvas>
                                </div>
                            </div>

                            <!-- 🔹 DIV 2 : Liste des utilisateurs ayant acheté des cours -->
                            <div class="card">
                                <div class="card-header">Students Who Purchased Courses</div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Email</th>
                                                <th>Courses Purchased</th>
                                                <th>Total Orders</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                                <tr>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->email }}</td>
                                                    <td>
                                                        @foreach ($student->orders as $order)
                                                            <span
                                                                class="badge bg-success">{{ $order->course->name }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $student->orders->count() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- 🔹 DIV 2 : Graphique des revenus par cours -->

                            <div class="card mb-4">
                                <div class="card-header">Revenue Per Course (Last 6 Months)</div>
                                <div class="card-body">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->

            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>




</body>
    <!-- Bootstrap core JavaScript-->
    <script src="adminassets/vendor/jquery/jquery.min.js"></script>
    <script src="adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="adminassets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="adminassets/js/sb-admin-2.min.js"></script>
    <script>
        $(document).on('click', '.edit-btn', function() {
            let courseId = $(this).data('id');
            let name = $(this).data('name');
            let price = $(this).data('price');
            let category = $(this).data('category');
            let totalVideos = $(this).data('total_videos');
            let shortDescription = $(this).data('short_description');
            let description = $(this).data('description');
            // Remplir les champs du formulaire dans le modal
            $('#edit-course-id').val(courseId);
            $('#edit-name').val(name);
            $('#edit-price').val(price);
            $('#edit-category').val(category);
            $('#edit-total-videos').val(totalVideos);
            $('#edit-short-description').val(shortDescription);
            $('#edit-description').val(description);
            // Définir l'action du formulaire avec l'ID du cours
            $('#editCourseForm').attr('action', '/admin/course/update/' + courseId);
            // Ouvrir le modal
            $('#editCourseModal').modal('show');
        });
    </script>

    <!-- Chart.js Graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let salesCtx = document.getElementById('salesChart').getContext('2d');
            let revenueCtx = document.getElementById('revenueChart').getContext('2d');

            let salesData = @json($salesByCourse);
            let revenueData = @json($revenueByCourse);
            let labels = [];
            let salesDatasets = [];
            let revenueDatasets = [];

            Object.keys(salesData).forEach((course, index) => {
                let courseData = salesData[course].map(sale => sale.total_sales);
                let months = salesData[course].map(sale => sale.month);

                if (labels.length === 0) labels = months;

                salesDatasets.push({
                    label: course,
                    data: courseData,
                    borderColor: `hsl(${index * 60}, 70%, 50%)`,
                    backgroundColor: `hsl(${index * 60}, 70%, 70%)`,
                    fill: false,
                    tension: 0.3
                });

                revenueDatasets.push({
                    label: course,
                    data: revenueData[course].map(rev => rev.total_revenue),
                    borderColor: `hsl(${index * 60}, 70%, 50%)`,
                    backgroundColor: `hsl(${index * 60}, 70%, 70%)`,
                    fill: false,
                    tension: 0.3
                });
            });

            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: salesDatasets
                }
            });
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: revenueDatasets
                }
            });
        });
    </script>
</html>
