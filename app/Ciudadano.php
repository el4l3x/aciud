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
    
}
