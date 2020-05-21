<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bauche extends Model
{
   protected $table = 'bauches';

	protected $fillable = ['fecha_ingreso','descripcion','fecha_salida','cedula_usuario','estado','cedula','nombre','telefono','direccion','tipo_equipo','marca','modelo', 'serial','estado_equipo','diagnostico','presupuesto','anticipo','restante','fecha_reparado'];

	public function user(){
		return $this->belongsTo(User::class,'cedula_usuario', 'cedula');
	}
}
