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
                <th style="text-align: center">{{ trans('string.id') }}</th>
                <th style="text-align: center">{{ trans('string.name') }}</th>
                <th style="text-align: center">{{ trans('string.price') }}</th>
                <th style="text-align: center">{{ trans('string.quantity') }}</th>
                <th style="text-align: center">{{ trans('string.image') }}</th>
                <th style="text-align: center">{{ trans('string.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
            <tr>
                <td>{{$item->id}}</td>
                <td>
                    @if(session('locale')=="en")
                        {{$item->name}}
                    @elseif(session('locale')=="ar")
                        {{$item->name_ar}}
                    @else
                        {{$item->name}}
                    @endif
                </td>
                <td>{{$item->price}}</td>
                <td>{{$item->quantity}}</td>
                <td><img style="min-width: 50px; max-width: 50px;" src="{{$item->image_path}}" alt="Error"></td>
                <td style="text-align: center">
                    <a href="{{route('editproduct',['productid'=>$item->id])}}" class="btn btn-success">
                        <i class="fas fa-pencil-alt"></i> {{ trans('string.Edit product') }}
                    </a>
                    <a href="{{route('removeproduct',['productid'=>$item->id])}}" class="btn btn-danger">
                        <i class="fas fa-trash"></i> {{ trans('string.Delete product') }}
                    </a>
                    <a href="{{route('AddProductImages',['productid'=>$item->id])}}" class="btn btn-primary">
                        <i class="fas fa-image"></i> {{ trans('string.Add images') }}
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
        $('#mytable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/{{ session('locale') == 'ar' ? 'Arabic' : 'English' }}.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [4, 5] }, // disable sorting for image and action columns
                { "searchable": false, "targets": [4, 5] }  // disable search for image and action columns
            ]
        });
    });
</script>
@endsection
