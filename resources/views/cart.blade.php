@extends('layouts.main')

@section('content')

<div class="table-responsive">

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
                <form action="{{ route('payment.initiate') }}" method="post">
                    @csrf
                    @method('post')

                    <input type="hidden" name="amount" value="{{$total}}">
                    
                    <button type="submit" class="btn btn-success">Checkout</button>
                </form>
            </td>
            <td></td>
        </tr>

    </table>
</div>

@endsection