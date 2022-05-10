<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Solicitud;
use App\Ciudadano;
use App\Organismo;
use App\Institucion;
use App\Anexo;
use App\Beneficiario;
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
        $solicitudes = Solicitud::with('institucion')->with('organismo')->with('anexos')->with('involucrados')->get();
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
        $select = Ciudadano::select('ci', 'nombre', 'apellido', 'telefono', 'parroquia', 'sector')->get();

        return view('solicituds.create')
            ->with('organismos', $organismos)
            ->with('ciudadanos', $select);
    }
    
    public function createinst($tipo)
    {
        switch ($tipo) {
            case 'terceros':

                $organismos = Organismo::all();
                $select = Ciudadano::select('ci', 'nombre', 'apellido', 'sector', 'parroquia', 'telefono')->get();

                return view('solicituds.create.terceros')
                    ->with('organismos', $organismos)
                    ->with('ciudadanos', $select);

                break;
                
            case 'institucional':
                
                $organismos = Organismo::all();
                $select = Institucion::select('direccion', 'nombre', 'telefono')->get();

                return view('solicituds.create.instituciones')
                    ->with('organismos', $organismos)
                    ->with('instituciones', $select);

                break;
            
            default:
                # code...
                break;
        }
        return $tipo;
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
				'parroquia' => 'required|string',
				'sector' => 'required|string',
				'telefono' => 'nullable|numeric',
				'tipo' => 'required|alpha',
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
            if (!isset($consulta->ci)) {
                
                $ciudadano = new Ciudadano();
                $ciudadano->nombre = $request->nombre;
                $ciudadano->apellido = $request->apellido;
                $ciudadano->ci = $request->ci;
                $ciudadano->telefono = $request->telefono;
                $ciudadano->sector = $request->sector;
                $ciudadano->parroquia = $request->parroquia;
                
                $ciudadano->save();

                $idciu = $ciudadano->id;
                
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nuevo ciudadano - C.I '.$request->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );                
                
            } else {
                $idciu = $consulta->id;
            }

            $solicitud = new Solicitud();

            $solicitud->tipo = $request->tipo;
            $solicitud->desarrollo = $request->desarrollo;
            $solicitud->institucion_id = NULL;
            $solicitud->organismo_id = $request->organismo;
            $solicitud->dirigida = 'personal';
            
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

            $beneficiario = new Beneficiario();
            $beneficiario->status = "solicitante";
            $beneficiario->ciudadano_id = $idciu;
            $beneficiario->solicitud_id = $solicitud->id;

            $beneficiario->save();
            
            $beneficiario = new Beneficiario();
            $beneficiario->status = "beneficiario";
            $beneficiario->ciudadano_id = $idciu;
            $beneficiario->solicitud_id = $solicitud->id;

            $beneficiario->save();
                    
            DB::table('logs')->insert(
                ['accion' => 'Registro de nuevo Solicitud - Codigo '.$solicitud->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
            );
            
            DB::commit();

            //return "Ciudadano y Solicitud registrados exitosamente";

	        return redirect("/");

		} catch (\Exception $e) {
			DB::rollback();
			return $e;
			//return redirect()->back()->with('danger', 'Algo a fallado.');
		}
    }
    
    public function storeter(Request $request)
    {
        return $request;
        try {
			DB::beginTransaction();
			$validator = Validator::make($request->all(), [
				'nombre' => 'required|string',
				'apellido' => 'required|string',
				'ci' => 'required|numeric',
				'parroquia' => 'required|string',
				'sector' => 'required|string',
				'telefono' => 'nullable|numeric',
                'nombreb' => 'required|string',
				'apellidob' => 'required|string',
				'cib' => 'required|numeric',
				'parroquiab' => 'required|string',
				'sectorb' => 'required|string',
				'telefonob' => 'nullable|numeric',
				'tipo' => 'required|alpha',
				'organismo' => 'required|integer',
				'desarrollo' => 'required'
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}
            
			$consultasol = Ciudadano::where('ci', $request->ci)->first();
			$consultaben = Ciudadano::where('ci', $request->cib)->first();
			$code = Solicitud::where('tipo', $request->tipo)->count();

            if (!isset($consultasol->ci)) {
                $ciudadano = new Ciudadano();
                $ciudadano->nombre = $request->nombre;
                $ciudadano->apellido = $request->apellido;
                $ciudadano->ci = $request->ci;
                $ciudadano->sector = $request->sector;
                $ciudadano->telefono = $request->telefono;
                $ciudadano->parroquia = $request->parroquia;
                
                $ciudadano->save();
                
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nuevo ciudadano - C.I '.$request->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );

                $idsol = $ciudadano->id;
            } else {
                $idsol = $consultasol->id;
            }
            
            if (!isset($consultaben->ci)) {
                $ciudadano = new Ciudadano();
                $ciudadano->nombre = $request->nombreb;
                $ciudadano->apellido = $request->apellidob;
                $ciudadano->ci = $request->cib;
                $ciudadano->sector = $request->sectorb;
                $ciudadano->telefono = $request->telefonob;
                $ciudadano->parroquia = $request->parroquiab;
                
                $ciudadano->save();
                
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nuevo ciudadano - C.I '.$request->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );

                $idben = $ciudadano->id;
            } else {
                $idben = $consultaben->id;
            }
                
            $solicitud = new Solicitud();
            $solicitud->tipo = $request->tipo;
            $solicitud->desarrollo = $request->desarrollo;
            $solicitud->organismo_id = $request->organismo;
            $solicitud->institucion_id = NULL;
            $solicitud->dirigida = 'tercero';
            
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

            $beneficiario = new Beneficiario();
            $beneficiario->status = "solicitante";
            $beneficiario->ciudadano_id = $idsol;
            $beneficiario->solicitud_id = $solicitud->id;

            $beneficiario->save();
            
            $beneficiario = new Beneficiario();
            $beneficiario->status = "beneficiario";
            $beneficiario->ciudadano_id = $idben;
            $beneficiario->solicitud_id = $solicitud->id;

            $beneficiario->save();
                    
            DB::table('logs')->insert(
                ['accion' => 'Registro de nuevo Solicitud - Codigo '.$solicitud->ci, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
            );

            DB::commit();

	        return redirect("/");

		} catch (\Exception $e) {
			DB::rollback();
			return $e;
			//return redirect()->back()->with('danger', 'Algo a fallado.');
		}
    }
    
    public function storeins(Request $request)
    {
        //return $request;
        try {
			DB::beginTransaction();
			$validator = Validator::make($request->all(), [
				'nombre' => 'required|string',
				'telefono' => 'required|string',
				'direccion' => 'required|string',
				'tipo' => 'required|alpha',
				'organismo' => 'required|integer',
				'desarrollo' => 'required'
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}
            
			$consulta = Institucion::where('nombre', $request->nombre)->first();
			$code = Solicitud::where('tipo', $request->tipo)->count();

            if (!isset($consulta->nombre)) {
                $institucion = new Institucion();
                $institucion->nombre = $request->nombre;
                $institucion->telefono = $request->telefono;
                $institucion->direccion = $request->direccion;
                
                $institucion->save();
                
                DB::table('logs')->insert(
                    ['accion' => 'Registro de nueva Institucion - Nombre '.$request->nombre, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
                );

                $idins = $institucion->id;
            } else {
                $idins = $consulta->id;
            }
                
            $solicitud = new Solicitud();
            $solicitud->tipo = $request->tipo;
            $solicitud->desarrollo = $request->desarrollo;
            $solicitud->organismo_id = $request->organismo;
            $solicitud->institucion_id = $idins;
            $solicitud->dirigida = 'institucion';
            
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

            DB::commit();

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
        $solicitudes = Solicitud::with('institucion')->with('organismo')->with('anexos')->with('involucrados')->findOrFail($id);
        //$solicitudes = Solicitud::with('institucion')->with('organismo')->with('anexos')->with('beneficiarios')->with('involucrados')->findOrFail($id);
        //return $solicitudes;
        
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
        $solicitudes = Solicitud::with('institucion')->with('organismo')->with('anexos')->with('involucrados')->findOrFail($id);
        $tipos = array('peticion', 'reclamo', 'denuncia');
        $organismos = Organismo::all();
        //return $solicitudes;
        
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
				'tipo' => 'required|alpha',
				'organismo' => 'required|integer',
				'desarrollo' => 'required'
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}

            $solicitud = Solicitud::findOrFail($id);

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
            $solicitud->organismo_id = $request->organismo;
            
            $solicitud->save();

            if ($request->file('fileinput') != null) {
                
                $i = 1;
                foreach($request->fileinput as $file)
                {
                    $anexos = new Anexo();
                    //$name = $typeabb.$solicitud->id.'pic'.$i.'.'.$file->extension();
                    $name = uniqid().'.'.$file->extension();
                    $file->move(public_path().'/anexos/', $name);
                    $anexos->nombre = $name;
                    $anexos->solicitud_id = $solicitud->id;
                    $i++;
                    $anexos->save();
                }
            }
            
            if ($request->image != null) {
                foreach($request->image as $file)
                {
                    if(\File::exists(public_path('anexos/'.$file))){
                        \File::delete(public_path('anexos/'.$file));
                    }
                    Anexo::where('nombre', $file)->delete();
                }
            }
            
            DB::table('logs')->insert(
                ['accion' => 'Actualizar Solicitud - Codigo '.$id, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
            );
            
            DB::commit();      

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
        $anexos = Anexo::where("solicitud_id", $id)->get();
        foreach ($anexos as $key => $value) {
            if(\File::exists(public_path('img/'.$value->nombre))){
                \File::delete(public_path('img/'.$value->nombre));
            }
        }
        Anexo::where('solicitud_id', $id)->delete();
        Beneficiario::where('solicitud_id', $id)->delete();
        Solicitud::destroy($id);

        DB::table('logs')->insert(
            ['accion' => 'Eliminar Solicitud - Codigo '.$id, 'cargo' => auth()->user()->username, 'usuario' => auth()->user()->name, 'created_at' => Carbon::now() ]
        );

		return $id;
    }
    
    public function graficast()
    {
        //return $tipo;
        $solicitudes = Solicitud::all()->count();
        $peticiones = Solicitud::where('tipo', 'peticion')->count();
        $reclamos = Solicitud::where('tipo', 'reclamo')->count();
        $denuncias = Solicitud::where('tipo', 'denuncia')->count();

        $data = array('solicitudes' => $solicitudes, 'peticiones' => $peticiones, 'reclamos' => $reclamos, 'denuncias' => $denuncias,);
        //return $data;
        return view('solicituds.graficast')
        ->with('data', $data);
        //return view('solicituds.graficas', compact($data));
        
    }
    
    public function graficass()
    {
        //return $tipo;
        $solicitudes = Solicitud::all()->count();
        $pendiente = Solicitud::where('status', 'pendiente')->count();
        $enproceso = Solicitud::where('status', 'en proceso')->count();
        $realizado = Solicitud::where('status', 'realizado')->count();
        $enesperade = Solicitud::where('status', 'en espera de')->count();

        $data = array('solicitudes' => $solicitudes, 'pendiente' => $pendiente, 'enproceso' => $enproceso, 'realizado' => $realizado, 'enesperade' => $enesperade,);
        //return $data;
        return view('solicituds.graficass')
        ->with('data', $data);
        //return view('solicituds.graficas', compact($data));
        
    }
    
    public function graficasti()
    {
        //return $tipo;
        $solicitudes = Solicitud::all()->count();
        $peticion = Solicitud::where('tipo', 'peticion')->count();
        $reclamo = Solicitud::where('tipo', 'reclamo')->count();
        $denuncia = Solicitud::where('tipo', 'denuncia')->count();

        $data = array('solicitudes' => $solicitudes, 'peticion' => $peticion, 'reclamo' => $reclamo, 'denuncia' => $denuncia,);
        //return $data;
        return view('solicituds.graficasti')
        ->with('data', $data);
        //return view('solicituds.graficas', compact($data));
        
    }
}
