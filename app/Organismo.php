<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organismo extends Model
{
    public function solicituds()
    {
        return $this->hasMany('App\Solicitud');
    }
}
