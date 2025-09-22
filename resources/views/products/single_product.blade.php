@extends('layout.master')
@section('content')
<div class="single-product mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<div class="single-product-img">
                        @if ($product->productImages)
                            @foreach($product->productImages as $image)
                            <div>
                                <img src="{{ asset($image->image_path) }}" alt="">
                            </div>
                            @endforeach
                            @else
                            @endif
                            <img src="{{ asset($product->image_path) }}" alt="">
						{{-- <img src="{{asset("$product->")}}" alt=""> --}}
					</div>
				</div>
				<div class="col-md-7">
					<div class="single-product-content">
						<h3>{{$product->name}}</h3>
						<p class="single-product-pricing"><span>Per Kg</span> ${{$product->price}}</p>
						<p>{{$product->description}}</p>
						<div class="single-product-form">
							<form action="index.html">
								<input type="number" placeholder="0">
							</form>
                            <button class="cart-btn add-to-cart-btn" id="Add_to_Cart"   data-product-id="{{$product->id}}">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
							<p><strong>Categories: </strong>{{$product->category->name}}</p>
						</div>
						<h4>Share:</h4>
						<ul class="product-share">
							<li><a href=""><i class="fab fa-facebook-f"></i></a></li>
							<li><a href=""><i class="fab fa-twitter"></i></a></li>
							<li><a href=""><i class="fab fa-google-plus-g"></i></a></li>
							<li><a href=""><i class="fab fa-linkedin"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
    <div class="more-products mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">
						<h3><span class="orange-text">Related</span> Products</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
					</div>
				</div>
			</div>
			<div class="row">
                @foreach ($related_products as $item)
                    <div class="col-lg-4 col-md-6 text-center">
                        <div class="single-product-item">
                            <div class="product-image">
                                <a href="{{route("ShowProduct",[$item->id])}}"><img src="{{asset("$item->image_path")}}" alt=""></a>
                            </div>
                            <h3>{{$item->name}}</h3>
                            <p class="product-price"><span>Per Kg</span> {{$item->price}}$ </p>
                            <button class="cart-btn add-to-cart-btn" id="Add_to_Cart" data-product-id="{{$item->id}}">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                            {{-- <a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a> --}}
                        </div>
                    </div>
                @endforeach
			</div>
		</div>
	</div>

<!-- Success/Error Message Modal or Alert -->
{{-- <div id="message-alert" class="alert" style="display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
    <span id="message-text"></span>
    <button type="button" class="close" onclick="closeAlert()">&times;</button>
</div> --}}

@endsection

@section('js')
<script>
$(document).ready(function() {
    console.log('Single product page script loaded and ready!');

    // CSRF Token setup for AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Add to Cart AJAX
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault();
        console.log('Product add to cart button clicked!');

        var button = $(this);
        var productId = button.data('product-id');
        console.log('Product ID:', productId);

        // Disable button during request
        button.prop('disabled', true);
        button.html('<i class="fas fa-spinner fa-spin"></i> Adding...');

        $.ajax({
            url: '/add_to_cart',
            type: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Success response:', response);
                if (response.success) {
                    console.log(response.message, 'success');
                    // Update cart count in header if the function exists
                    if (typeof window.loadCartCount === 'function') {
                        window.loadCartCount();
                    }
                } else {
                    console.log(response.message || 'Something went wrong!', 'error');
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
                console.log(errorMessage, 'error');
            },
            complete: function() {
                // Re-enable button
                button.prop('disabled', false);
                button.html('<i class="fas fa-shopping-cart"></i> Add to Cart');
            }
        });
    });
});

</script>

<style>
/* Alert Styles */

/* Button loading state */

</style>
@endsection
