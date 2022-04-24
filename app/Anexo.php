<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    public function solicitud()
    {
        return $this->belongsTo('App\Solicitud');
    }
}
