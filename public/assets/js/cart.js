$(document).ready(function() {
    let updateTimeout;
    let isUpdating = false;
    // دالة محلية لتحديث العدد في أيقونة السلة
    function updateCartBadgeLocal() {
        const cartCount = $('.table-body-row').length;
        const cartBadge = $('#cart_count');
        if (cartBadge.length) {
            cartBadge.text(cartCount);
            // إضافة تأثير التحديث
            cartBadge.addClass('updated');
            setTimeout(function() {
                cartBadge.removeClass('updated');
            }, 600);
            // للأرقام الكبيرة
            if (cartCount > 99) {
                cartBadge.addClass('large-number');
                cartBadge.text('99+');
            } else {
                cartBadge.removeClass('large-number');
            }
            // إخفاء الشارة إذا كان العدد صفر
            if (cartCount === 0) {
                cartBadge.attr('data-count', '0');
            } else {
                cartBadge.removeAttr('data-count');
            }
        }
    }
    // Remove from cart function (محدث)
    $(document).on('click', '.remove-from-cart-btn', function(e) {
        e.preventDefault();
        const button = $(this);
        const productId = button.data('product-id');
        const row = $('#cart-item-' + productId);
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
                if (response.success) {
                    // Animate and remove row
                    row.fadeOut(200, function() {
                        $(this).remove();
                        // Update totals
                        updateTotalAmounts();
                        // تحديث عدد السلة
                        updateCartBadgeLocal();
                        // Check if cart is empty
                        if ($('.table-body-row').length === 0) {
                            $('.cart-table-wrap table').hide();
                            $('#empty-cart-message').show();
                            $('.total-section').hide();
                        }
                    });
                } else {
                    // Restore button
                    button.html(originalIcon);
                    button.prop('disabled', false);
                }
            },
            error: function(xhr, status, error) {
                console.error('Remove from cart error:', error);
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
                }
            },
            error: function(xhr, status, error) {
                inputElement.siblings('.loading-spinner').hide();
                isUpdating = false;
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
    // تحديث العدد عند تحميل الصفحة
    updateCartBadgeLocal();
});
