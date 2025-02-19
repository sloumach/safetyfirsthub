$(document).on('click', '.add-to-cart', function() {
    let courseId = $(this).data('id'); // Récupérer l'ID du cours
    let cartCountSpan = $('.cart-icon span'); // Sélectionne les compteurs du panier
    let url = $(this).data('url'); // Get the URL from data attribute

    $.ajax({
        url: url, // Use the URL from data attribute
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // Get CSRF token from meta tag
            course_id: courseId, // Renommé pour être plus clair
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    customClass: { confirmButton: "default-btn" },
                    title: 'Added to the cart.',
                    icon: 'success',
                    confirmButtonText: 'Cart',
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "/cart";
                    }
                });

                // Mettre à jour dynamiquement le compteur du panier
                cartCountSpan.text(response.cart_count);
            }
        },
        error: function(xhr) {
            let response = xhr.responseJSON;
            if (response && response.message) {
                Swal.fire({
                    customClass: { confirmButton: "default-btn" },
                    title: response.message,
                    icon: 'warning',
                    confirmButtonText: response.message === "Product already in your cart!" ? 'Cart' : 'Dashboard',
                    showCloseButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = response.message === "Product already in your cart!" ? "/cart" : "/dashboard";
                    }
                });
            }
        }
    });
});