<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
        return $this->hasManyThrough(
        'App\Ciudadano', // Modelo destino
        'App\Beneficiario', // Modelo intermedio
        'solicitud_id', // Clave foránea en la tabla intermedia
        'id', // Clave foránea en la tabla de destino
        'id', // Clave primaria en la tabla de origen
        'id', // Clave primaria en la tabla intermedia
        );
    }

    public function involucrados()
    {
        return $this->hasMany('App\Beneficiario');
    }
}
