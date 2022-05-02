@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between mb-3">
                            <h3>Solicitudes</h3>
                            <div class="lead">
                                @if (auth()->user()->rol != 3)                                    
                                    <a href="{{ route('solicitudes.create') }}">
                                        <b-icon icon="plus-square" type="button" variant="info" id="btn-plus"></b-icon>
                                        <b-tooltip target="btn-plus" triggers="hover">
                                            Nueva Solicitud
                                        </b-tooltip>                          
                                    </a>
                                @endif

                                <a href="#" data-toggle="modal" data-target="#sdateModal">
                                    <b-icon icon="calendar3" type="button" variant="info" id="btn-date"></b-icon>
                                    <b-tooltip target="btn-date" triggers="hover">
                                        Buscar por Fechas
                                    </b-tooltip>
                                </a>

                            </div>
                              
                            <!-- Modal -->
                            <div class="modal fade" id="sdateModal" tabindex="-1" role="dialog" aria-labelledby="sdateModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="sdateModalLabel">Filtrar por Fechas</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form method="POST" action="/solicitudes/rf">
                                            <div class="modal-body">
                                                @csrf
                                                <div class="form-row">
                                                    <div class="col">
                                                        <label for="desde">Desde:</label>
                                                        <input type="date" class="form-control" name="desde" id="desde">
                                                    </div>
                                                    <div class="col">
                                                        <label for="hasta">Hasta:</label>
                                                        <input type="date" class="form-control" name="hasta" id="hasta">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-primary">Buscar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <solicitud-component v-bind:solicitudes="{{$solicitudes}}" v-bind:rol="{{auth()->user()->rol}}"></solicitud-component>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
