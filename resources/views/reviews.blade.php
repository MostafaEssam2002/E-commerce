@extends('layout.master')
@section('content')
    <div class="contact-from-section mt-150 mb-150">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">
                    <h3><span class="orange-text">{{ trans('string.add') }}</span> {{ trans('string.Review') }}</h3>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mb-5 mb-lg-0">
                    <div id="form_status"></div>
                    <div class="contact-form">
                        <form method="POST" action="{{route("storereview")}}" id="fruitkha-contact">
                            @csrf()
                            <p>
                                <input type="text" placeholder="{{trans("string.name")}}" name="name" id="name"
                                    value="{{ old('name') }}" style="width: 100%">
                                <span class="text-danger">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p>
                                <input type="text" placeholder="{{trans("string.phone")}}" name="phone" id="phone"
                                    value="{{ old('phone') }}" style="width: 100%">
                                <span class="text-danger">
                                    @error('phone')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p style="display: flex">
                                <input type="email" style="width: 50% ;padding: 15px;border: 1px solid #ddd;border-radius: 3px;" value="{{ old('email') }}" class="mr-4" placeholder="{{trans("string.email")}}" name="email"id="email">
                                <span class="text-danger">@error('email') {{ $message }} @enderror </span>
                                <input type="text" style="width: 50% ;padding: 15px;border: 1px solid #ddd;border-radius: 3px;" value="{{ old('subject') }}" placeholder="{{trans("string.Subject")}}" name="subject" id="subject">
                                <span class="text-danger">@error('subject'){{ $message }} @enderror </span>
                            </p>
                            <p>
                                <textarea name="content" id="content" cols="30" rows="10" placeholder="{{trans("string.content")}}">{{ old('content') }}</textarea>
                                <span class="text-danger">
                                    @error('content')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </p>
                            <p><input type="submit" value="{{trans("string.submit")}}"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- testimonail-section -->
    <div class="testimonail-section mt-80 mb-150">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1 text-center">
                    <div class="testimonial-sliders">
                        {{-- @for ($i = 0; $i < 3; $i++) --}}
                        @foreach ($reviews as $item)
                            <div class="single-testimonial-slider">
                                <div class="client-avater">
                                    <img src="{{ asset($item->image_path) }}" alt="">
                                </div>
                                <div class="client-meta">
                                    <h3>{{ $item->name }} <span>{{ $item->subject }}</span></h3>
                                    <p class="testimonial-body">
                                        {{ $item->message }}
                                    </p>
                                    <div class="last-icon">
                                        <i class="fas fa-quote-right"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end testimonail-section -->

    <!-- logo carousel -->
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
    <!-- end logo carousel -->
@endsection
