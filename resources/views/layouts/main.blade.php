<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" defer></script>
    <title>My Page</title>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow">

        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="{{route('landing')}}" class="navbar-brand mx-5">Knitwear By L</a>

            <div class="collapse navbar-collapse" id="navbarContent">
                

                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a href="#" class="nav-link">my link</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('catalogue')}}" class="nav-link">Catelogue</a>
                    </li>

                    @if (Auth::user())

                    <li class="nav-item">
                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            @method('post')
                            <input type="submit" value="Logout" class="nav-link">
                        </form>
                    </li>

                    <li class="nav-item">
                        @if (auth()->user()->cart)
                        <a href="{{route('cart')}}" class="nav-link">cart({{count(json_decode(auth()->user()->cart, true))}})</a>
                        @else
                        <a href="#" class="nav-link">cart()</a>
                        @endif
                    </li>

                    @if(auth()->user()->admin)
                    <li class="nav-item">
                        <a href="{{route('product.index')}}">Admin</a>
                    </li>
                    @endif

                    <li class="nav-item">Hi {{auth()->user()->name}}</li>

                    @else
                    <li class="nav-item">
                        <a href="{{route('register')}}" class="nav-link"> Register</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('login')}}" class="nav-link"> Login</a>
                    </li>
                    @endif

                </ul>
            </div>

        </div>

    </nav>

    @if(Route::currentRouteName() === 'landing')
        <div class="landing-banner d-flex justify-content-center align-items-center shadow-lg">
            <h1 class="text-light">Landing page</h1>
        </div>
    
    @endif



    <div class="container">
        @yield('content')
    </div>
    

</body>
</html>