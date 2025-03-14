<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Add Question</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/course-form.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/quiz.css') }}" rel="stylesheet">
</head>

<body id="page-top">
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
                    <h1 class="h3 mb-4 text-gray-800">Add Question</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">New Question for Quiz: {{ $quiz->section->title }}</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.quizzes.questions.store', $quiz->id) }}" method="POST" class="course-form">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="text">Question Text</label>
                                    <input type="text" name="text" id="text" class="form-control" required>
                                </div>

                                <div class="section">
                                    <h4 class="mb-4">Answer Choices</h4>
                                    @for ($i = 1; $i <= 4; $i++)
                                        <div class="form-group">
                                            <div class="section-header">
                                                <label for="choice_{{ $i }}">Choice {{ $i }}</label>
                                                <input type="text" name="choices[{{ $i }}]" id="choice_{{ $i }}" class="form-control" required>
                                                <div class="ml-2">
                                                    <input type="checkbox" name="correct_choice" value="{{ $i }}" id="correct_{{ $i }}" class="mr-2">
                                                    <label for="correct_{{ $i }}" class="mb-0">Correct Answer</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <div class="text-right mt-4">
                                    <a href="{{ url()->previous() }}" class="quiz-btn quiz-btn-secondary mr-2">
                                        <span class="quiz-btn-icon">
                                            <i class="fas fa-times"></i>
                                        </span>
                                        <span class="quiz-btn-text">Cancel</span>
                                    </a>
                                    <button type="submit" class="quiz-btn quiz-btn-success">
                                        <span class="quiz-btn-icon">
                                            <i class="fas fa-save"></i>
                                        </span>
                                        <span class="quiz-btn-text">Save Question</span>
                                    </button>
                                </div>
                            </form>
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
                        <span>Copyright &copy; Your Website 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
    </div>

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
