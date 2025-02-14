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
        <div class="container">
            <h2>Ajouter une question pour l'examen : {{ $exam->title }}</h2>

            <form action="{{ route('admin.exams.questions.store', $exam->id) }}" method="POST">
                @csrf

                <!-- Question -->
                <div class="mb-3">
                    <label for="question_text" class="form-label">Question</label>
                    <textarea name="question_text" id="question_text" class="form-control" required></textarea>
                </div>

                <!-- Réponses -->
                <div class="mb-3">
                    <label class="form-label">Réponses</label>
                    <div class="row">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_answer" value="{{ $i }}" required>
                                    <input type="text" name="answers[]" class="form-control" placeholder="Réponse {{ $i }}" required>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Bouton Submit -->
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Ajouter Question</button>
            </form>
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
