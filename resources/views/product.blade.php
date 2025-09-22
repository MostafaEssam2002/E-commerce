@extends('layout.master')
@section('content')
<br>
<br>
<br>
<br>
<br>
<br>

<div class="more-products mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">
						<h3><span class="orange-text">OUR</span> Products</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
					</div>
				</div>
			</div>
			<div class="row">
                @foreach ($products as $item)
                <div class="col-lg-4 col-md-6 text-center">
					<div class="single-product-item">
						<div class="product-image">
							<a href="single-product.html"><img src="{{url(asset($item->image_path))}}" alt=""></a>
						</div>
						<h3>{{$item->name}}</h3>
						<p class="product-price"><span>{{$item->quantity}}</span> {{$item->price}}$ </p>

						<!-- Updated Add to Cart Button with AJAX -->
						<button class="cart-btn add-to-cart-btn" id="Add_to_Cart" data-product-id="{{$item->id}}">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>

						<a href="/removeproduct/{{$item->id}}" class="cart-btn" style="background-color: red">
                            <i class="fas fa-shopping-cart"></i> Remove Product
                        </a>
                        <br>
                        <br>
						<a href="/editproduct/{{$item->id}}" class="cart-btn" style="background-color: blue">
                            <i class="fas fa-shopping-cart"></i> Edit Product
                        </a>
					</div>
				</div>
                @endforeach
			</div>
		</div>
	</div>

<!-- Success/Error Message Modal or Alert -->
<div id="message-alert" class="alert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
    <span id="message-text"></span>
    <button type="button" class="close" onclick="closeAlert()">&times;</button>
</div>

@endsection

@section('js')
<script>
$(document).ready(function() {
    console.log('Script loaded and ready!');

    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Function to update cart count in header
    function updateCartCountDisplay(count) {
        const cartBadge = $('#cart_count');

        if (count > 0) {
            cartBadge.text(count);
            cartBadge.removeClass('d-none').show();
            cartBadge.removeAttr('data-count');

            // Add animation effect
            cartBadge.addClass('updated');
            setTimeout(function() {
                cartBadge.removeClass('updated');
            }, 600);

            // Handle large numbers
            if (count > 99) {
                cartBadge.addClass('large-number');
                cartBadge.text('99+');
            } else {
                cartBadge.removeClass('large-number');
                cartBadge.text(count);
            }
        } else {
            cartBadge.attr('data-count', '0');
            cartBadge.text('0');
        }
    }

    // Add to Cart AJAX
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        console.log('Button clicked!');

        var button = $(this);
        var productId = button.data('product-id');
        console.log('Product ID:', productId);

        // Disable button during request
        button.prop('disabled', true);
        var originalText = button.html();
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
                    // Update cart count in header
                    updateCartCountDisplay(response.cart_count);
                } else {
                    showMessage(response.message || 'Something went wrong!', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', xhr, status, error);
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
                button.html(originalText);
            }
        });
    });

    // Load initial cart count when page loads
    loadCartCount();
});

// Function to load cart count from server
function loadCartCount() {
    $.ajax({
        url: '{{ route("cart.count") }}',
        type: 'GET',
        success: function(response) {
            updateCartCountDisplay(response.count);
        },
        error: function() {
            console.log('Failed to load cart count');
        }
    });
}

// Function to update cart count display
function updateCartCountDisplay(count) {
    const cartBadge = $('#cart_count');

    if (count > 0) {
        cartBadge.text(count);
        cartBadge.removeClass('d-none').show();
        cartBadge.removeAttr('data-count');

        // Add animation effect
        cartBadge.addClass('updated');
        setTimeout(function() {
            cartBadge.removeClass('updated');
        }, 600);

        // Handle large numbers
        if (count > 99) {
            cartBadge.addClass('large-number');
            cartBadge.text('99+');
        } else {
            cartBadge.removeClass('large-number');
            cartBadge.text(count);
        }
    } else {
        cartBadge.attr('data-count', '0');
        cartBadge.text('0');
    }
}

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
    } else if (type === 'warning') {
        alertBox.addClass('alert-warning');
    } else {
        alertBox.addClass('alert-info');
    }

    alertBox.show();

    // Auto hide after 5 seconds
    setTimeout(function() {
        alertBox.fadeOut();
    }, 5000);
}

// Close alert function
function closeAlert() {
    $('#message-alert').fadeOut();
}
</script>

<style>
/* Alert Styles */
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
    position: relative;
}

.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}

.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}

.alert-warning {
    color: #8a6d3b;
    background-color: #fcf8e3;
    border-color: #faebcc;
}

.alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}

.alert .close {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    color: inherit;
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    opacity: 0.7;
}

.alert .close:hover {
    opacity: 1;
}

/* Button loading state */
.add-to-cart-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Make sure button looks like other cart buttons */
.add-to-cart-btn {
    background: none;
    border: none;
    padding: 0;
    font: inherit;
    cursor: pointer;
    outline: inherit;
}

/* Cart badge animations */
.cart-badge.updated {
    animation: bounce 0.6s ease;
}

@keyframes bounce {
    0%, 20%, 60%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    80% {
        transform: translateY(-5px);
    }
}
</style>
@endsection
