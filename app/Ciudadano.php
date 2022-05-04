<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudadano extends Model
{
    public function beneficiarios()
    {
        return $this->hasMany('App\Beneficiario');
    }
    
}
