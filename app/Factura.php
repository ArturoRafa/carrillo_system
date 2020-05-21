<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
	protected $table = 'factura';

	protected $fillable = ['num_factura','cedula_usuario','email','nombre','fecha_facturacion','email','total','estado'];


	public function detalleFactura(){
		return $this->hasMany(DetalleFactura::class, 'id_factura','id');
	}

	public function user(){
		return $this->belongsTo(User::class,'cedula_usuario', 'cedula');
	}

    //
}
