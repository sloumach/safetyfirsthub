<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Create Quiz</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('adminassets/css/quiz.css') }}" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Create New Quiz</h1>
                        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="text">Back to List</span>
                        </a>
                    </div>

                    <!-- Create Quiz Card -->
                    <div class="quiz-card">
                        <div class="quiz-card-header">
                            <h6 class="quiz-card-title">
                                <i class="fas fa-plus-circle"></i>
                                Quiz Information
                            </h6>
                        </div>
                        <div class="quiz-container">
                            <form action="{{ route('admin.quizzes.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Select Course -->
                                    <div class="col-md-6">
                                        <div class="quiz-input-group">
                                            <label class="quiz-label">
                                                <i class="fas fa-book"></i> Course
                                            </label>
                                            <select id="course-select" class="quiz-select" required>
                                                <option value="" disabled selected>Select a course</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Select Section (filtered) -->
                                    <div class="col-md-6">
                                        <div class="quiz-input-group">
                                            <label class="quiz-label">
                                                <i class="fas fa-book-open"></i> Section
                                            </label>
                                            <select name="section_id" id="section-select" class="quiz-select @error('section_id') quiz-input-error @enderror" required>
                                                <option value="" disabled selected>Select a section</option>
                                                {{-- Options ajoutées dynamiquement via JS --}}
                                            </select>
                                            @error('section_id')
                                                <div class="quiz-error-message">
                                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Other inputs (Passing Score etc.) -->
                                <div class="col-md-6 mt-3">
                                    <div class="quiz-input-group">
                                        <label class="quiz-label">
                                            <i class="fas fa-percentage"></i> Passing Score (%)
                                        </label>
                                        <input type="number" name="passing_score" class="quiz-input @error('passing_score') quiz-input-error @enderror" min="0" max="100" required>
                                        @error('passing_score')
                                            <div class="quiz-error-message">
                                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="quiz-input-group">
                                        <label class="quiz-label">
                                            <i class="fas fa-percentage"></i> title
                                        </label>
                                        <input type="text" name="title" class="quiz-input" required>

                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="text-right mt-4 col-12">
                                    <button type="submit" class="quiz-btn quiz-btn-primary">
                                        <span class="quiz-btn-icon"><i class="fas fa-check"></i></span>
                                        <span class="quiz-btn-text">Create Quiz</span>
                                    </button>
                                </div>
                            </form>

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

    <!-- Select2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
        });
    </script>
    <script>
        const courses = @json($courses);
        const courseSelect = document.getElementById('course-select');
        const sectionSelect = document.getElementById('section-select');

        courseSelect.addEventListener('change', function () {
            const courseId = parseInt(this.value);
            const selectedCourse = courses.find(course => course.id === courseId);

            // Vider les anciennes options
            sectionSelect.innerHTML = '<option value="" disabled selected>Select a section</option>';

            if (selectedCourse) {
                selectedCourse.sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.title;
                    sectionSelect.appendChild(option);
                });
            }
        });
    </script>

</body>
</html>
