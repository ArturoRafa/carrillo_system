<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    public $incrementing = false;

    protected $primaryKey = 'cedula';

    protected $table = 'usuarios';

    protected $fillable = [
    'email', 'nombre', 'cedula', 'password', 'tipo_usuario','telefono', 'status_delete'
    ];

    protected $hidden = ['password', 'status_delete'];

   

    public function setPasswordAttribute($clave) {
        $this->attributes['password'] = bcrypt($clave);
    }



    public function bauche() {
        return $this->hasMany(Bauche::class, 'cedula_usuario', 'cedula');
    }



    public function factura(){
        return $this->hasMany(Factura::class,'cedula_usuario','cedula');
    }
    

    public function notificaciones() {
        return $this->hasMany(Notificacion::class, 'cedula_usuario', 'cedula');
    }



}
