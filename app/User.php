<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public $incrementing = false;

    protected $primaryKey = 'email';

    protected $table = 'usuario';

    protected $fillable = [
        'email', 'nombres', 'identificacion', 'fecha_nacimiento', 'saldo', 'sexo',
        'celular', 'direccion', 'password', 'estado', 'avatar', 'permiso_correos',
        'pais', 'status', 'tipo_usuario'
    ];

    protected $hidden = ['password'];

    public function setClaveAttribute($clave) {
        $this->attributes['clave'] = bcrypt($clave);
    }

}
