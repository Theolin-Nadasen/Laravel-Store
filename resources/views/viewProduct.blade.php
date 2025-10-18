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

  <div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            {{--Carousel for displaying images--}}
            <div id="carouselExampleAutoplaying" class="carousel slide" style="aspect-ratio: 1/1;" data-bs-ride="carousel">
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
        </div>
        <div class="col-md-6">
            <div class="shadow-lg p-4 bg-light-subtle">
                <h1 class="mt-2 p-2">{{$product->name}}</h1>
                <div>
                    {{--must be done like this or it will ignore the newline chars and show everything on one line--}}
                    <p>{!! nl2br(e($product->longdescription)) !!}</p>

                    {{--Shows buy button if user is logged in--}}
                    @if(Auth::user())
                        @if($product->is_master)
                            @php
                                $variants = $product->variants;
                                $sizes = $variants->pluck('size')->unique()->filter();
                                $colors = $variants->pluck('color')->unique()->filter();
                            @endphp

                            <div id="variant-options">
                                <div class="mb-3">
                                    <label for="size" class="form-label">Size:</label>
                                    <select name="size" id="size" class="form-select">
                                        <option value="">Select Size</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if($colors->isNotEmpty())
                                    <div class="mb-3">
                                        <label for="color" class="form-label">Color:</label>
                                        <select name="color" id="color" class="form-select">
                                            <option value="">Select Color</option>
                                            @foreach($colors as $color)
                                                <option value="{{ $color }}">{{ $color }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <a href="#" id="addToCartBtn" class="btn btn-info disabled">Add To Cart <span id="price-display"></span></a>

                            <script>
                                const variants = @json($variants);
                                const sizeSelector = document.getElementById('size');
                                const colorSelector = document.getElementById('color');
                                const addToCartBtn = document.getElementById('addToCartBtn');
                                const priceDisplay = document.getElementById('price-display');

                                function findVariant() {
                                    const selectedSize = sizeSelector.value;
                                    const selectedColor = colorSelector ? colorSelector.value : null;

                                    const hasColorOptions = {{ $colors->isNotEmpty() ? 'true' : 'false' }};

                                    const isSelectable = selectedSize && (hasColorOptions ? !selectedColor : true);

                                    if (!isSelectable) {
                                        addToCartBtn.classList.add('disabled');
                                        addToCartBtn.href = '#';
                                        priceDisplay.textContent = '';
                                        return;
                                    }

                                    const variant = variants.find(v => {
                                        if (hasColorOptions) {
                                            return v.size === selectedSize && v.color === selectedColor;
                                        }
                                        return v.size === selectedSize;
                                    });

                                    if (variant) {
                                        addToCartBtn.classList.remove('disabled');
                                        addToCartBtn.href = `/addtocart/${variant.id}`;
                                        priceDisplay.textContent = `(R${variant.price})`;
                                    } else {
                                        addToCartBtn.classList.add('disabled');
                                        addToCartBtn.href = '#';
                                        priceDisplay.textContent = '(Not available)';
                                    }
                                }

                                sizeSelector.addEventListener('change', findVariant);
                                if (colorSelector) {
                                    colorSelector.addEventListener('change', findVariant);
                                }
                            </script>

                        @else
                            {{-- Simple Product View --}}
                            <a href="{{route('addtocart', ['product' => $product])}}" class="btn btn-info">Add To Cart (R{{$product->price}})</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection