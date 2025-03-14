<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Edit Question</title>

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
                    <div class="quiz-wrapper">
                        <!-- Header -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Edit Question</h1>
                            <a href="{{ route('admin.questions.index', $quiz->id) }}" class="quiz-btn quiz-btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Questions
                            </a>
                        </div>

                        <!-- Question Editor Card -->
                        <div class="quiz-card">
                            <div class="quiz-card-header">
                                <h6 class="quiz-card-title">
                                    <i class="fas fa-edit"></i>
                                    Edit Question for: {{ $quiz->section->title }}
                                </h6>
                            </div>
                            
                            <div class="quiz-container">
                                <form action="{{ route('admin.quizzes.questions.update', [$quiz->id, $question->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <!-- Question Text -->
                                    <div class="quiz-input-group">
                                        <label class="quiz-label">
                                            <i class="fas fa-question"></i>
                                            Question Text
                                        </label>
                                        <input type="text" 
                                               name="question_text" 
                                               class="quiz-input @error('question_text') is-invalid @enderror" 
                                               value="{{ old('question_text', $question->question_text) }}" 
                                               required>
                                        @error('question_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Choices -->
                                    <div class="quiz-choices-grid">
                                        @foreach ($question->choices as $index => $choice)
                                            <div class="quiz-choice-item">
                                                <div class="quiz-choice-header">
                                                    <div class="quiz-radio-wrapper">
                                                        <input type="radio" 
                                                               name="correct_choice" 
                                                               value="{{ $choice->id }}" 
                                                               class="quiz-radio"
                                                               @if($choice->is_correct) checked @endif>
                                                        <label class="quiz-choice-label">Choice {{ $index + 1 }}</label>
                                                    </div>
                                                </div>
                                                <input type="text" 
                                                       name="choices[{{ $index + 1 }}]" 
                                                       class="quiz-input @error('choices.'.$index) is-invalid @enderror" 
                                                       value="{{ old('choices.'.$index, $choice->choice_text) }}" 
                                                       required>
                                                @error('choices.'.$index)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="quiz-actions">
                                        <button type="submit" class="quiz-btn quiz-btn-primary">
                                            <i class="fas fa-save"></i> Update Question
                                        </button>
                                        <a href="{{ route('admin.questions.index', $quiz->id) }}" class="quiz-btn quiz-btn-secondary ml-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </form>
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
    <!-- Chart.js Graph -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('adminassets/js/finances.js') }}"></script>

</body>

</html>
