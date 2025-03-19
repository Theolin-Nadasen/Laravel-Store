@extends('layouts.main')

@section('content')
<div class="d-flex justify-content-center">
    <h1>Catalogue</h1>
</div>

    <div class="d-flex justify-content-center">

        <div class="row justify-content-between">
            @foreach ($products as $product)
            
            <div class="card col-s-6 col-md-3 m-2">
                <img src="{{asset('storage/' . $product->image)}}" alt="Product image" class="card-img-top mt-2 rounded-3" width="100">

                <hr>

                <div class="card-body">
                    <h5 class="card-title">{{$product->name}}</h5>
                    <p class="card-text">This is where the description goes</p>
                    <a href="{{route('view', ['product' => $product])}}" class="btn btn-info">View Product</a>
                </div>
            </div>
                
            @endforeach
        </div>
    </div>


    
@endsection