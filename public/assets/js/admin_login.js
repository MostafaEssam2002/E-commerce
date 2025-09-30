
$(document).ready(function() {
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': window.appConfig.csrfToken
        }
    });
    // Form submit handler
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        // Clear previous errors
        clearErrors();

        // Show loading state
        showLoading(true);

        // Collect form data
        const formData = {
            email: $('#email').val(),
            password: $('#password').val(),
            _token: window.appConfig.csrfToken
        };

        // Send AJAX request
        $.ajax({
            url: window.appConfig.loginCheckUrl,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirect
                        window.location.href = response.redirect_url;
                    });
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;

                if (xhr.status === 422) {
                    // Validation errors
                    displayValidationErrors(response.errors);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                } else {
                    // Other errors (401, 403, 500)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message || 'An unexpected error occurred'
                    });
                }
            },
            complete: function() {
                // Hide loading state
                showLoading(false);
            }
        });
    });

    // Clear error messages on input
    $('#email, #password').on('input', function() {
        const fieldName = $(this).attr('name');
        $(`#${fieldName}-error`).text('');
        $(this).closest('.form-group').removeClass('error');
    });

    // Helper functions
    function showLoading(show) {
        const btn = $('#loginBtn');
        if (show) {
            btn.addClass('btn-loading').prop('disabled', true);
        } else {
            btn.removeClass('btn-loading').prop('disabled', false);
        }
    }

    function clearErrors() {
        $('.error-message').text('');
        $('.form-group').removeClass('error');
        $('#alert-container').empty();
    }

    function displayValidationErrors(errors) {
        $.each(errors, function(field, messages) {
            const errorDiv = $(`#${field}-error`);
            const formGroup = $(`#${field}`).closest('.form-group');

            if (errorDiv.length) {
                errorDiv.text(messages[0]);
                formGroup.addClass('error');
            }
        });
    }

    function showAlert(type, message) {
        const alertClass = type === 'error' ? 'alert-danger' : 'alert-success';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        $('#alert-container').html(alertHtml);
    }
});
