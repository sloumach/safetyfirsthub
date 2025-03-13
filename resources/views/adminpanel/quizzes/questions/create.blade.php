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
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">

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
            <div class="content">
                <h2>Ajouter une Question au Quiz : {{ $quiz->section->title }}</h2>

                <form action="{{ route('admin.quizzes.questions.store', $quiz->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="text">Question :</label>
                        <input type="text" name="text" class="form-control" required>
                    </div>

                    <h4>Choix de Réponse :</h4>
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="form-group">
                            <label for="choice_{{ $i }}">Choix {{ $i }} :</label>
                            <input type="text" name="choices[{{ $i }}]" class="form-control" required>
                            <input type="checkbox" name="correct_choice" value="{{ $i }}"> Réponse correcte ?
                        </div>
                    @endfor

                    <button type="submit" class="btn btn-success">Ajouter Question</button>
                </form>
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
<!-- Chart.js Graph -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('adminassets/js/finances.js') }}"></script>

</html>
