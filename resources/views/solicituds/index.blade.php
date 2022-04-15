@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between mb-3">
                            <h3>Solicitudes</h3>
                            <a class="lead" href="{{ route('solicitudes.create') }}">
                                <b-icon icon="plus-square" type="button" variant="info" id="btn-plus"></b-icon>
                                <b-tooltip target="btn-plus" triggers="hover">
                                    Nueva Solicitud
                                </b-tooltip>                                
                            </a>
                        </div>
                        <div class="card-body">
                            <solicitud-component v-bind:solicitudes="{{$solicitudes}}"></solicitud-component>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
