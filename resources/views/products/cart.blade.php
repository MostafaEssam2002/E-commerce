@extends('layout.master')

@section('content')
<div class="cart-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="cart-table-wrap">
                    <table class="cart-table">
                        <thead class="cart-table-head">
                            <tr class="table-head-row">
                                <th class="product-remove"></th>
                                <th class="product-image">Product Image</th>
                                <th class="product-name">Name</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                            <tr class="table-body-row" data-product-id="{{ $item->product_id }}" id="cart-item-{{ $item->product_id }}">
                                <td class="product-remove">
                                    <a href="#" class="remove-from-cart-btn" data-product-id="{{ $item->product_id }}">
                                        <i class="far fa-window-close"></i>
                                    </a>
                                </td>
                                <td class="product-image">
                                    <img style="max-height: 60px;min-height: 60px;min-width: 50px;max-width: 50px;width: 90%"
                                        src="{{asset($item->product->image_path)}}" alt="">
                                </td>
                                <td class="product-name">{{$item->product->name}}</td>
                                <td class="product-price">${{$item->product->price}}</td>
                                <td class="product-quantity">
                                    <input class="quantity-input"
                                        type="number"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        data-product-id="{{ $item->product_id }}"
                                        data-product-price="{{ $item->product->price }}">
                                    <div class="loading-spinner" style="display: none;">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </div>
                                </td>
                                <td class="product-total total-{{ $item->product_id }}">
                                    ${{ $item->quantity * $item->product->price }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Empty Cart Message -->
                    <div id="empty-cart-message" style="display: none; text-align: center; padding: 50px;">
                        <h3>Your cart is empty</h3>
                        <p>Add some products to your cart to see them here.</p>
                        <a href="{{ route('prods') }}" class="boxed-btn">Continue Shopping</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="total-section">
                    <table class="total-table">
                        <thead class="total-table-head">
                            <tr class="table-total-row">
                                <th>Total</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="total-data">
                                <td><strong>Subtotal: </strong></td>
                                <td class="subtotal-amount">
                                    ${{ $cart->sum(function($item) { return $item->quantity * $item->product->price; }) }}
                                </td>
                            </tr>
                            @php
                                $subtotal = $cart->sum(function($item) {
                                    return $item->quantity * $item->product->price;
                                });
                                $shippingTotal = $cart->sum(function($item) {
                                    return $item->product->shipping ?? 0;
                                });
                                $finalTotal = $subtotal + $shippingTotal;
                            @endphp
                            <tr class="total-data">
                                <td><strong>Shipping: </strong></td>
                                <td class="shipping-amount">${{ $shippingTotal }}</td>
                            </tr>
                            <tr class="total-data">
                                <td><strong>Total: </strong></td>
                                <td class="final-total">${{ $finalTotal }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart-buttons">
                        <a href="#" class="boxed-btn" onclick="location.reload()">Update Cart</a>
                        <a href="checkout.html" class="boxed-btn black">Check Out</a>
                    </div>
                </div>

                <div class="coupon-section">
                    <h3>Apply Coupon</h3>
                    <div class="coupon-form-wrap">
                        <form action="index.html">
                            <p><input type="text" placeholder="Coupon"></p>
                            <p><input type="submit" value="Apply"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // CSRF Token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let updateTimeout;
    let isUpdating = false;

    // Remove from cart function
    $(document).on('click', '.remove-from-cart-btn', function(e) {
        e.preventDefault();

        const button = $(this);
        const productId = button.data('product-id');
        const row = $('#cart-item-' + productId);

        console.log('Attempting to remove product ID:', productId);

        // Remove confirmation dialog - direct removal

        // Show loading state
        const originalIcon = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i>');
        button.prop('disabled', true);

        // AJAX request
        $.ajax({
            url: "{{ route('remove_from_cart') }}",
            type: 'POST',
            data: {
                productid: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            timeout: 10000,
            success: function(response) {
                console.log('Remove response:', response);

                if (response.success) {
                    // Animate and remove row (faster animation)
                    row.fadeOut(200, function() {
                        $(this).remove();

                        // Update totals
                        updateTotalAmounts();

                        // Check if cart is empty
                        if ($('.table-body-row').length === 0) {
                            $('.cart-table-wrap table').hide();
                            $('#empty-cart-message').show();
                            $('.total-section').hide();
                        }
                    });

                    showAlert(response.message, 'success');
                } else {
                    showAlert(response.message || 'Failed to remove item', 'error');
                    // Restore button
                    button.html(originalIcon);
                    button.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error Details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error
                });

                let errorMessage = 'Failed to remove item from cart';

                if (xhr.status === 404) {
                    errorMessage = 'Route not found. Please check your routes.';
                } else if (xhr.status === 422) {
                    errorMessage = 'Validation error occurred.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error occurred.';
                } else if (xhr.status === 419) {
                    errorMessage = 'Session expired. Please refresh the page.';
                } else if (status === 'timeout') {
                    errorMessage = 'Request timed out. Please try again.';
                }

                showAlert(errorMessage, 'error');

                // Restore button
                button.html(originalIcon);
                button.prop('disabled', false);
            }
        });
    });

    // Quantity change handlers
    $('.quantity-input').on('change', function() {
        const input = $(this);
        const productId = input.data('product-id');
        const newQuantity = parseInt(input.val());
        const productPrice = parseFloat(input.data('product-price'));

        if (!isUpdating) {
            updateQuantity(productId, newQuantity, productPrice, input);
        }
    });

    $('.quantity-input').on('input', function() {
        const input = $(this);
        const productId = input.data('product-id');
        const newQuantity = parseInt(input.val());
        const productPrice = parseFloat(input.data('product-price'));

        clearTimeout(updateTimeout);

        updateTimeout = setTimeout(function() {
            if (!isUpdating) {
                updateQuantity(productId, newQuantity, productPrice, input);
            }
        }, 500);
    });

    function updateQuantity(productId, quantity, productPrice, inputElement) {
        if (isUpdating) return;

        isUpdating = true;

        if (quantity < 1) {
            showAlert('Quantity must be greater than zero', 'error');
            inputElement.val(1);
            isUpdating = false;
            return;
        }

        inputElement.siblings('.loading-spinner').show();

        // Update UI immediately
        const newTotal = (quantity * productPrice).toFixed(2);
        $('.total-' + productId).text('$' + newTotal);
        updateTotalAmounts();

        $.ajax({
            url: "{{ route('change_quantity') }}",
            method: 'POST',
            data: {
                productid: productId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            timeout: 5000,
            success: function(response) {
                inputElement.siblings('.loading-spinner').hide();
                isUpdating = false;

                if (response.success) {
                    $('.total-' + productId).text('$' + response.new_total);
                    updateTotalAmounts();

                    inputElement.parent().addClass('updated');
                    setTimeout(function() {
                        inputElement.parent().removeClass('updated');
                    }, 600);

                    showAlert('Quantity updated', 'success', 1500);
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                inputElement.siblings('.loading-spinner').hide();
                isUpdating = false;
                showAlert('Failed to update quantity', 'error');
                console.error('Update quantity error:', error);
            }
        });
    }

    function updateTotalAmounts() {
        let subtotal = 0;

        $('.quantity-input').each(function() {
            const quantity = parseInt($(this).val());
            const price = parseFloat($(this).data('product-price'));
            subtotal += quantity * price;
        });

        const shipping = $('.quantity-input').length * 5; // $5 per item

        $('.subtotal-amount').text('$' + subtotal.toFixed(2));
        $('.shipping-amount').text('$' + shipping.toFixed(2));
        $('.final-total').text('$' + (subtotal + shipping).toFixed(2));
    }

    function showAlert(message, type, duration = 3000) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;

        $('#alert-container').html(alertHtml);

        setTimeout(function() {
            $('.alert').fadeOut();
        }, duration);
    }
});
</script>
@endsection

@section('css')
<style>
.quantity-input {
    width: 80px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
    transition: all 0.15s ease-in-out;
}

.quantity-input:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 3px rgba(0,123,255,.25);
}

.loading-spinner {
    margin-left: 10px;
    color: #007bff;
    font-size: 12px;
}

.updated {
    background-color: #d4edda !important;
    transition: background-color 0.3s ease;
}

.alert {
    margin-bottom: 5px;
    min-width: 250px;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.remove-from-cart-btn {
    color: #dc3545;
    font-size: 18px;
    transition: color 0.2s ease;
}

.remove-from-cart-btn:hover {
    color: #c82333;
    text-decoration: none;
}

.remove-from-cart-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.table-body-row {
    transition: opacity 0.4s ease;
}

#empty-cart-message {
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}
</style>
@endsection
