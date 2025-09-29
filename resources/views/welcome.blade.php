@extends('layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/welcome.css')}}">
@endsection
@section('content')
<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">
						<h3><span class="orange-text">{{trans('string.Our Products') }}</h3>
						<p>{{ trans('string.Lorem') }}</p>
					</div>
				</div>
			</div>
			<div class="row">
                @foreach ($categories as $item)
                    <div class="col-lg-4 col-md-6 text-center">
                        <div class="single-product-item">
                            <div class="product-image">
                                <a href="{{route("ShowProduct",["id"=>$item->id])}}"><img  src="{{url($item->image_path)}}" alt=""></a>
                            </div>
                            @if(session("locale")=="ar")
                                <h3>{{$item->name_ar}}</h3>
                            @elseif(session("locale")=="en")
                                <h3>{{$item->name}}</h3>
                            @else
                                <h3>{{$item->name}}</h3>
                            @endif
                            <p class="product-price"><span>{{ $item->price }}</span></p>
                            <p class="product-price"><span>{{ trans('string.Per Kg') }}</span></p>
                            <!-- Updated Add to Cart Button with AJAX -->
                            <button class="cart-btn add-to-cart-btn" id="Add_to_Cart"   data-product-id="{{$item->id}}">
                                <i class="fas fa-shopping-cart"></i> {{trans('string.Add to Cart')}}
                            </button>
                        </div>
                    </div>
                @endforeach
			</div>

        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="pagination-wrap">
                            <ul>
                                {{-- Prev --}}
                                @if($categories->onFirstPage())
                                    <li><span>{{ trans('string.previous') }}</span></li>
                                @else
                                    <li><a href="{{ $categories->previousPageUrl() }}">{{ trans('string.previous') }}</a></li>
                                @endif
                                {{-- Page Numbers --}}
                                @foreach($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                                    @if($page == $categories->currentPage())
                                        <li><a class="active" href="{{ $url }}">{{ $page }}</a></li>
                                    @else
                                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                                {{-- Next --}}
                                @if($categories->hasMorePages())
                                    <li><a href="{{ $categories->nextPageUrl() }}">{{ trans('string.next') }}</a></li>
                                @else
                                    <li><span>{{ trans('string.next') }}</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
@endsection
@section('js')
<script src="{{asset('assets/js/product-gallery.js')}}"></script>
@endsection
