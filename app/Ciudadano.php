<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudadano extends Model
{
    public function solicituds()
    {
        return $this->hasMany('App\Solicitud');
    }
}
