<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SB Admin 2 - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('adminassets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('adminassets/css/sb-admin-2.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">


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
                <!-- Begin Page Content -->
                <div class="container mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>📩 Messages de Contact</h2>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Sujet</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($messages as $message)
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $message->name }}</td>
                                    <td>{{ $message->email }}</td>
                                    <td>{{ $message->subject }}</td>
                                    <td>
                                        <button class="btn btn-link text-primary view-message"
                                                data-id="{{ $message->id }}"
                                                data-name="{{ $message->name }}"
                                                data-email="{{ $message->email }}"
                                                data-subject="{{ $message->subject }}"
                                                data-message="{{ $message->message }}"
                                                data-date="{{ $message->created_at->format('d/m/Y H:i') }}">
                                            📜 Voir
                                        </button>
                                    </td>
                                    <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $message->id }}">
                                            🗑 Supprimer
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $messages->links() }}
                    </div>
                </div>
                <!-- /.container-fluid -->

                <!-- Modal Bootstrap -->
                <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="messageModalLabel">Détails du Message</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Nom:</strong> <span id="modal-name"></span></p>
                                <p><strong>Email:</strong> <span id="modal-email"></span></p>
                                <p><strong>Sujet:</strong> <span id="modal-subject"></span></p>
                                <p><strong>Date:</strong> <span id="modal-date"></span></p>
                                <hr>
                                <p><strong>Message:</strong></p>
                                <p id="modal-message" class="text-muted"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
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
<!-- SweetAlert2 via CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ajoute un écouteur sur tout le document pour gérer les boutons dynamiquement
            document.body.addEventListener("click", function(event) {
                // 📌 Vérification si c'est le bouton "Voir le message"
                if (event.target.classList.contains("view-message")) {
                    document.getElementById("modal-name").textContent = event.target.getAttribute("data-name");
                    document.getElementById("modal-email").textContent = event.target.getAttribute("data-email");
                    document.getElementById("modal-subject").textContent = event.target.getAttribute("data-subject");
                    document.getElementById("modal-message").textContent = event.target.getAttribute("data-message");
                    document.getElementById("modal-date").textContent = event.target.getAttribute("data-date");

                    var modal = new bootstrap.Modal(document.getElementById("messageModal"));
                    modal.show();
                }

                // 📌 Vérification si c'est le bouton "Supprimer"
                if (event.target.classList.contains("delete-btn")) {
                    const messageId = event.target.getAttribute("data-id");

                    Swal.fire({
                        title: "Êtes-vous sûr ?",
                        text: "Cette action est irréversible !",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Oui, supprimer !",
                        cancelButtonText: "Annuler"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/contacts/${messageId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire("Supprimé !", "Le message a été supprimé.", "success")
                                        .then(() => location.reload());
                                } else {
                                    Swal.fire("Erreur", "Une erreur s'est produite, essayez de nouveau.", "error");
                                }
                            })
                            .catch(error => {
                                console.error("Erreur lors de la suppression :", error);
                                Swal.fire("Erreur", "Une erreur s'est produite.", "error");
                            });
                        }
                    });
                }
            });
        });
    </script>

<!-- Bootstrap core JavaScript -->
<script src="{{ asset('adminassets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminassets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript -->
<script src="{{ asset('adminassets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages -->
<script src="{{ asset('adminassets/js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('adminassets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminassets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('adminassets/js/demo/datatables-demo.js') }}"></script>


</body>

</html>
