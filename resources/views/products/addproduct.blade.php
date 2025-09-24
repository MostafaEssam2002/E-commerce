@extends('layout.master')
@section("content")
<div class="contact-from-section mt-150 mb-150">
    <div class="row">
        <div class="col-lg-8 offset-lg-2 text-center">
            <div class="section-title">
                <h3><span class="orange-text">ADD</span> Products</h3>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 mb-5 mb-lg-0">
                <div id="form_status"></div>
                <div class="contact-form">
                    {{-- @if (auth()->check()) --}}
                    <form method="POST" enctype="multipart/form-data" action="/storeproduct" id="fruitkha-contact"  >
                        @csrf
                        <p>
                        </p>
                        <p>
                            <input type="text" placeholder="Name" name="name" id="name" value="{{old("name")}}" style="width: 100%">
                            <span class="text-danger">
                                @error('name')
                                {{$message}}
                                @enderror
                            </span>
                        </p>
                        <p style="display: flex">
                            <input type="number" style="width: 50% ;padding: 15px;border: 1px solid #ddd;border-radius: 3px;" value="{{old("price")}}" class="mr-4" placeholder="Price" name="price" id="price">
                            <span class="text-danger">
                                @error('price')
                                {{$message}}
                                @enderror
                            </span>
                            <input type="number" style="width: 50% ;padding: 15px;border: 1px solid #ddd;border-radius: 3px;" value="{{old("price")}}" placeholder="Quantity" name="quantity" id="quantity">
                            <span class="text-danger">
                                @error('quantity')
                                {{$message}}
                                @enderror
                            </span>
                            {{-- <input type="text" placeholder="Subject" name="subject" id="subject"> --}}
                        </p>
                        <p>
                            <textarea name="description" id="description" cols="30" rows="10" placeholder="description">{{old("description")}}</textarea>
                            <span class="text-danger">
                                @error('description')
                                {{$message}}
                                @enderror
                            </span>
                        </p>
                        <p>
                            <input type="number" placeholder="shipping" name="shipping" id="shipping" value="{{old("shipping")}}" style="width: 100%">
                            <span class="text-danger">
                                @error('shipping')
                                {{$message}}
                                @enderror
                            </span>
                        </p>
                        <p>
                            <select name="category_id" id="category_id" class="form-control">
                                {{old("category_id")}}
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </p>
                        <span>
                            @error('category_id')
                            {{$message}}
                            @enderror
                        </span>
                        <p>
                            <input type="file" class="form-control" name="image_path" value="{{$item->image_path}}" id="">
                        </p>
                        {{-- <input type="hidden" name="token" value="FsWga4&amp;@f6aw"> --}}
                        <p><input type="submit" value="Submit"></p>
                    </form>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
