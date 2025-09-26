@extends('layout.master')
@section('content')

<div class="product-section mt-150 mb-150">
		<div class="container">

			<div class="row">
                <div class="col-md-12">
                    <div class="product-filters">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            @if(session("locale") == "ar")
                                {{-- <li data-filter="._0">الكل</li> --}}
                                @foreach ($categories as $category)
                                    <li data-filter="._{{$category->id}}">{{$category->name_AR}}</li>
                                @endforeach
                                {{-- @endforeach --}}
                                @elseif(session("locale") == "en")
                                @foreach ($categories as $category)
                                    <li data-filter="._{{$category->id}}">{{$category->name}}</li>
                                @endforeach
                                {{-- <li data-filter="._0">All</li> --}}
                            @endif

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
                        @if(session("locale") == "ar")
    	    				<h3>{{$product->name_ar}}</h3>
                        @elseif(session("locale") == "en")
    	    				<h3>{{$product->name}}</h3>
                        @endif
                            <p class="product-price">{{$product->price}}$</p>
                        <span>{{ trans('string.quantity') }}: {{$product->quantity}}</span>
                        <br><br>
                        <!-- Fixed: Changed $item->id to $product->id -->
                        <button class="cart-btn add-to-cart-btn" id="Add_to_Cart"   data-product-id="{{$product->id}}">
                            <i class="fas fa-shopping-cart"></i> {{ trans('string.Add to Cart') }}
                        </button>
					</div>
				</div>
                @endforeach
			</div>
			<div class="row">
				<div class="col-lg-12 text-center">
					<div class="pagination-wrap">
						<ul>
							<li><a href="#">{{ trans('string.previous') }}</a></li>
							<li><a href="#">1</a></li>
							<li><a class="active" href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">{{ trans('string.next') }}</a></li>
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
<script src="{{asset('assets/js/Category.js')}}"></script>


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
