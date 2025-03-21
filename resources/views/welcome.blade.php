
@extends('layouts.main')

@section('content')

<div class="d-flex flex-column align-items-center mt-5">

    <h1>Latest Products</h1>

    @foreach ($products as $product)

    <div class="container-fluid">

    
    <div class="row mt-5">
        <div class="col-10 col-md-4">
            <img src="{{asset('storage/' . $product->image)}}" alt="Product Image" width="256">
        </div>

        <div class="col-10 col-md-6">
            <p>{{$product->description}}</p>

            @if(Auth::user())
            <a href="{{route('addtocart', ['product' => $product])}}" class="btn btn-info">Add To Cart (R{{$product->price}})</a>
            @endif
        </div>
    </div>

    </div>
        
    @endforeach

</div>
    
@endsection