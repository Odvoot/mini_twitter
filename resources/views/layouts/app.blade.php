<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mini Twitter') }}</title>
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/custom.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/28e768e8b3.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="app">

        <div class="container">
                
            <div class="row">
                <div class="col-md-2" id="left">
                    <div class="fixed">
                        <main class="py-4">
                            <div class="custom-selectable">
                                <h4 class=""><a href="#"><strong><i class="fas fa-crow"></i></strong></a></h4><br>
                                <h4><strong><a class="custom-active" href="#"><i class="fas fa-home"></i>&nbsp;&nbsp;Home</strong></a></h4><br>
                                <h4 class=""><a class="" href="#"><strong><i class="far fa-user-circle"></i>&nbsp;&nbsp;Profile</strong></a></h4><br>
                                @if (Auth::check())
                                    <h4><a class="" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <strong><i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;LogOut</strong>
                                    </a></h4>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                @else
                                <h4 class=""><a class="" href="{{route('login')}}"><strong><i class="fas fa-sign-in-alt"></i>&nbsp;&nbsp;Login</strong></a></h4>
                                    
                                @endif
                                
                            </div>
                        </main>
                    </div>
                    
                </div>
                <div class="col-md-8">
                    <main class="">
                        @yield('content')
                    </main>
                </div>
                
            </div>
        </div>
    </div>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script> --}}
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/autosize.js') }}"></script>
    
    @stack('script')
</body>
</html>
