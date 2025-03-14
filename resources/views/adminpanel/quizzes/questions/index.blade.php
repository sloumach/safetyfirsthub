<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Quiz Questions</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/quiz.css') }}" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">

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
                        <h1 class="h3 mb-0 text-gray-800">
                            Quiz Questions: {{ $quiz->section->title }}
                        </h1>
                        <div>
                            <a href="{{ route('admin.quizzes.show', $quiz->id) }}" class="quiz-btn quiz-btn-secondary mr-2">
                                <span class="quiz-btn-icon">
                                    <i class="fas fa-arrow-left"></i>
                                </span>
                                <span class="quiz-btn-text">Back to Quiz</span>
                            </a>
                            <a href="{{ route('admin.quizzes.questions.create', $quiz->id) }}" class="quiz-btn quiz-btn-primary">
                                <span class="quiz-btn-icon">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="quiz-btn-text">Add Question</span>
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Questions List Card -->
                    <div class="quiz-card">
                        <div class="quiz-card-header">
                            <h6 class="quiz-card-title">
                                <i class="fas fa-question-circle"></i>
                                Questions List
                            </h6>
                        </div>
                        <div class="quiz-container">
                            @if ($quiz->questions->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-question-circle fa-3x text-gray-300 mb-3"></i>
                                    <p class="mb-0">No questions have been added to this quiz yet.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="quiz-table">
                                        <thead>
                                            <tr>
                                                <th width="10%">ID</th>
                                                <th width="65%">Question Text</th>
                                                <th width="25%">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($quiz->questions as $question)
                                                <tr>
                                                    <td>{{ $question->id }}</td>
                                                    <td>{{ $question->question_text }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.quizzes.questions.edit', [$quiz->id, $question->id]) }}" 
                                                           class="quiz-btn quiz-btn-primary btn-sm mr-2">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.quizzes.questions.destroy', [$quiz->id, $question->id]) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf 
                                                            @method('DELETE')
                                                            <button type="submit" class="quiz-btn quiz-btn-secondary btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Delete confirmation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (this.method.toLowerCase() === 'post' && this.querySelector('button[type="submit"].quiz-btn-secondary')) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Delete Question?',
                        text: 'This action cannot be undone',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e74a3b',
                        cancelButtonColor: '#858796',
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel',
                        customClass: {
                            popup: 'small-dialog'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>
