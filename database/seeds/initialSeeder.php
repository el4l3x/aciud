<?php

use Illuminate\Database\Seeder;

class initialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Supervisor',
            'rol' => '2',
            'username' => 'Vansuli Mita',
            'password' => Hash::make('00000000')
        ]);
        
        DB::table('users')->insert([
            'name' => 'Atencion al Ciudadano',
            'rol' => '1',
            'username' => 'Natali',
            'password' => Hash::make('00000000')
        ]);
        
        DB::table('users')->insert([
            'name' => 'Atencion al Ciudadano',
            'rol' => '1',
            'username' => 'Yessica',
            'password' => Hash::make('00000000')
        ]);
        
        DB::table('users')->insert([
            'name' => 'Director General',
            'rol' => '3',
            'username' => 'Zapata',
            'password' => Hash::make('00000000')
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Dirección de despacho',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Despacho de alcaldia',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Coordinación de tecnología e informática',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Dirección de desarrollo social',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Dirección de Ingeniería Municipal',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Dirección de servicios Públicos municipales',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Dirección de Catastro y Ejido',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Instituto autónomo de la policía municipal',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Protección civil',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Protección del niño',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Registro civil',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Instituto para la mujer',
        ]);
        
        DB::table('organismos')->insert([
            'nombre' => 'Instituto municipal para la vivienda',
        ]);
    }
}
