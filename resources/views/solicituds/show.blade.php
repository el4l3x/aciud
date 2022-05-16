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

                            @if ($solicitudes->institucion != NULL)

                                <div class="row">

                                    <div class="col">
                                        <p class="text-capitalize">Institucion: {{ $solicitudes->institucion->nombre }}</p>
                                    </div>

                                    <div class="col">
                                        <p class="text-capitalize">
                                            Telf.: {{ $solicitudes->institucion->telefono }}
                                        </p>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col">
                                        <p class="text-capitalize">
                                            {{ $solicitudes->institucion->direccion }}                                    
                                        </p>
                                    </div>
    
                                </div>

                            @else

                                @switch($solicitudes->dirigida)
                                    @case("personal")                                        
                                        <div class="row">                                            

                                            <div class="col">
                                                <p class="text-capitalize">Ciudadano: {{ $solicitudes['involucrados']['0']['nombre'].' '.$solicitudes['involucrados']['0']['apellido'] }} C.I {{ $solicitudes['involucrados']['0']['ci'] }}</p>
                                            </div>
        
                                            <div class="col">
                                                <p class="text-capitalize">
                                                    @if (isset($solicitudes['involucrados']['0']['telefono']))
                                                        Telf.: {{ $solicitudes['involucrados']['0']['telefono'] }}
                                                    @endif
                                                </p>
                                            </div>
        
                                        </div>

                                        <div class="row">

                                            <div class="col">
                                                <p class="text-capitalize">
                                                    {{ $solicitudes['involucrados']['0']['parroquia'].' '.$solicitudes['involucrados']['0']['sector'] }}                                    
                                                </p>
                                            </div>
            
                                        </div>
                                        @break

                                    @case("tercero")
                                        @php
                                            foreach ($solicitudes->involucrados as $key => $value) {
                                                switch ($value->pivot->status) {
                                                    case "solicitante":
                                                        $solicitante = $value;
                                                        break;
                                                        
                                                    case "beneficiario":
                                                        $beneficiario = $value;
                                                        break;
                                                    
                                                    default:
                                                        # code...
                                                        break;
                                                }
                                            }
                                        @endphp
                                        <div class="row">                                            
                                            <h5>Solicitante</h5>
                                            <div class="col">
                                                <p class="text-capitalize">Ciudadano: {{ $solicitante->nombre.' '.$solicitante->apellido }} C.I {{ $solicitante->ci }}</p>
                                            </div>
        
                                            <div class="col">
                                                <p class="text-capitalize">
                                                    @if (isset($solicitante->telefono))
                                                        Telf.: {{ $solicitante->telefono }}
                                                    @endif
                                                </p>
                                            </div>
        
                                        </div>

                                        <div class="row">

                                            <div class="col">
                                                <p class="text-capitalize">
                                                    {{ $solicitante->parroquia.' '.$solicitante->sector }}                                    
                                                </p>
                                            </div>
            
                                        </div>
                                        
                                        <div class="row">                                            
                                            <h5>Beneficiario</h5>
                                            <div class="col">
                                                <p class="text-capitalize">Ciudadano: {{ $beneficiario->nombre.' '.$beneficiario->apellido }} C.I {{ $beneficiario->ci }}</p>
                                            </div>
        
                                            <div class="col">
                                                <p class="text-capitalize">
                                                    @if (isset($beneficiario->telefono))
                                                        Telf.: {{ $beneficiario->telefono }}
                                                    @endif
                                                </p>
                                            </div>
        
                                        </div>

                                        <div class="row">

                                            <div class="col">
                                                <p class="text-capitalize">
                                                    {{ $beneficiario->parroquia.' '.$beneficiario->sector }}                                    
                                                </p>
                                            </div>
            
                                        </div>

                                        @break
                                    @default
                                        
                                @endswitch                                

                            @endif                            

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
                                                            <img class="card-img" src="http://localhost/aciud/public/anexos/{{ $item->nombre }}" alt="Cargando">
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#loading').fadeOut();        
    });
</script>
@endpush