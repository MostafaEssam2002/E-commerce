@foreach ($products_in_cart as $item)
    <p>{{ $item->product->name }} - ${{ $item->product->price }} - Quantity: {{ $item->quantity }}</p>

@endforeach
