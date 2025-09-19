@extends('layout.master')
@section('content')

<div class="product-section mt-150 mb-150">
		<div class="container">

			<div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            {{-- {{$categories}} --}}
                            @foreach ($categories as $category)
                                <li data-filter="._{{$category->id}}">{{$category->name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

			<div class="row product-lists" style="position: relative; height: 1147.4px;">
                @foreach ($products as $product)
                {{-- <p>{{ optional($product->category)->name ?? 'No Category' }}</p> --}}
				<div class="col-lg-4 col-md-6 text-center  _{{$product->category_id}}" style="position: absolute; left: 0px; top: 0px;">
                    <div class="single-product-item"  >
						<div class="product-image" >
							<a href="single-product.html"><img  src="{{asset($product->image_path)}}" alt=""></a>
						</div>
						<h3>{{$product->name}}</h3>
                        {{-- @if ($product->category_id==6)
                            <span>Per Kg</span>
                        @endif --}}
						<p class="product-price">{{$product->price}}</p>
                        <span>Quantity</span>
						<p class="product-price">{{$product->quantity}}</p>
						<a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
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
