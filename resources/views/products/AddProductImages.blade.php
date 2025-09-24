@extends('layout.master')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/css/product-gallery.css')}}">
@endsection
@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <h1 class="page-title">Product Image Gallery</h1>

        <!-- Upload Section -->
        <div class="upload-section">
            <h2 class="section-title">
                <i class="fas fa-cloud-upload-alt me-2"></i>
                Upload New Image
            </h2>
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="upload-form">
                        <form action="{{route('add_product_image',['product_id'=>$product->id])}}" enctype="multipart/form-data" method="post" id="uploadForm">
                            @csrf
                            <div class="file-input-wrapper">
                                <input type="file" name="image_path" id="imageInput" accept="image/*" required>
                                <label for="imageInput" class="file-input-label">
                                    <i class="fas fa-images"></i>
                                    <span class="file-text">Choose Image File</span>
                                    <small class="file-name" id="fileName"></small>
                                </label>
                            </div>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-upload me-2"></i>
                                Upload Image
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Images Gallery -->
        <div class="images-section">
            @if($product->productImages && count($product->productImages) > 0)
                <div class="images-grid">
                    @foreach ($product->productImages as $item)
                        <div class="image-card">
                            <img src="{{ asset($item->image_path) }}" alt="Product Image" loading="lazy">
                            <a href="{{route('removeproductimage',[$item->id])}}" class="delete-btn" onclick="return confirm('Are you sure you want to delete this image?')">
                                <i class="fas fa-trash me-1"></i>
                                Delete
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-images"></i>
                    <h3>No Images Yet</h3>
                    <p>Upload your first product image using the form above.</p>
                </div>
            @endif
        </div>
    </div>
</div>
<script src="{{asset('assets/js/product-gallery.js')}}"></script>
@endsection
