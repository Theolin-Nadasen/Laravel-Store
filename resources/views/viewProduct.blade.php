@extends('layouts.main')

@section('content')
  <style>
    /* --- Carousel Fixed Aspect Ratio & Image Centering --- */
    #carouselExampleAutoplaying .carousel-inner {
    aspect-ratio: 1 / 1;
    /* Square aspect ratio */
    overflow: hidden;
    /* Clip any overflowing image content */
    }

    #carouselExampleAutoplaying .carousel-item {
    height: 100%;
    /* Make carousel item fill the height of the .carousel-inner */
    /* Ensure no default flex properties from .active are interfering.
       Bootstrap usually makes .carousel-item.active display: block; which is fine. */
    }

    #carouselExampleAutoplaying .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    /* This is what centers the image content */
    }
  </style>

  <div class="d-flex align-items-center flex-column mt-5">

    {{--Carousel for displaying images--}}
    <div id="carouselExampleAutoplaying" class="carousel slide w-50" style="aspect-ratio: 1/1;" data-bs-ride="carousel">
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
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
      data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
      data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
    </div>


    <div class="shadow-lg p-2 mt-5 bg-light-subtle d-flex flex-column flex-md-row align-items-center">
    <h1 class="mt-2 p-2">{{$product->name}}</h1>

    <div>

      {{--must be done like this or it will ignore the newline chars and show everything on one line--}}
      <p>{!! nl2br(e($product->longdescription)) !!}</p>

      {{--Shows buy button if user is logged in--}}
      @if(Auth::user())
      <a href="{{route('addtocart', ['product' => $product])}}" class="btn btn-info">Add To Cart
      (R{{$product->price}})</a>
    @endif

    </div>

    </div>


  </div>

@endsection