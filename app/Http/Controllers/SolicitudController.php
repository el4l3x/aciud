<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Solicitud;
use App\Ciudadano;
use App\Organismo;
use App\Anexo;
use DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SolicitudController extends Controller
{

    public function __construct()
	{
		$this->middleware('auth');
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $solicitudes = Solicitud::with('ciudadano')->with('organismo')->get();
        //return $solicitudes;
        return view('solicituds.index')
            ->with('solicitudes', $solicitudes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organismos = Organismo::all();
        $select = Ciudadano::select('ci', 'nombre', 'apellido')->get();

        return view('solicituds.create')
            ->with('organismos', $organismos)
            ->with('ciudadanos', $select);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
			DB::beginTransaction();
			$validator = Validator::make($request->all(), [
				'nombre' => 'required|string',
				'apellido' => 'required|string',
				'ci' => 'required|numeric',
				'institucion' => 'nullable|alpha_num',
				'tipo' => 'required|alpha',
				'parroquia' => 'required|string',
				'telefono' => 'nullable|numeric',
				'direccion' => 'required|string',
				'organismo' => 'required|integer',
				'desarrollo' => 'required'
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}
            
			$consulta = Ciudadano::where('ci', $request->ci)->first();
			$code = Solicitud::where('tipo', $request->tipo)->count();
            if (isset($consulta->ci)) {
                
                $solicitud = new Solicitud();
                /*if ($request->file('fileinput') != null) {
                    $solicitud->anexo = explode('public/', $request->file('fileinput')->store('public'))[1];
                } else {
                    $solicitud->anexo = "default.jpg";
                }*/
                $solicitud->tipo = $request->tipo;
                $solicitud->desarrollo = $request->desarrollo;
                $solicitud->ciudadano_id = $consulta->id;
                $solicitud->organismo_id = $request->organismo;
                
                switch ($request->tipo) {
                    case 'peticion':
                        $solicitud->codigo = "P-".$code;
                        $typeabb = 'pet';
                        break;
                        
                    case 'reclamo':
                        $solicitud->codigo = "R-".$code;
                        $typeabb = 'rec';
                        break;
                            
                    case 'denuncia':
                        $solicitud->codigo = "D-".$code;
                        $typeabb = 'den';
                        break;
                }

                $solicitud->save();

                if ($request->file('fileinput') != null) {
                    $i = 1;
                    foreach($request->file('fileinput') as $file)
                    {
                        $anexos = new Anexo();
                        $name = $typeabb.$solicitud->id.'pic'.$i.'.'.$file->extension();
                        $file->move(public_path().'/img/', $name);
                        $anexos->nombre = $name;
                        $anexos->solicitud_id = $solicitud->id;
                        $i++;
                        $anexos->save();
                    }
                }
                     
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nuevo Solicitud - Codigo '.$solicitud->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );
                
            } else {
                $ciudadano = new Ciudadano();
                $ciudadano->nombre = $request->nombre;
                $ciudadano->apellido = $request->apellido;
                $ciudadano->ci = $request->ci;
                $ciudadano->institucion = $request->institucion;
                $ciudadano->direccion = $request->direccion;
                $ciudadano->telefono = $request->telefono;
                $ciudadano->parroquia = $request->parroquia;
                
                $ciudadano->save();
                
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nuevo ciudadano - C.I '.$request->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );
                
                $solicitud = new Solicitud();
                /*if ($request->file('fileinput') != null) {
                    $solicitud->anexo = explode('public/', $request->file('fileinput')->store('public'))[1];
                } else {
                    $solicitud->anexo = "default.jpg";
                }*/
                $solicitud->tipo = $request->tipo;
                $solicitud->desarrollo = $request->desarrollo;
                $solicitud->codigo = "P-".$solicitud->id;
                $solicitud->ciudadano_id = $ciudadano->id;
                $solicitud->organismo_id = $request->organismo;          

                switch ($request->tipo) {
                    case 'peticion':
                        $solicitud->codigo = "P-".$code;
                        $typeabb = 'pet';
                        break;
                        
                    case 'reclamo':
                        $solicitud->codigo = "R-".$code;
                        $typeabb = 'rec';
                        break;
                            
                    case 'denuncia':
                        $solicitud->codigo = "D-".$code;
                        $typeabb = 'den';
                        break;
                }
                
                $solicitud->save();

                if ($request->file('fileinput') != null) {
                    $i = 1;
                    foreach($request->file('fileinput') as $file)
                    {
                        $anexos = new Anexo();
                        $name = $typeabb.$solicitud->id.'pic'.$i.'.'.$file->extension();
                        $file->move(public_path().'/img/', $name);
                        $anexos->nombre = $name;
                        $anexos->solicitud_id = $solicitud->id;
                        $i++;
                        $anexos->save();

                    }
                }                
                
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nuevo Solicitud - Codigo '.$request->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );
                
                DB::commit();
    
                //return "Ciudadano y Solicitud registrados exitosamente";
            }

	        return redirect("/");

		} catch (\Exception $e) {
			DB::rollback();
			return $e;
			//return redirect()->back()->with('danger', 'Algo a fallado.');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $solicitudes = Solicitud::with('ciudadano')->with('organismo')->with('anexos')->findOrFail($id);
        
        return view('solicituds.show')
            ->with('solicitudes', $solicitudes);
    }
    
    public function rf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'desde' => 'required|date',
            'hasta' => 'required|date'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if ($request->desde < $request->hasta) {
            $desde = $request->desde;
            $hasta = $request->hasta;
        } else {
            $hasta = $request->desde;
            $desde = $request->hasta;
        }

        $fecha_inicial = new Carbon($desde);
		$fecha_final = new Carbon($hasta);

        $solicitudes = Solicitud::with('ciudadano')->with('organismo')->where('created_at', '<=', $fecha_final)->where('created_at', '>=', $fecha_inicial)->get();

        //return $solicitudes;
        return view('solicituds.index')
            ->with('solicitudes', $solicitudes);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $solicitudes = Solicitud::with('ciudadano')->with('organismo')->findOrFail($id);

        $tipos = array('peticion', 'reclamo', 'denuncia');
        $organismos = Organismo::all();
        
        return view('solicituds.edit')
            ->with('solicitudes', $solicitudes)
            ->with('tipos', $tipos)
            ->with('organismos', $organismos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
			DB::beginTransaction();
			$validator = Validator::make($request->all(), [
				'nombre' => 'required|string',
				'apellido' => 'required|string',
				'ci' => 'required|numeric',
				'institucion' => 'nullable|alpha_num',
				'tipo' => 'required|alpha',
				'parroquia' => 'required|string',
				'telefono' => 'nullable|numeric',
				'direccion' => 'required|string',
				'organismo' => 'required|integer',
				'desarrollo' => 'required'
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}

            $solicitud = Solicitud::findOrFail($id);

            $consulta = Ciudadano::where('ci', $request->ci)->first();
            if (isset($consulta->ci)) {

                if ($request->file('fileinput') != null) {
                    $solicitud->anexo = explode('public/', $request->file('fileinput')->store('public'))[1];
                }
                $solicitud->tipo = $request->tipo;
                $solicitud->desarrollo = $request->desarrollo;
                switch ($request->tipo) {
                    case 'peticion':
                        $solicitud->codigo = "P-".$id;
                        $typeabb = 'pet';
                        break;
                        
                    case 'reclamo':
                        $solicitud->codigo = "R-".$id;
                        $typeabb = 'rec';
                        break;
                            
                    case 'denuncia':
                        $solicitud->codigo = "D-".$id;
                        $typeabb = 'den';
                        break;
                }
                $solicitud->ciudadano_id = $consulta->id;
                $solicitud->organismo_id = $request->organismo;
                
                $solicitud->save();

                if ($request->file('fileinput') != null) {
                    $i = 1;
                    foreach($request->file('fileinput') as $file)
                    {
                        $anexos = new Anexo();
                        $name = $typeabb.$solicitud->id.'pic'.$i.'.'.$file->extension();
                        $file->move(public_path().'/img/', $name);
                        $anexos->nombre = $name;
                        $anexos->solicitud_id = $solicitud->id;
                        $i++;
                        $anexos->save();
                    }
                }
                
                DB::table('logs')->insert(
                    ['accion' => 'Actualizar Solicitud - Codigo '.$id, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );
                
                DB::commit();

            } else {
                $ciudadano = new Ciudadano();
                $ciudadano->nombre = $request->nombre;
                $ciudadano->apellido = $request->apellido;
                $ciudadano->ci = $request->ci;
                $ciudadano->institucion = $request->institucion;
                $ciudadano->direccion = $request->direccion;
                $ciudadano->telefono = $request->telefono;
                $ciudadano->parroquia = $request->parroquia;
                
                $ciudadano->save();
                
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nuevo ciudadano - C.I '.$request->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );

                $solicitud->tipo = $request->tipo;
                $solicitud->desarrollo = $request->desarrollo;
                $solicitud->codigo = "P-".$solicitud->id;
                $solicitud->ciudadano_id = $ciudadano->id;
                $solicitud->organismo_id = $request->organismo;
                
                $solicitud->save();

                switch ($request->tipo) {
                    case 'peticion':
                        $solicitud->codigo = "P-".$id;
                        $typeabb = 'pet';
                        break;
                        
                    case 'reclamo':
                        $solicitud->codigo = "R-".$id;
                        $typeabb = 'rec';
                        break;
                            
                    case 'denuncia':
                        $solicitud->codigo = "D-".$id;
                        $typeabb = 'den';
                        break;
                }

                $solicitud->save();

                if ($request->file('fileinput') != null) {
                    $i = 1;
                    foreach($request->file('fileinput') as $file)
                    {
                        $anexos = new Anexo();
                        $name = $typeabb.$solicitud->id.'pic'.$i.'.'.$file->extension();
                        $file->move(public_path().'/img/', $name);
                        $anexos->nombre = $name;
                        $anexos->solicitud_id = $solicitud->id;
                        $i++;
                        $anexos->save();
                    }
                }
                
                DB::table('logs')->insert(
                    ['accion' => 'Actualizar Solicitud - Codigo '.$id, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );
                
                DB::commit();
            }            

	        return redirect("/");

		} catch (\Exception $e) {
			DB::rollback();
			return $e;
			//return redirect()->back()->with('danger', 'Algo a fallado.');
		}
    }

    public function status(Request $request, $id)
    {
        try {
			DB::beginTransaction();
			$validator = Validator::make($request->all(), [
				'status' => 'required|string',
			]);
	
			if ($validator->fails()) {
				return $validator->withErrors($validator);
			}

            $solicitud = Solicitud::find($id);

            if (isset($solicitud->id)) {
                $solicitud->status = $request->status;
                $solicitud->save();

                DB::table('logs')->insert(
                    ['accion' => 'Actualizar Status Solicitud - Codigo '.$id, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );

                DB::commit();

                return "Tuvo exito";
            } else {
                return "Algo fallo";
            }

		} catch (\Exception $e) {
			DB::rollback();
			return $e;
			//return redirect()->back()->with('danger', 'Algo a fallado.');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Solicitud::destroy($id);

        DB::table('logs')->insert(
            ['accion' => 'Eliminar Solicitud - Codigo '.$id, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
        );

		return $id;
    }
    
    public function graficas()
    {
        //$solicitudes = Solicitud::with('ciudadano')->with('organismo')->get();
        //return $solicitudes;
        return view('solicituds.graficas');
            //->with('solicitudes', $solicitudes);
    }
}
