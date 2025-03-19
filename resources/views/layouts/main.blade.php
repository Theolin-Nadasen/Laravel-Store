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
    
    <nav class="navbar bg-body-tertiary">

        <div class="container-fluid">
            <a href="{{route('landing')}}" class="navbar-brand mx-5">Knitwear By L</a>

            <ul class="navbar-nav d-flex flex-row me-auto">

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
                    <a href="#" class="nav-link">cart({{count(json_decode(auth()->user()->cart, true))}})</a>
                    @else
                    <a href="#" class="nav-link">cart()</a>
                    @endif
                </li>

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

    </nav>

    @if(Route::currentRouteName() === 'landing')
        <div class="landing-banner d-flex justify-content-center align-items-center">
            <h1 class="text-light">Landing page</h1>
        </div>
    
    @endif



    <div class="container">
        @yield('content')
    </div>
    

</body>
</html>