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
    
    <label class="form-label" for="price">Price</label>
    <input class="form-control" type="text" name="price" placeholder="price">
    
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

<br>
<h1>For best results :</h1>

<ul>
    <li>Square image for thumbnail</li>
    <li>Same sized images for product images</li>
    <li>A short description for the description field</li>
    <li>A long detailed multi-line description for the long description</li>
</ul>

@endsection