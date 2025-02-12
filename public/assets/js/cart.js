$(document).on('click', '.remove', function(e) {
    e.preventDefault();

    let $row = $(this).closest('tr'); // Sélection du <tr> à supprimer
    let courseId = $row.data('id'); // Récupération de l'ID du cours
    let cartCountSpan = $('.cart-icon span'); // Sélectionne le compteur du panier

    $.ajax({
        url: "{{ route('remove.from.cart') }}", // Route pour la suppression
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            course_id: courseId,
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    customClass: { confirmButton: "default-btn" },
                    title: 'Removed from cart.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    showCloseButton: true
                });

                // Suppression du cours du DOM
                $row.fadeOut(300, function() {
                    $(this).remove();
                });

                // Mise à jour du compteur du panier
                cartCountSpan.text(response.cart_count);
            }
        },
        error: function(xhr) {
            let response = xhr.responseJSON;
            if (response && response.message) {
                Swal.fire({
                    customClass: { confirmButton: "default-btn" },
                    title: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    showCloseButton: true
                });
            }
        }
    });
});