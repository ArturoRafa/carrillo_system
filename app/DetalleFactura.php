<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
	protected $table = 'detalle';

	protected $fillable = ['id_factura','id_producto','precio','descripcion','cantidad'];

	public function factura() {
		return $this->belongsTo(Factura::class,'id_factura','id');
	}

	public function producto() {
		return $this->belongsTo(Producto::class,'id_producto','id');
	}
    //
}
