@extends('layout.master')
@section('content')
    <div class="checkout-section mt-150 mb-150">
        <div class="container">
            <div class="row">
                @foreach ($last_orders as $item)
                    <div class="col-lg-12">
                        <div class="checkout-accordion-wrap">
                            <div class="accordion mt-2 mb-2" id="accordionExample{{ $loop->index }}">
                                <div class="card single-accordion">
                                    <div class="card-header" id="heading{{ $loop->index }}">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#collapse{{ $loop->index }}" aria-expanded="false"
                                                aria-controls="collapse{{ $loop->index }}">
                                                Billing Address for Order #{{ $item->id }}
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapse{{ $loop->index }}" class="collapse"
                                        aria-labelledby="heading{{ $loop->index }}"
                                        data-parent="#accordionExample{{ $loop->index }}">
                                        <div class="card-body">
                                            <div class="billing-address-form">
                                                <form action="index.html">
                                                    <p><input disabled type="text" placeholder="Name"
                                                            value="{{ $item->name }}"></p>
                                                    <p><input disabled type="email" placeholder="Email"
                                                            value="{{ $item->email }}"></p>
                                                    <p><input disabled type="text" placeholder="Address"
                                                            value="{{ $item->address }}"></p>
                                                    <p><input disabled type="tel" placeholder="Phone"
                                                            value="{{ $item->phone }}"></p>
                                                    <p>
                                                        <textarea disabled name="bill" id="bill" cols="30" rows="10" placeholder="Say Something">{{ $item->note }}</textarea>
                                                    </p>
                                                </form>
                                            </div>
                                            <div class="row col-lg-12 col-md-12">
                                                <div class="col-lg-8 col-md-12">
                                                    <div class="cart-table-wrap">
                                                        <table class="cart-table">
                                                            <thead class="cart-table-head">
                                                                <tr class="table-head-row">
                                                                    <th class="product-image">Product Image</th>
                                                                    <th class="product-name">Name</th>
                                                                    <th class="product-price">Price</th>
                                                                    <th class="product-quantity">Quantity</th>
                                                                    <th class="product-total">Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($item->orderDetails as $i)
                                                                    <tr class="table-body-row"
                                                                        data-product-id="{{ $i->product_id }}"
                                                                        id="cart-item-{{ $i->product_id }}">
                                                                        <td class="product-image">
                                                                            <img style="max-height: 60px;min-height: 60px;min-width: 50px;max-width: 50px;width: 90%"
                                                                                src="{{ asset($i->product->image_path) }}"
                                                                                alt="">
                                                                        </td>
                                                                        <td class="product-name">
                                                                            <a
                                                                                href="/product/{{ $i->product->id }}">{{ $i->product->name }}</a>
                                                                        </td>
                                                                        <td class="product-price">${{ $i->product->price }}
                                                                        </td>
                                                                        <td class="product-quantity">
                                                                            <input disabled class="quantity-input"
                                                                                type="number" value="{{ $i->quantity }}"
                                                                                min="1"
                                                                                data-product-id="{{ $i->product_id }}"
                                                                                data-product-price="{{ $i->product->price }}">
                                                                            <div class="loading-spinner"
                                                                                style="display: none;">
                                                                                <i class="fas fa-spinner fa-spin"></i>
                                                                            </div>
                                                                        </td>
                                                                        <td
                                                                            class="product-total total-{{ $i->product_id }}">
                                                                            ${{ $i->quantity * $i->product->price }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @php
                                                    $subtotal = $item->orderDetails->sum(function ($detail) {
                                                        return $detail->quantity * $detail->product->price;
                                                    });
                                                    $shippingTotal = $item->orderDetails->sum(function ($detail) {
                                                        return $detail->product->shipping ?? 0;
                                                    });
                                                    $finalTotal = $subtotal + $shippingTotal;
                                                @endphp
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
                                                                    <td class="subtotal-amount">${{ $subtotal }}</td>
                                                                </tr>
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
                                                                            <div class="alert alert-danger">
                                                                                {{ session('error') }}</div>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> <!-- row -->
                                        </div> <!-- card-body -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty Cart Message -->
                    <div id="empty-cart-message" style="display: none; text-align: center; padding: 50px;">
                        <h3>Your cart is empty</h3>
                        <p>Add some products to your cart to see them here.</p>
                        <a href="{{ route('prods') }}" class="boxed-btn">Continue Shopping</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
