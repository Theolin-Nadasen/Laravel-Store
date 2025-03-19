@extends('layouts.main')

@section('content')
    
    <h1>Products</h1>

    <a href="{{route('product.create')}}" class="btn btn-info">CREATE</a>

    <div>
        <table class="table">
            <tr>
                <th>Image</th>
                <th>Images</th>
                <th>Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>

            @foreach ($products as $product)

            <tr>
                @if($product->image)
                <td>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="64">
                </td>

                @else
                <td>no Image</td>


                @endif

                @if($product->images)
                <td>
                    @foreach ($product->images as $item)
                        <img src="{{asset('storage/'. $item)}}" alt="Product Images" width="64">
                    @endforeach
                </td>

                @else
                <td>no images</td>

                @endif




                <td>{{$product->name}}</td>
                <td>{{$product->qty}}</td>
                <td>{{$product->price}}</td>
                <td>
                    <a href={{route('product.edit', ['product' => $product])}}>Edit</a>
                </td>
                <td>
                    <form action="{{route('product.destroy', ['product' => $product])}}" method="post">
                        @csrf
                        @method('delete')

                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
            
            @endforeach
        </table>
    </div>

@endsection