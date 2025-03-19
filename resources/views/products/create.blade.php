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
    
    <input class="form-control" type="text" name="name" placeholder="name">
    <input class="form-control" type="text" name="qty" placeholder="qty">
    <input class="form-control" type="text" name="price" placeholder="price">
    <input class="form-control" type="file" name="image">
    <input class="form-control" type="file" name="images[]" multiple>
    
    <input class="btn btn-dark m-2" type="submit" value="submit">
    <a href="{{route('product.index')}}" class="btn btn-dark">Back</a>
</form>

@endsection