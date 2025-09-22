hellow world
<br>
{{$product->name}}
<br>
@foreach($product->productImages as $image)
<img src="{{ asset($image->image_path) }}" alt="">
<br>
@endforeach


{{$product->price}}
<br>
{{$product->quantity}}
<br>
<br>
{{$product->id}}
<br>
