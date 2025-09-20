@extends('layout.master')
@section('content')

<div class="product-section mt-150 mb-150">
		<div class="container">

			<div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            @foreach ($categories as $category)
                                <li data-filter="._{{$category->id}}">{{$category->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

			<div class="row product-lists" style="position: relative; height: 1147.4px;">
                @foreach ($products as $product)
				<div class="col-lg-4 col-md-6 text-center  _{{$product->category_id}}" style="position: absolute; left: 0px; top: 0px;">
                    <div class="single-product-item">
						<div class="product-image">
							<a href="single-product.html"><img src="{{asset($product->image_path)}}" alt=""></a>
						</div>
						<h3>{{$product->name}}</h3>
						<p class="product-price">{{$product->price}}$</p>
                        <span>Quantity: {{$product->quantity}}</span>
                        <br><br>
                        <!-- Fixed: Changed $item->id to $product->id -->
                        <button class="cart-btn add-to-cart-btn" id="Add_to_Cart"   data-product-id="{{$product->id}}">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
					</div>
				</div>
                @endforeach
			</div>
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="pagination-wrap">
						<ul>
							<li><a href="#">Prev</a></li>
							<li><a href="#">1</a></li>
							<li><a class="active" href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">Next</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Success/Error Message Alert - This was missing! -->
<div id="message-alert" class="alert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
    <span id="message-text"></span>
    <button type="button" class="close" onclick="closeAlert()">&times;</button>
</div>

    <div class="logo-carousel-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="logo-carousel-inner">
						<div class="single-logo-item">
							<img src="assets/img/company-logos/1.png" alt="">
						</div>
						<div class="single-logo-item">
							<img src="assets/img/company-logos/2.png" alt="">
						</div>
						<div class="single-logo-item">
							<img src="assets/img/company-logos/3.png" alt="">
						</div>
						<div class="single-logo-item">
							<img src="assets/img/company-logos/4.png" alt="">
						</div>
						<div class="single-logo-item">
							<img src="assets/img/company-logos/5.png" alt="">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
<script>
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
</style>
@endsection
