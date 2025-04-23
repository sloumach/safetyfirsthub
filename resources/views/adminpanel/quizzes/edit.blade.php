<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Edit Quiz</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Edit Quiz</h1>
                        <a href="{{ route('admin.quizzes.index') }}" class="quiz-btn quiz-btn-secondary">
                            <span class="quiz-btn-icon">
                                <i class="fas fa-arrow-left"></i>
                            </span>
                            <span class="quiz-btn-text">Back to List</span>
                        </a>
                    </div>

                    <!-- Edit Quiz Card -->
                    <div class="quiz-card">
                        <div class="quiz-card-header">
                            <h6 class="quiz-card-title">
                                <i class="fas fa-edit"></i>
                                Edit Quiz Information
                            </h6>
                        </div>
                        <div class="quiz-container">
                            <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    {{-- Select Course --}}
                                    <div class="col-md-6">
                                        <div class="quiz-input-group">
                                            <label class="quiz-label"><i class="fas fa-book"></i> Course</label>
                                            <select id="course-select" class="quiz-select" required>
                                                <option value="" disabled>Select a course</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}"
                                                        {{ $course->sections->contains('id', $quiz->section_id) ? 'selected' : '' }}>
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Select Section (filtered) --}}
                                    <div class="col-md-6">
                                        <div class="quiz-input-group">
                                            <label class="quiz-label"><i class="fas fa-book-open"></i> Section</label>
                                            <select name="section_id" id="section-select" class="quiz-select @error('section_id') quiz-input-error @enderror" required>
                                                <option value="" disabled>Select a section</option>
                                                {{-- Options ajoutées dynamiquement via JS --}}
                                            </select>
                                            @error('section_id')
                                                <div class="quiz-error-message">
                                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Quiz Title --}}
                                    <div class="col-md-6 mt-3">
                                        <div class="quiz-input-group">
                                            <label class="quiz-label"><i class="fas fa-heading"></i> Title</label>
                                            <input type="text" name="title" value="{{ old('title', $quiz->title) }}"
                                                   class="quiz-input @error('title') quiz-input-error @enderror" required>
                                            @error('title')
                                                <div class="quiz-error-message">
                                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Passing Score --}}
                                    <div class="col-md-6 mt-3">
                                        <div class="quiz-input-group">
                                            <label class="quiz-label"><i class="fas fa-percentage"></i> Passing Score (%)</label>
                                            <input type="number" name="passing_score" class="quiz-input @error('passing_score') quiz-input-error @enderror"
                                                   min="0" max="100" value="{{ old('passing_score', $quiz->passing_score) }}" required>
                                            @error('passing_score')
                                                <div class="quiz-error-message">
                                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="text-right mt-4 col-12">
                                    <a href="{{ route('admin.quizzes.index') }}" class="quiz-btn quiz-btn-secondary mr-2">
                                        <span class="quiz-btn-icon"><i class="fas fa-times"></i></span>
                                        <span class="quiz-btn-text">Cancel</span>
                                    </a>
                                    <button type="submit" class="quiz-btn quiz-btn-success">
                                        <span class="quiz-btn-icon"><i class="fas fa-save"></i></span>
                                        <span class="quiz-btn-text">Update Quiz</span>
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
    <script>
        const courses = @json($courses);
        const courseSelect = document.getElementById('course-select');
        const sectionSelect = document.getElementById('section-select');
        const selectedSectionId = {{ $quiz->section_id }};

        function updateSectionOptions(courseId) {
            const selectedCourse = courses.find(c => c.id == courseId);
            sectionSelect.innerHTML = '<option value="" disabled>Select a section</option>';

            if (selectedCourse) {
                selectedCourse.sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.title;

                    if (section.id == selectedSectionId) {
                        option.selected = true;
                    }

                    sectionSelect.appendChild(option);
                });
            }
        }

        // Initialisation
        updateSectionOptions(courseSelect.value);

        // Changement de cours
        courseSelect.addEventListener('change', function () {
            updateSectionOptions(this.value);
        });
    </script>


</body>

</html>
