@extends('layout.master')
@section("content")
<div class="contact-from-section mt-150 mb-150">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <div class="section-title">
                <h3><span class="orange-text">EDIT</span> Products</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-5 mb-lg-0">
                <div id="form_status"></div>
                <div class="contact-form">
                    <form method="POST" action="/storeproduct" enctype="multipart/form-data" id="fruitkha-contact"  >

                        {{-- <form method="POST" action="edit_product/{{$product->id}}" id="fruitkha-contact"  > --}}
                        @csrf
                        <p>
                            <input type="text" hidden placeholder="id" name="id" id="id" value="{{$product->id}}" style="width: 100%">
                            <span class="text-danger">
                            @error('name')
                                {{$message}}
                            @enderror
                            </span>
                        </p>
                        <p>
                            <input type="text" placeholder="Name" name="name" id="name" value="{{$product->name}}" style="width: 100%">
                            <span class="text-danger">
                            @error('name')
                                {{$message}}
                            @enderror
                            </span>
                        </p>
                        <p style="display: flex">
                            <input type="number" style="width: 50% ;padding: 15px;border: 1px solid #ddd;border-radius: 3px;" value="{{$product->price}}" class="mr-4" placeholder="Price" name="price" id="price">
                            <span class="text-danger">
                                @error('price')
                                    {{$message}}
                                @enderror
                            </span>
                            <input type="number" style="width: 50% ;padding: 15px;border: 1px solid #ddd;border-radius: 3px;" value="{{$product->quantity}}" placeholder="Quantity" name="quantity" id="quantity">
                            <span class="text-danger">
                                @error('quantity')
                                    {{$message}}
                                @enderror
                            </span>
                            {{-- <input type="text" placeholder="Subject" name="subject" id="subject"> --}}
                        </p>
                        <p>
                            <textarea name="description" id="description" cols="30" rows="10" placeholder="description">"{{$product->description}}"</textarea>
                            <span class="text-danger">
                                @error('description')
                                    {{$message}}
                                @enderror
                            </span>
                        </p>
                        <p>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach ($categories as $item)
                                    @if ($item->id==$product->category_id)
                                        <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                        @else
                                        <option value="{{ $item->id }}" >{{ $item->name }}</option>
                                    @endif
                                        {{ $item->name }}
                                @endforeach
                            </select>
                        </p>
                        <span>
                            @error('category_id')
                                {{$message}}
                            @enderror
                        </span>
                        <p>
                            <img src="{{asset($product->image_path)}}"  alt="Not Found" style="width: 200px;height: 200px;">
                        </p>
                        {{-- <input type="text" name="image_path" hidden  value="{{$product->image_path}}"> --}}
                        <p>
                            <input type="file" class="form-control" name="image_path" value="{{old("image_path")}}" id="">
                        </p>
                        <span>
                            @error("image_path")
                                {{$message}}
                            @enderror
                        </span>
                        <p><input type="submit" value="Submit"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
