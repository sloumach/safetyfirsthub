<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Quiz Details</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/quiz.css') }}" rel="stylesheet">

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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Quiz Details</h1>
                        <a href="{{ route('admin.quizzes.index') }}" class="quiz-btn quiz-btn-secondary">
                            <span class="quiz-btn-icon">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="quiz-btn-text">Back to List</span>
                        </a>
                    </div>

                    <!-- Quiz Info Card -->
                    <div class="quiz-card mb-4">
                        <div class="quiz-card-header">
                            <h6 class="quiz-card-title">
                                <i class="fas fa-info-circle"></i>
                                Quiz Information - {{ $quiz->section->title }}
                            </h6>
                        </div>
                        <div class="quiz-container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="quiz-input-group">
                                        <label class="quiz-label">
                                            <i class="fas fa-book"></i>
                                            Section
                                        </label>
                                        <p class="mb-0">{{ $quiz->section->title }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="quiz-input-group">
                                        <label class="quiz-label">
                                            <i class="fas fa-percentage"></i>
                                            Passing Score
                                        </label>
                                        <div class="quiz-status quiz-status-active">
                                            {{ $quiz->passing_score }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions List Card -->
                    <div class="quiz-card">
                        <div class="quiz-card-header">
                            <h6 class="quiz-card-title">
                                <i class="fas fa-question-circle"></i>
                                Quiz Questions
                            </h6>
                        </div>
                        <div class="quiz-container">
                            @if ($quiz->questions->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-gray-300 mb-3"></i>
                                    <p class="mb-0">No questions have been added to this quiz yet.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="quiz-table">
                                        <thead>
                                            <tr>
                                                <th width="5%">ID</th>
                                                <th width="40%">Question</th>
                                                <th width="40%">Choices</th>
                                                <th width="15%">Correct Answer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($quiz->questions as $question)
                                                <tr>
                                                    <td>{{ $question->id }}</td>
                                                    <td>{{ $question->question_text }}</td>
                                                    <td>
                                                        <ul class="list-unstyled mb-0">
                                                            @foreach ($question->choices as $choice)
                                                                <li class="mb-1">
                                                                    {{ $choice->choice_text }}
                                                                    @if ($choice->is_correct)
                                                                        <span class="quiz-status quiz-status-active ml-2">
                                                                            <i class="fas fa-check mr-1"></i> Correct
                                                                        </span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>
                                                        @foreach ($question->choices->where('is_correct', true) as $correctChoice)
                                                            <span class="quiz-status quiz-status-active">
                                                                {{ $correctChoice->choice_text }}
                                                            </span>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            <div class="text-right mt-4">
                                <a href="{{ route('admin.questions.index', $quiz->id) }}" class="quiz-btn quiz-btn-primary">
                                    <span class="quiz-btn-icon">
                                        <i class="fas fa-cog"></i>
                                    </span>
                                    <span class="quiz-btn-text">Manage Questions</span>
                                </a>
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
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

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

</body>

</html>
