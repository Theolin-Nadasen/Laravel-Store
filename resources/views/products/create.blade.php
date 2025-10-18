@extends('layouts.main')

@section('content')


@if($errors->any())

<ul>
    @foreach ($errors->all() as $error)
    <li>{{$error}}</li>        
    @endforeach
</ul>

@endif

<form class="mt-5" action={{route('product.store')}} method='post' enctype="multipart/form-data">
    @csrf
    @method('post')
    
    <label class="form-label" for="name">Name</label>
    <input class="form-control" type="text" name="name" placeholder="name">
    
    <label class="form-label" for="qty">Qty</label>
    <input class="form-control" type="text" name="qty" placeholder="qty">

    {{-- Variant Toggle --}}
    <div class="form-check mt-3">
        <input class="form-check-input" type="checkbox" name="has_variants" id="hasVariantsCheck">
        <label class="form-check-label" for="hasVariantsCheck">
            This product has variations (sizes, colors)
        </label>
    </div>

    {{-- Standard Price --}}
    <div id="price-container" class="mb-3">
        <label class="form-label" for="price" id="price-label">Price</label>
        <input class="form-control" type="text" name="price" placeholder="price">
    </div>

    {{-- Variant Fields (hidden by default) --}}
    <div id="variant-fields" style="display: none; border: 1px solid #ccc; padding: 15px; border-radius: 5px;" class="mt-3 mb-3">
        <h5>Variant Pricing</h5>
        <p>The price set above will be used for the "Normal" size.</p>
        <label class="form-label" for="price_small">Price (Small Size)</label>
        <input class="form-control" type="text" name="price_small" placeholder="Optional price for small size">

        <label class="form-label mt-2" for="price_large">Price (Large Size)</label>
        <input class="form-control" type="text" name="price_large" placeholder="Optional price for large size">

        <h5 class="mt-3">Colors</h5>
        <label class="form-label" for="colors">Available Colors (optional, comma-separated)</label>
        <input class="form-control" type="text" name="colors" placeholder="e.g., Red, Blue, Black">
    </div>
    
    <label class="form-label" for="description">Description</label>
    <input class="form-control" type="text" name="description" placeholder="description">
    
    <label class="form-label" for="longdescription">Long Description</label>
    <textarea class="form-control" name="longdescription" placeholder="long description"></textarea>
    
    <label class="form-label" for="image">Thumbnail</label>
    <input class="form-control" type="file" name="image">

    <label class="form-label" for="Images">Images</label>
    <input class="form-control" type="file" name="images[]" multiple>
    
    <input class="btn btn-dark m-2" type="submit" value="submit">
    <a href="{{route('product.index')}}" class="btn btn-dark">Back</a>
</form>

<script>
    document.getElementById('hasVariantsCheck').addEventListener('change', function() {
        var variantFields = document.getElementById('variant-fields');
        var priceLabel = document.getElementById('price-label');
        if (this.checked) {
            variantFields.style.display = 'block';
            priceLabel.textContent = 'Price (Normal Size)';
        } else {
            variantFields.style.display = 'none';
            priceLabel.textContent = 'Price';
        }
    });
</script>

<br>
<h1>For best results :</h1>

<ul>
    <li>Square image for thumbnail</li>
    <li>Same sized images for product images for best results</li>
    <li>A short description for the description field</li>
    <li>A long detailed multi-line description for the long description</li>
</ul>

@endsection