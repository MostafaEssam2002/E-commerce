$(document).ready(function() {
    console.log('Products page script loaded and ready!');

    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add to Cart AJAX
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        console.log('Add to cart button clicked!');

        var button = $(this);
        var productId = button.data('product-id');
        console.log('Product ID:', productId);

        // Disable button during request
        button.prop('disabled', true);
        button.html('<i class="fas fa-spinner fa-spin"></i> Adding...');

        $.ajax({
            url: '{{ route("add_to_cart") }}',
            type: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    showMessage(response.message, 'success');
                } else {
                    showMessage(response.message || 'Something went wrong!', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr, status, error);
                console.log('Response:', xhr.responseText);
                var errorMessage = 'Something went wrong!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join(', ');
                }
                showMessage(errorMessage, 'error');
            },
            complete: function() {
                // Re-enable button
                button.prop('disabled', false);
                button.html('<i class="fas fa-shopping-cart"></i> Add to Cart');
            }
        });
    });
});

// Show message function
function showMessage(message, type) {
    var alertBox = $('#message-alert');
    var messageText = $('#message-text');

    messageText.text(message);

    // Remove existing classes and add appropriate class
    alertBox.removeClass('alert-success alert-danger alert-info alert-warning');

    if (type === 'success') {
        alertBox.addClass('alert-success');
    } else if (type === 'error') {
        alertBox.addClass('alert-danger');
    } else {
        alertBox.addClass('alert-info');
    }

    alertBox.show();

    // Auto hide after 4 seconds
    setTimeout(function() {
        alertBox.fadeOut();
    }, 4000);
}

// Close alert function
function closeAlert() {
    $('#message-alert').fadeOut();
}
