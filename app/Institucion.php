<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    public function solicitudes()
    {
        return $this->hasMany('App\Solicitud');
    }
}
