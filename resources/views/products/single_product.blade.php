@extends('layout.master')
@section('content')
<div class="single-product mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="single-product-img">
                    @if ($product->productImages && count($product->productImages) > 0)
                        <div class="product-slider-container">
                            <div class="product-slider">
                                @foreach($product->productImages as $image)
                                <div class="slider-item">
                                    <img src="{{ asset($image->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                                </div>
                                @endforeach
                                {{-- إضافة الصورة الأساسية أيضاً --}}
                                @if($product->image_path)
                                <div class="slider-item">
                                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                                </div>
                            </div>
                        @endif
                            {{-- أزرار التنقل --}}
                            <button class="slider-btn slider-prev" type="button" aria-label="Previous image">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="slider-btn slider-next" type="button" aria-label="Next image">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            {{-- النقاط السفلية --}}
                        </div>
                    @else
                        {{-- في حالة عدم وجود صور إضافية --}}
                        @if($product->image_path)
                        <div class="single-image">
                            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                        </div>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-md-7">
                <div class="single-product-content">
                    <h3>{{$product->name}}</h3>
                    <p class="single-product-pricing"><span>Per Kg</span> ${{$product->price}}</p>
                    <p>{{$product->description}}</p>
                    <div class="single-product-form">
                        <form action="index.html">
                            <input type="number" placeholder="0" min="1" max="100">
                        </form>
                        <button class="cart-btn add-to-cart-btn" id="Add_to_Cart" data-product-id="{{$product->id}}" type="button">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    <div class="loading-spinner" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                        <p><strong>Categories: </strong>{{$product->category->name}}</p>
                    </div>
                    <h4>Share:</h4>
                    <ul class="product-share">
                        <li><a href="#" rel="noopener"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" rel="noopener"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" rel="noopener"><i class="fab fa-google-plus-g"></i></a></li>
                        <li><a href="#" rel="noopener"><i class="fab fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="more-products mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3><span class="orange-text">Related</span> Products</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($related_products as $item)
                <div class="col-lg-4 col-md-6 text-center">
                    <div class="single-product-item">
                        <div class="product-image">
                            <a href="{{route('ShowProduct',[$item->id])}}">
                                <img src="{{asset($item->image_path)}}" alt="{{ $item->name }}" loading="lazy">
                            </a>
                        </div>
                        <h3>{{$item->name}}</h3>
                        <p class="product-price"><span>Per Kg</span> {{$item->price}}$</p>
                        <button class="cart-btn add-to-cart-btn" id="Add_to_Cart" data-product-id="{{$item->id}}" type="button">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection


