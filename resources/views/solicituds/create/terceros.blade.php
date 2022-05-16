@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">

                <div class="col">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between mb-3">
                            <h3>Nueva Solicitud</h3>
                        </div>
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <h4>Solicitante</h4>

                            <form method="POST" action="/solicitudes/store/terceros" enctype="multipart/form-data">
                                
                                @csrf
                                
                                <select-ter v-bind:ciudadanos="{{$ciudadanos}}"></select-ter>
                                
                                <h4>Beneficiario</h4>
                                
                                <select-ben v-bind:ciudadanos="{{$ciudadanos}}"></select-ben>

                                <div class="form-row mb-4">
                                    
                                    <div class="col-4">
                                        <label for="tipo">Tipo</label>
                                        <select name="tipo" id="tipo" class="form-control" data-width="100%" required>
                                            <option selected disabled>Selecciona</option>
                                            <option value="peticion">Peticion</option>
                                            <option value="reclamo">Reclamo</option>
                                            <option value="denuncia">Denuncia</option>
                                        </select>
                                    </div>

                                    <div class="col-6">
                                        <label for="organismo">Organismos</label>
                                        <select name="organismo" id="organismo" class="form-control" data-width="100%" required>
                                            <option selected disabled>Selecciona</option>
                                            @foreach ($organismos as $i)
                                                <option value="{{ $i->id }}">{{ $i->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>                                 
                                    
                                </div>
                                
                                <div class="form-row mb-4">    
                                    <div class="col-6">
                                        <label>Anexos</label><br>
                                        <div class="file-input-wrapper">
                                            <img class="img-fluid img-thumbnail shadow" style="height: 200px; display: none" id="foto">
                                            <p id="image_name" class="mt-3 mb-1"></p>
                                            <p id="image_weigth" class="mb-3"></p>

                                            <p id="imgerror" class="text-danger" style="display: none;"></p>
                                            <label id="clearbtn" type="button" class="btn btn-info" style="display: none">Limpiar</label>
                                            <label for="fileinput" class="btn btn-info">Buscar</label>
                                            <input id="fileinput" id="fileinput" name="fileinput[]" type="file" accept="image/*" multiple>
                                        </div>
                                    </div>
                                    
                                    <div class="col-6">
                                        <label for="desarrollo">Desarrollo</label>
                                        <textarea class="form-control" name="desarrollo" cols="5" id="desarrollo"></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-row mb-4">
                                    <div class="col-md-4 col-12">
                                        <a href="{{ url('/') }}" type="button" class="btn btn-secondary">
                                            Volver
                                        </a>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <button type="submit" class="btn btn-info">Enviar</button>
                                    </div>
                                </div>

                            </form>                            

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
        
        $('#fileinput').change((e) => {

            var input = e.target;

            // imagen de preview
            let file   = e.target.files[0];
            let reader = new FileReader();

            let filesize = file.size / 1024

            // validaciones del archivo
            if (filesize > 15000) {
                $('#imgerror').text('La imagen excede los 15000kb permitidos.')
                $('#imgerror').show()
                return
            }

            let allowed_ext = ['png', 'jpeg', 'gif']
            let ext_length = allowed_ext.length

            for (let i = 0; i < ext_length; i++){

                if (!file.type.includes(allowed_ext[i])) {
                    if (ext_length - 1 == i) {
                        $('#imgerror').text('Este formato no está permitido.')
                        $('#imgerror').show()

                        return
                    }
                }
                else {
                    break
                }
            }

            reader.onload = function(){
                $('#imgerror').hide();
                $('#foto').show();
                $('#clearbtn').show();
            var dataURL = reader.result;
            var output = document.getElementById('foto');
            output.src = dataURL;
                $('#image_name').text(file.name)
                $('#image_weigth').text(`${ filesize.toFixed(2) } kb`)
            };
            reader.readAsDataURL(input.files[0]);

            $('#clearbtn').click(() => {
                $('#imgerror').text('')
                $('#fileinput').val('')
                $('#foto').hide()
                $('#image_name').text('')
                $('#image_weigth').text('')
                $('#clearbtn').hide()
            })

            reader.readAsDataURL(file);
        });

        $("#chk-int").change((e) => {
            var prop = $("#chk-int").prop( "checked" );

            if (!prop) {
                $("#inpt-int").val("");
            }
        
            $("#inpt-int").prop( "disabled", !prop );

        });
        
    });

</script>
@endpush
