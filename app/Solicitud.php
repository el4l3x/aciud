<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    public function organismo()
    {
        return $this->belongsTo('App\Organismo');
    }

    public function ciudadano()
    {
        return $this->belongsTo('App\Ciudadano');
    }
}
