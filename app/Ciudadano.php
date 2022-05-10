<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudadano extends Model
{
    public function beneficiarios()
    {
        return $this->belongsToMany('App\Beneficiario')->using('App\Beneficiario')->withPivot([
            'ciudadano_id',
        ]);
    }

    public function involucrados()
    {
        return $this->belongsToMany('App\Solicitud', 'beneficiarios', 'ciudadano_id', 'solicitud_id')->withPivot('status');
    }
    
}
