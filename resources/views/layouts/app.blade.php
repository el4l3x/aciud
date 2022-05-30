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

                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#pdfModal">
                                    <b-icon icon="file-arrow-down" type="button" variant="default" id="pdf-print"></b-icon>
                                    <b-tooltip target="pdf-print" triggers="hover">
                                        Imprimir Listado
                                    </b-tooltip>
                                </a>
                            </li>

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

        <!-- Modal -->
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Imprimir Solicitudes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <form method="POST" action="{{ route('pdf') }}">
                    <div class="modal-body">
                                    
                        @csrf
    
                        <div class="form-row mb-4">
                            
                            <div class="col-4">
                                <label for="tipo">Estatus</label>
                                <select name="filtroStatus[]" id="tipo" class="form-control" multiple data-width="100%" required>
                                    <option value="null" selected>Todos</option>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="realizado">Realizado</option>
                                    <option value="en proceso">En Proceso</option>
                                    <option value="en espera de">En espera de</option>
                                </select>
                            </div>

                            <div class="col-4">
                                <label for="organismo">Tipos</label>
                                <select name="filtroTipo[]" id="tipo" class="form-control" multiple data-width="100%" required>
                                    <option value="null" selected>Todos</option>
                                    <option value="peticion">Peticion</option>
                                    <option value="reclamo">Reclamo</option>
                                    <option value="denuncia">Denuncia</option>
                                </select>
                            </div>
                            
                            <div class="col-4">
                                <label for="organismo">Organismos</label>
                                <select name="filtroOrganismo[]" id="tipo" class="form-control" multiple data-width="100%" required>
                                    <option value="null" selected>Todos</option>
                                    <option value="1">Dirección de despacho</option>
                                    <option value="2">Despacho de alcaldia</option>
                                    <option value="3">Coordinación de tecnología e informática</option>
                                    <option value="4">Dirección de desarrollo social</option>
                                    <option value="5">Dirección de Ingeniería Municipal</option>
                                    <option value="6">Dirección de servicios Públicos municipales</option>
                                    <option value="7">Dirección de Catastro y Ejido</option>
                                    <option value="8">Instituto autónomo de la policía municipal</option>
                                    <option value="9">Protección civil</option>
                                    <option value="10">Protección del niño</option>
                                    <option value="11">Registro civil</option>
                                    <option value="12">Instituto para la mujer</option>
                                    <option value="13">Instituto municipal para la vivienda</option>
                                </select>
                            </div>
                            
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info">Imprimir</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
