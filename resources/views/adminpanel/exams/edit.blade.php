
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
    <!-- CSS principal -->
<link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet">

<!-- Font Awesome -->
<link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->


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
            <div class="container mt-4">
                <h2 class="mb-3">Edit Exam: <strong>{{ $exam->title }}</strong></h2>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.exams.update', $exam->id) }}" method="POST">
                            @csrf
                            @method('POST')

                            <!-- Select Course -->
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course</label>
                                <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                                    <option value="">-- Select a Course --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ $exam->course_id == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Exam Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $exam->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Exam Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3">{{ old('description', $exam->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Duration -->
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror"
                                       id="duration" name="duration" min="5" max="120"
                                       value="{{ old('duration', $exam->duration) }}" required>
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Passing Score -->
                            <div class="mb-3">
                                <label for="passing_score" class="form-label">Passing Score (%)</label>
                                <input type="number" class="form-control @error('passing_score') is-invalid @enderror"
                                       id="passing_score" name="passing_score" min="1" max="100"
                                       value="{{ old('passing_score', $exam->passing_score) }}" required>
                                @error('passing_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Exam Status -->
                            <div class="mb-3">
                                <label for="is_active" class="form-label">Status</label>
                                <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                                    <option value="1" {{ $exam->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$exam->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Exam
                                </button>
                                <a href="{{ route('admin.exams') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>



    <!-- Bootstrap core JavaScript-->
    <!-- jQuery -->
<script src="{{ asset('adminassets/vendor/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap Bundle -->
<script src="{{ asset('adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript -->
<script src="{{ asset('adminassets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages -->
<script src="{{ asset('adminassets/js/sb-admin-2.min.js') }}"></script>




</body>

</html>
