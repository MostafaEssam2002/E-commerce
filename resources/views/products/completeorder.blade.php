@extends('layout.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
@endsection
@section('content')
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="checkout-accordion-wrap">
                        <div class="accordion" id="accordionExample">
                            <div class="card single-accordion">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Billing Address
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="billing-address-form">
                                            <form action="/storeorder" method="POST">
                                                @csrf
                                                <p><input type="text" required id="name" name="name" placeholder="Name"></p>
                                                <p><input type="email" required id="email" name="email" placeholder="Email"></p>
                                                <p><input type="text" required id="address" name="address" placeholder="Address"></p>
                                                <p><input type="tel" required id="phone" name="phone" placeholder="Phone"></p>
                                                <p><textarea name="note" id="note" cols="30" rows="10" placeholder="Say Something"></textarea></p>
                                                {{-- <input type="submit" value="Place Order" class="btn btn-primary"> --}}
                                            {{-- </form> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card single-accordion">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                            data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Shipping Address
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="shipping-address-form">
                                            <p>Your shipping address form is here.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card single-accordion">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                            data-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            Card Details
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="card-details">
                                            <div class="col-lg-12 col-md-12">
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
                                                            @foreach ($cart as $item)
                                                                <tr class="table-body-row"
                                                                    data-product-id="{{ $item->product_id }}"
                                                                    id="cart-item-{{ $item->product_id }}">
                                                                    <td class="product-remove">
                                                                        <a href="#" class="remove-from-cart-btn"
                                                                            data-product-id="{{ $item->product_id }}">
                                                                            <i class="far fa-window-close"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td class="product-image">
                                                                        <img style="max-height: 60px;min-height: 60px;min-width: 50px;max-width: 50px;width: 90%"
                                                                            src="{{ asset($item->product->image_path) }}"
                                                                            alt="">
                                                                    </td>
                                                                    <td class="product-name"><a
                                                                            href="/product/{{ $item->product->id }}">{{ $item->product->name }}</a>
                                                                    </td>
                                                                    <td class="product-price">${{ $item->product->price }}
                                                                    </td>
                                                                    <td class="product-quantity">
                                                                        <input class="quantity-input" type="number"
                                                                            value="{{ $item->quantity }}" min="1"
                                                                            data-product-id="{{ $item->product_id }}"
                                                                            data-product-price="{{ $item->product->price }}">
                                                                        <div class="loading-spinner"
                                                                            style="display: none;">
                                                                            <i class="fas fa-spinner fa-spin"></i>
                                                                        </div>
                                                                    </td>
                                                                    <td
                                                                        class="product-total total-{{ $item->product_id }}">
                                                                        ${{ $item->quantity * $item->product->price }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                    <!-- Empty Cart Message -->
                                                    <div id="empty-cart-message"
                                                        style="display: none; text-align: center; padding: 50px;">
                                                        <h3>Your cart is empty</h3>
                                                        <p>Add some products to your cart to see them here.</p>
                                                        <a href="{{ route('prods') }}" class="boxed-btn">Continue
                                                            Shopping</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="total-section col-lg-12 col-md-12 mt-4">

                                                <div class="cart-buttons">
                                                    <a href="{{ route('cart') }}" class="boxed-btn">Update Cart</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="order-details-wrap">
                        <table class="order-details">
                            <thead>
                                <tr>
                                    <th>Your order Details</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody class="order-details-body">
                                <tr>
                                    <td>Product</td>
                                    <td>Total</td>
                                </tr>
                                @foreach ($cart as $item)
                                    <tr>
                                        <td>{{$item->product->name}}</td>
                                        <td>${{$item->product->price * $item->quantity}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tbody class="checkout-details">
                                <tr class="total-data">
                                    <td><strong>Subtotal: </strong></td>
                                    <td class="subtotal-amount">
                                        ${{ $cart->sum(function ($item) {return $item->quantity * $item->product->price;}) }}
                                    </td>
                                </tr>
                                @php
                                    $subtotal = $cart->sum(function ($item) {
                                        return $item->quantity * $item->product->price;
                                    });
                                    $shippingTotal = $cart->sum(function ($item) {
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
                                    <td class="final-total">
                                        @if (session()->has('finalTotalAfterCopon'))
                                            <del>${{ $finalTotal }}</del>
                                            ${{ session('finalTotalAfterCopon') }}
                                        @else
                                            ${{ $finalTotal }}
                                        @endif

                                        @if (session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <input type="submit"  class="boxed-btn mt-5" value="Place Order">
            {{-- <a href="#" class="boxed-btn mt-5"></a> --}}
            </form>
        </div>
    </div>
@section('js')
    <script src="{{ asset('assets/js/cart.js') }}"></script>
@endsection
@endsection
