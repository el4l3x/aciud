<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $casts = [
        'created_at' => 'datetime:d-m-Y',
    ];

    public function organismo()
    {
        return $this->belongsTo('App\Organismo');
    }

    public function institucion()
    {
        return $this->belongsTo('App\Institucion');
    }

    public function anexos()
    {
        return $this->hasMany('App\Anexo');
    }
    
    public function beneficiarios()
    {
        return $this->hasMany('App\Beneficiario');
    }
}
