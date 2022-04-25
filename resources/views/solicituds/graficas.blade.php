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
                                <!--<a href="{{ route('solicitudes.create') }}">
                                    <b-icon icon="plus-square" type="button" variant="info" id="btn-plus"></b-icon>
                                    <b-tooltip target="btn-plus" triggers="hover">
                                        Nueva Solicitud
                                    </b-tooltip>                          
                                </a>

                                <a href="#" data-toggle="modal" data-target="#sdateModal">
                                    <b-icon icon="calendar3" type="button" variant="info" id="btn-date"></b-icon>
                                    <b-tooltip target="btn-date" triggers="hover">
                                        Buscar por Fechas
                                    </b-tooltip>
                                </a>-->

                            </div>
                            
                        </div>
                        <div class="card-body">
                            <indices-graf v-bind:data="{{json_encode($data)}}"></indices-graf>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
