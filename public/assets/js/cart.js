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
    const $couponMessage = $('#coupon-message');

    $couponTrigger.on('click', function() {
        // Only allow click if no success message is showing
        if (!$couponTrigger.find('div[style*="border: 1px dashed"]').length) {
            $(this).addClass('hidden');
            $couponInputGroup.addClass('visible');
            setTimeout(() => {
                $couponInput.focus();
            }, 300);
        }
    });

    $('.coupon-apply-btn').on('click', function() {
        let couponCode = $('#coupon_code').val();
        fetch("/admin/apply-coupon", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({ coupon_code: couponCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide input group and show success message in place of trigger button
                $couponInputGroup.removeClass('visible');
                $couponMessage.html(''); // Clear any error messages
                $couponTrigger
                    .html(`<div style="border-radius: 4px; text-align: center; color: #28a745;">
                            Coupon applied: ${data.discount}$ off
                          </div>`);
                $couponTrigger.removeClass('hidden');
                
                // Make the coupon trigger non-clickable
                $couponTrigger.css('pointer-events', 'none');
                
                // Update totals
                $('#discount').text(`$${data.discount}`);
                $('#payable_total').html(`<b>$${data.new_total}</b>`);
            } else {
                // Show error message in coupon-message div
                $couponMessage.html(`<span class="text-danger">${data.message}</span>`);
                setTimeout(() => {
                    $couponMessage.html('');
                }, 3000); // Clear error message after 3 seconds
            }
        });
    });

    // Close coupon input when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.coupon-wrapper').length) {
            if (!$couponTrigger.find('div[style*="border: 1px dashed"]').length) {
                $couponInputGroup.removeClass('visible');
                $couponTrigger.removeClass('hidden');
            }
        }
    });
});

$(document).ready(function() {
    // Check if DataTable is already initialized
    if (!$.fn.DataTable.isDataTable('#cartTable')) {
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
    }
});