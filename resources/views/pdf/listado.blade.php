<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Solicitudes</title>

        <!-- Fonts -->

        <!-- Styles -->
        {{-- <link rel="stylesheet" href="../public/css/app.css">
        <link rel="stylesheet" href="../public/css/index.css"> --}}
        <link rel="stylesheet" href="../public/css/bootstrap.css">

    </head>
    <body>
      <!--HEADER DE LA FACTURA-->
      <div class="" style="height: 120px; position: absolute; left: 10px; top: 0px;" >
          <img src="../public/img/LOGO-ALCALDIA-ANAHIS-PALACIOS-NEGRO.png" width="150" alt="logo-alcaldia" >
      </div>

          <div class="text-center mt-3" style="width: 100%;">
            <br>
            <br>
              <span class="font-weight-bold">Despacho de la Alcaldia</span>
              <br>
              <span><span class="font-weight-bold">Listado de Solicitudes</span>
              <br>
          </div>

          <br>

      <!--TABLA DE PRODUCTOS-->

      <table class="table table-striped table-bordered mt-3">
          <thead class="bg-info text-white">
              <tr>
                  <th>Beneficiario</th>
                  <th>Tipo</th>
                  <th>Organismo</th>
                  <th>Estado</th>
                  <th>Fecha</th>
              </tr>
          </thead>
          <tbody>
              @foreach($solicitudes as $solicitud)
              <tr>
                  @if ($solicitud->institucion == null)
                    @php                                      
                      $idben = "1";
                      $nombre = "error";
                      $involucrados = $solicitud->involucrados;

                      foreach ($involucrados as $key => $value) {
                        if ($value->pivot->status == "beneficiario") {
                          $idben = $value->id;
                          $nombre = $value->nombre.' '.$value->apellido;
                        }
                      }
                    @endphp
                    <td class="small text-center text-capitalize">{{ $nombre }}</td>                                
                    @else
                    <td class="small text-center text-capitalize">{{ $solicitud->institucion->nombre }}</td>                                                                  
                  @endif
                  <td class="small text-center text-capitalize">{{$solicitud->tipo}}</td>                                
                  <td class="small text-center text-capitalize">{{$solicitud->organismo->nombre}}</td>                                
                  <td class="small text-center text-capitalize">{{$solicitud->status}}</td>                                
                  <td class="small text-center text-capitalize">{{$solicitud->created_at}}</td>                                

              </tr>
              @endforeach

          </tbody>
      </table>
    </body>
</html>
