@extends('layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/cart.css')}}">
@endsection
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
                                <td class="product-name"><a href="/product/{{$item->product->id}}">{{$item->product->name}}</a></td>
                                <td class="product-price">${{$item->product->price}}</td>
                                <td class="product-quantity">
                                    <input class="quantity-input" type="number" value="{{ $item->quantity }}" min="1" data-product-id="{{ $item->product_id }}" data-product-price="{{ $item->product->price }}">
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
                                <td class="final-total">
                                    @if (session()->has('finalTotalAfterCopon'))
                                        <del>${{ $finalTotal }}</del> ${{ session('finalTotalAfterCopon') }}
                                    @else
                                        ${{ $finalTotal }}
                                    @endif

                                    @if(session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart-buttons">
                        <a href="#" class="boxed-btn" onclick="location.reload()">Update Cart</a>
                        <a href="{{route("completeorder")}}" class="boxed-btn black">Check Out</a>
                    </div>
                </div>
                <div class="coupon-section">
                    <h3>Apply Coupon</h3>
                    <div class="coupon-form-wrap">
                        <form method="POST" action="{{route("copon")}}">
                            @csrf
                        <input type="hidden" name="total" value="{{$finalTotal}}">
                        <p><input type="text" name="copon" placeholder="Coupon"></p>
                        <p><input type="submit" value="Apply"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/js/cart.js')}}"></script>
@endsection

@section('css')
@endsection
