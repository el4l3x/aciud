<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Solicitud;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    public function chpass()
    {
        return view('auth.passwords.chpass');
    }
    
    public function updatepass(Request $request)
    {
        
        $validatedData = $request->validate([
            'password' => 'required',
            'contraseña' => 'required|confirmed|alpha_num|min:8',
            'contraseña_confirmation' => 'required|alpha_num|min:8',
        ]);

        $user = Auth::user();

        $update = User::find($user->id);
 
        $update->password = Hash::make($request->contraseña);
        
        $update->save();

        $solicitudes = Solicitud::with('institucion')->with('organismo')->with('anexos')->with('involucrados')->get();

        return view('solicituds.index')
            ->with('solicitudes', $solicitudes);
        //return view('home');
    }
}
