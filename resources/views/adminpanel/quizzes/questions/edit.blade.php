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
                <h2>Modifier la Question du Quiz : {{ $quiz->section->title }}</h2>

                <form action="{{ route('admin.quizzes.questions.update', [$quiz->id, $question->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="text">Question :</label>
                        <input type="text" name="question_text" class="form-control" value="{{ $question->question_text }}" required>
                    </div>

                    <h4>Choix de Réponse :</h4>
                    @foreach ($question->choices as $index => $choice)
                        <div class="form-group">
                            <label for="choice_{{ $index + 1 }}">Choix {{ $index + 1 }} :</label>
                            <input type="text" name="choices[{{ $index + 1 }}]" class="form-control" value="{{ $choice->choice_text }}" required>
                            <input type="radio" name="correct_choice" value="{{ $choice->id }}" @if($choice->is_correct) checked @endif>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-success">Mettre à jour</button>
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
