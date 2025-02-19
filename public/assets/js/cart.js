$(document).on('click', '.remove', function(e) {
    e.preventDefault();

    let $row = $(this).closest('tr');
    let courseId = $row.data('id');
    let removeUrl = $row.data('remove-url');
    let cartCountSpan = $('.cart-icon span');

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
                    title: 'Removed from cart.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    showCloseButton: true
                });

                $row.fadeOut(300, function() {
                    $(this).remove();
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
                    icon: 'error',
                    confirmButtonText: 'OK',
                    showCloseButton: true
                });
            }
        }
    });
});