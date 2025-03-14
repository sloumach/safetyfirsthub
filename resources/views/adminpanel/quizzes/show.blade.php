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
                <h2 class="mb-4">Détails du Quiz - {{ $quiz->section->title }}</h2>

        <div class="card">
            <div class="card-header">
                <h4>Informations du Quiz</h4>
            </div>
            <div class="card-body">
                <p><strong>Section :</strong> {{ $quiz->section->title }}</p>
                <p><strong>Score Minimum Requis :</strong> {{ $quiz->passing_score }}%</p>
            </div>
        </div>

        <div class="mt-4">
            <h4>Liste des Questions</h4>

            @if ($quiz->questions->isEmpty())
                <p>Aucune question n'est encore ajoutée à ce quiz.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Question</th>
                                <th>Choix</th>
                                <th>Réponse Correcte</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quiz->questions as $question)
                                <tr>
                                    <td>{{ $question->id }}</td>
                                    <td>{{ $question->question_text }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($question->choices as $choice)
                                                <li>
                                                    {{ $choice->choice_text }}
                                                    @if ($choice->is_correct)
                                                        <span class="badge bg-success">✔ Correct</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @foreach ($question->choices->where('is_correct', true) as $correctChoice)
                                            <span class="badge bg-primary">{{ $correctChoice->choice_text }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.questions.index', $quiz->id) }}" class="btn btn-primary">
                Gérer les Questions
            </a>
            <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                Retour à la Liste des Quiz
            </a>
        </div>

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
