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
                <h2>Créer un Nouveau Coupon</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="code" class="form-label">Code du Coupon</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>

                    <div class="mb-3">
                        <label for="discount_type" class="form-label">Type de Réduction</label>
                        <select class="form-control" id="discount_type" name="discount_type" required>
                            <option value="fixed">Montant Fixe</option>
                            <option value="percentage">Pourcentage</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="discount_value" class="form-label">Valeur de la Réduction</label>
                        <input type="number" class="form-control" id="discount_value" name="discount_value"
                            step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="expiration_date" class="form-label">Date d’Expiration</label>
                        <input type="datetime-local" class="form-control" id="expiration_date" name="expiration_date"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="usage_limit" class="form-label">Limite d’Utilisation (optionnel)</label>
                        <input type="number" class="form-control" id="usage_limit" name="usage_limit">
                    </div>

                    <div class="mb-3">
                        <label for="is_active" class="form-label">Statut</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Créer le Coupon</button>
                </form>
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
