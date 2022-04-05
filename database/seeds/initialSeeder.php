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
            'name' => 'Vansuli Mita',
            'rol' => '2',
            'username' => 'Supervisor',
            'password' => Hash::make('00000000')
        ]);
        
        DB::table('users')->insert([
            'name' => 'Natali',
            'rol' => '1',
            'username' => 'Atencion al Ciudadano',
            'password' => Hash::make('00000000')
        ]);
        
        DB::table('users')->insert([
            'name' => 'Zapata',
            'rol' => '3',
            'username' => 'Director General',
            'password' => Hash::make('00000000')
        ]);
    }
}
