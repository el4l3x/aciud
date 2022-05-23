<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', ) }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.4.1.min.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">

    <script>window._asset = '{{ asset('') }}';</script>
    
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    @guest
                        Atencion al Ciudadano
                    @else
                        <img src="{{ asset('img\LOGO-ALCALDIA-ANAHIS-PALACIOS-NEGRO.png') }}" width="30" height="30" alt="">
                    @endguest
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            
                        @else
                            @if (auth()->user()->rol == 2)                                
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('graficast') }}">
                                        <b-icon icon="pie-chart" type="button" variant="default" id="nav-chart"></b-icon>
                                        <b-tooltip target="nav-chart" triggers="hover">
                                            Indices de Gestion
                                        </b-tooltip>
                                    </a>
                                </li>
                            @endif                            

                            <b-nav-item-dropdown right>
                                <template #button-content>
                                    <b-icon icon="person" type="button" variant="default" id="nav-user"></b-icon>
                                    <b-tooltip target="nav-user" triggers="hover">
                                        {{ Auth::user()->name }}
                                    </b-tooltip>
                                </template>
                                <b-dropdown-item href="{{ route('chpass') }}">
                                    Cambiar clave
                                </b-dropdown-item>
                                <b-dropdown-item href="{{ route('logout') }}" 
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                    Salir
                                </b-dropdown-item>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </b-nav-item-dropdown>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="loading" id="loading">
                <div>
                    <div class="spinner-grow mb-2 ml-4" style="width: 5rem; height: 5rem" role="status">
                    </div>
                    <p>Espere un momento...</p>
                </div>
            </div>
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
