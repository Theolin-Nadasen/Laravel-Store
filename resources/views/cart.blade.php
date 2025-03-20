@extends('layouts.main')

@section('content')

<table class="table">

    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Remove</th>
    </tr>

    @foreach ($products as $product)

    <tr>
        <td>
            <img src="{{asset('storage/' . $product->image)}}" alt="Product Image" width="64">
        </td>

        <td>{{$product->name}}</td>
        <td>{{$product->price}}</td>

        <td>
            <form action="{{route('cart.remove', ['product' => $product])}}" method="post">
                @csrf
                @method('post')

                <input type="submit" value="X" class="btn btn-danger">
            </form>
        </td>
    </tr>
        
    @endforeach

    <tr>
        <td></td>
        <td>Total : R{{$total}}</td>
        <td>
            <a href="#" class="btn btn-success">Checkout</a>
        </td>
        <td></td>
    </tr>

</table>

@endsection