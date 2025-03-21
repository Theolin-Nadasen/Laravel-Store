@extends('layouts.main')

@section('content')

<div class="d-flex align-items-center flex-column mt-5">

  {{--Carousel for displaying images--}}
    <div id="carouselExampleAutoplaying" class="carousel slide w-50" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="{{asset('storage/' . $product->image)}}" class="d-block w-100" alt="Main Product Image">
          </div>

          @if($product->images)
          @foreach ($product->images as $image)

          
            <div class="carousel-item">
                <img src="{{asset('storage/' . $image)}}" class="d-block w-100" alt="Product Image">
            </div>
          
          @endforeach
          @endif

        </div>

        {{--left and right buttons for carousel--}}
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
    </div>



    <h1 class="mt-2">{{$product->name}}</h1>

    {{--must be done like this or it will ignore the newline chars and show everything on one line--}}
    <p>{!! nl2br(e($product->longdescription)) !!}</p>

    {{--Shows buy button if user is logged in--}}
    @if(Auth::user())
    <a href="{{route('addtocart', ['product' => $product])}}" class="btn btn-info">Add To Cart (R{{$product->price}})</a>
    @endif
    
</div>
    
@endsection