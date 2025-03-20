@extends('layouts.main')

@section('content')

<table class="table">

    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
    </tr>

    @foreach ($products as $product)

    <tr>
        <td>
            <img src="{{asset('storage/' . $product->image)}}" alt="Product Image" width="64">
        </td>

        <td>{{$product->name}}</td>
        <td>{{$product->price}}</td>
    </tr>
        
    @endforeach

</table>

@endsection