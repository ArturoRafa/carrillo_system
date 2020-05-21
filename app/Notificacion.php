<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    /*const CREATED_AT = 'fecha';*/

    protected $table = 'notificaciones';

    protected $fillable = ['cedula','id_bauche','tipo', 'estado'];

    public function bauches() {
        return $this->belongsTo(Bauche::class, 'id_bauche', 'id');
    }
}
