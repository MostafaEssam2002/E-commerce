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
						<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
						<a href="/removeproduct/{{$item->id}}" class="cart-btn" style="background-color: red"><i class="fas fa-shopping-cart"></i> Remove Product</a>
                        {{-- <br> --}}
                        <br>
                        <br>
						<a href="/editproduct/{{$item->id}}" class="cart-btn" style="background-color: blue"><i class="fas fa-shopping-cart"></i> Edit Product</a>
					</div>
				</div>
                @endforeach
			</div>
		</div>
	</div>
@endsection
