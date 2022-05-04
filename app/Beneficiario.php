<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    public function solicitud()
    {
        return $this->belongsTo('App\Solicitud');
    }

    public function ciudadanos()
    {
        return $this->belongsTo('App\Ciudadano');
    }
}
