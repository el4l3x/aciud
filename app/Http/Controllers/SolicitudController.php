<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Solicitud;
use App\Ciudadano;
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
        return view('solicituds.create');
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
				'desarrollo' => 'required'
			]);
	
			if ($validator->fails()) {
				return redirect()->back()
							->withErrors($validator)
							->withInput();
			}

            
			$consulta = Ciudadano::where('ci', $request->ci)->first();
            if (isset($consulta->ci)) {
                return "Ciudadano ya registrado";
                
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
                if ($request->file('fileinput') != null) {
                    $solicitud->anexo = explode('public/', $request->file('fileinput')->store('public'))[1];
                } else {
                    $solicitud->anexo = "default.jpg";
                }
                $solicitud->tipo = $request->tipo;
                $solicitud->desarrollo = $request->desarrollo;
                $solicitud->codigo = "P-".$solicitud->id;
                $solicitud->ciudadano_id = $ciudadano->id;
                $solicitud->organismo_id = 1;
                
                $solicitud->save();
                
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
