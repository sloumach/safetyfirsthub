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

// Coupon handling
$(document).ready(function() {
    const $couponTrigger = $('.coupon-trigger');
    const $couponInputGroup = $('.coupon-input-group');
    const $couponInput = $('.coupon-input-group .form-control');

    $couponTrigger.on('click', function() {
        $(this).addClass('hidden');
        $couponInputGroup.addClass('visible');
        setTimeout(() => {
            $couponInput.focus();
        }, 300);
    });

    // Optional: Reset to initial state when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.coupon-wrapper').length) {
            $couponTrigger.removeClass('hidden');
            $couponInputGroup.removeClass('visible');
        }
    });
});

$(document).ready(function() {
    $('#cartTable').DataTable({
        pageLength: 4,
        lengthMenu: [4, 8, 12],
        language: {
            lengthMenu: "Show _MENU_ items per page",
            info: "",
            infoEmpty: "",
            search: "Search:",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            zeroRecords: "No data available in table"
        },
        columnDefs: [
            { orderable: false, targets: [0, 3] }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12"p>>'
    });
});