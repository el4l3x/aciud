@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between mb-3">
                            <h3>Solicitud {{ $solicitudes->codigo }}</h3>
                        </div>
                        <div class="card-body">

                            <div class="row">                                

                                <div class="col-12">
                                    <p class="text-capitalize">
                                        {{ $solicitudes->tipo.' a '.$solicitudes->organismo->nombre.' actualmente '.$solicitudes->status }}
                                    </p>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col">
                                    <p class="text-capitalize">Ciudadano: {{ $solicitudes->ciudadano->nombre.' '.$solicitudes->ciudadano->apellido }} C.I {{ $solicitudes->ciudadano->ci }}</p>
                                </div>

                                <div class="col">
                                    <p class="text-capitalize">
                                        @if (isset($solicitudes->ciudadano->institucion))
                                            Institucion: {{ $solicitudes->ciudadano->institucion.' Telf.: '.$solicitudes->ciudadano->telefono }}
                                        @else
                                            Telf.: {{ $solicitudes->ciudadano->telefono }}
                                        @endif
                                    </p>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col">
                                    <p class="text-capitalize">
                                        {{ $solicitudes->ciudadano->parroquia.'. '.$solicitudes->ciudadano->direccion }}                                    
                                    </p>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col">
                                    <p>
                                        {{ $solicitudes->desarrollo }}
                                    </p>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col">
                                    
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#before">
                                        Anexos
                                    </button>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="before" tabindex="-1" role="dialog" aria-labelledby="beforeLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="beforeLabel">Anexos</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <div class="modal-body">
                                                <div class="card-columns">
                                                    @foreach ($solicitudes->anexos as $item)                                                        
                                                        <div class="card">
                                                            <img class="card-img" src="http://localhost/aciud/public/img/{{ $item->nombre }}" alt="Cargando">
                                                        </div>
                                                    @endforeach
                                                  </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
