@extends('layout.master')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    /* Style زر Add Product */
    .btn-add-product {
        background-color: #007bff; /* اللون الأزرق */
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 5px; /* مسافة بين الايقونه والنص */
        font-weight: bold;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-add-product i {
        font-size: 16px;
    }

    .btn-add-product:hover {
        background-color: #0069d9;
        color: white;
    }

    /* Container لزر Add Product */
    .add-product-container {
        margin-bottom: 20px; /* مسافة أسفل الزر قبل الجدول */
    }
</style>
@endsection

@section("content")
<div class="container mt-5 mb-5">
    <div class="add-product-container">
        <a href="{{ route('addproduct') }}" class="btn-add-product">
            <i class="fas fa-plus"></i> Add product
        </a>
    </div>

    <table id="mytable" class="display">
        <thead>
            <tr>
                <th style="text-align: center">ID</th>
                <th style="text-align: center">Name</th>
                <th style="text-align: center">Price</th>
                <th style="text-align: center">Quantity</th>
                <th style="text-align: center">Image</th>
                <th style="text-align: center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->price}}</td>
                <td>{{$item->quantity}}</td>
                <td><img style="min-width: 50px; max-width: 50px;" src="{{$item->image_path}}" alt="Error"></td>
                <td style="text-align: center">
                    <a href="{{route('editproduct',['productid'=>$item->id])}}" class="btn btn-success">
                        <i class="fas fa-pencil-alt"></i> Edit product
                    </a>
                    <a href="{{route('removeproduct',['productid'=>$item->id])}}" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete product
                    </a>
                    <a href="{{route('AddProductImages',['productid'=>$item->id])}}" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Add images
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#mytable').DataTable();
    });
</script>
@endsection
