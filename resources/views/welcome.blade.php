{{-- <h1>{{$categories}}</h1> --}}
{{-- @foreach ($categories as $item)
    <h1>{{$item->name}}</h1>
@endforeac?h --}}
@extends('layout.master')
@section('content')
<div class="product-section mt-150 mb-150">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2 text-center">
					<div class="section-title">
						<h3><span class="orange-text">Our</span> Products</h3>
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
					</div>
				</div>
			</div>

			<div class="row">
                @foreach ($categories as $item)
                    <div class="col-lg-4 col-md-6 text-center">
                        <div class="single-product-item">
                            <div class="product-image">
                                <a href="{{route("prods",["catid"=>$item->id])}}"><img  src="{{url($item->image_path)}}" alt=""></a>
                            </div>
                            <h3>{{$item->name}}</h3>
                            <p class="product-price"><span>Per Kg</span>  </p>
                            <a href="cart.html" class="cart-btn"><i class="fas fa-shopping-cart"></i> Add to Cart</a>
                        </div>
                    </div>
                @endforeach
			</div>
            {{-- {{$categories->links()}} --}}
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="pagination-wrap">
                            <ul>
                                {{-- Prev --}}
                                @if($categories->onFirstPage())
                                    <li><span>Prev</span></li>
                                @else
                                    <li><a href="{{ $categories->previousPageUrl() }}">Prev</a></li>
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
                                    <li><a href="{{ $categories->nextPageUrl() }}">Next</a></li>
                                @else
                                    <li><span>Next</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
    <style>
        .pagination{
        }
    </style>
@endsection
{{-- @section('xyz')
<h1>xyz section</h1>
@endsection --}}
