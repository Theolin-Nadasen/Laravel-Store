@extends('layouts.main')

@section('content')

    @if($errors->any())

    <ul>
        @foreach ($errors->all() as $error)
            <li>{{$error}}</li>        
        @endforeach
    </ul>

    @endif
    
    <form class="mt-5" action="{{route('product.update', ['product' => $product])}}" method='post' enctype="multipart/form-data">
        @csrf
        @method('put')

        <label for="name" class="form-label">Name</label>
        <input class="form-control" type="text" name="name" value="{{$product->name}}">

        <label for="qty" class="form-label">QTY</label>
        <input class="form-control" type="text" name="qty" value={{$product->qty}}>

        <label for="price" class="form-label">Price</label>
        <input class="form-control" type="text" name="price" value={{$product->price}}>

        <label for="description" class="form-label">Description</label>
        <input class="form-control" type="text" name="description" value="{{$product->description}}">

        <label for="longdescription" class="form-label">Long Description</label>
        <textarea class="form-control" name="longdescription">{{e($product->longdescription)}}</textarea>

        <label for="image" class="form-label">Thumbnail</label>
        <input class="form-control" type="file" name="image" value="{{$product->image}}">

        <label for="images" class="form-label">Images</label>
        <input class="form-control" type="file" name="images[]" multiple>
        


        <input class="btn btn-dark m-2" type="submit" value="Confirm">
        <a href="{{route('product.index')}}" class="btn btn-dark">Back</a>
    </form>

@endsection