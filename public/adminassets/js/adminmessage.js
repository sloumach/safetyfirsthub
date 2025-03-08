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