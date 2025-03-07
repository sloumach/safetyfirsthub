$(document).ready(function() {
    // Add to Cart functionality
    $(document).on('click', '.wishlist-cart-btn', function() {
        let $row = $(this).closest('tr');
        let courseId = $row.data('id');
        let addUrl = $row.data('add-url');
        let cartCountSpan = $('.cart-icon span');

        $.ajax({
            url: addUrl,
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                course_id: courseId,
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        customClass: { confirmButton: "default-btn" },
                        title: 'Added to cart!',
                        icon: 'success',
                        confirmButtonText: 'Go to Cart',
                        showCloseButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/cart';
                        }
                    });

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
                        confirmButtonText: 'OK',
                        showCloseButton: true
                    });
                }
            }
        });
    });

    // Remove from Wishlist functionality
    $(document).on('click', '.wishlist-remove-btn', function() {
        let $row = $(this).closest('tr');
        let courseId = $row.data('id');
        let removeUrl = $row.data('remove-url');

        $.ajax({
            url: removeUrl,
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                course_id: courseId,
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        customClass: { confirmButton: "default-btn" },
                        title: 'Removed from wishlist!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        showCloseButton: true
                    });

                    $row.fadeOut(300, function() {
                        $(this).remove();
                    });
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
}); 

$(document).ready(function() {
    $('#wishlistTable').DataTable({
        pageLength: 4,
        lengthMenu: [4, 8, 12],
        language: {
            lengthMenu: "Show _MENU_ items per page",
            info: "Showing _START_ to _END_ of _TOTAL_ items",
            infoEmpty: "No items available",
            search: "Search:",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});