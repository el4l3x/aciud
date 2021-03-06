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
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Tipo
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ asset('/graficos/total') }}">Total</a>
                                        <a class="dropdown-item" href="{{ asset('/graficos/status') }}">Status</a>
                                    </div>
                                </div>

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

@push('scripts')
<script>
    $(document).ready(function() {
        $('#loading').fadeOut();        
    });
</script>
@endpush