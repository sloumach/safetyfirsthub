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
        <div class="container mt-4">
            <h2 class="mb-3">Edit Question for: <strong>{{ $exam->title }}</strong></h2>

            <!-- Retour vers la liste des questions -->
            <a href="{{ route('admin.exams.questions', $exam->id) }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Questions
            </a>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Texte de la question -->
                        <div class="mb-3">
                            <label for="question_text" class="form-label">Question Text</label>
                            <textarea class="form-control @error('question_text') is-invalid @enderror"
                                      id="question_text" name="question_text" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Réponses -->
                        <h5>Choices (One correct answer)</h5>
                        @foreach ($question->choices as $index => $choice)
                            <div class="mb-2">
                                <label for="choice_{{ $index + 1 }}">Choice {{ $index + 1 }}</label>
                                <input type="text" class="form-control @error('choices.' . $index) is-invalid @enderror"
                                       id="choice_{{ $index + 1 }}" name="choices[]"
                                       value="{{ old('choices.' . $index, $choice->choice_text) }}" required>
                                @error('choices.' . $index)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <!-- Sélection de la réponse correcte -->
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Correct Answer</label>
                            <select class="form-select @error('correct_answer') is-invalid @enderror"
                                    id="correct_answer" name="correct_answer" required>
                                @foreach ($question->choices as $index => $choice)
                                    <option value="{{ $index + 1 }}" {{ $choice->is_correct ? 'selected' : '' }}>
                                        Choice {{ $index + 1 }}
                                    </option>
                                @endforeach
                            </select>
                            @error('correct_answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bouton de soumission -->
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Question
                        </button>
                    </form>
                </div>
            </div>
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
