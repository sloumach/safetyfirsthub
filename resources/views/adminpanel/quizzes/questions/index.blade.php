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
                <h2>Questions du Quiz: {{ $quiz->section->title }}</h2>
                <a href="{{ route('admin.quizzes.questions.create', $quiz->id) }}" class="btn btn-primary">Ajouter une Question</a>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table mt-4">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Texte de la Question</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quiz->questions as $question)
                            <tr>
                                <td>{{ $question->id }}</td>
                                <td>{{ $question->question_text }}</td>
                                <td>
                                    <a href="{{ route('admin.quizzes.questions.edit', [$quiz->id, $question->id]) }}" class="btn btn-warning btn-sm">Modifier</a>
                                    <form action="{{ route('admin.quizzes.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette question ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
