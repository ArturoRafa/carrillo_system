<?php

namespace App;

use Illuminate\Database\Eloquent\Model;



class Producto extends Model
{
	protected $table = 'productos';
 
    protected $fillable = ['codigo_barras', 'estado','precio_venta','precio_compra','marca','modelo','color','garantia','descripcion','imagen','tipo_producto','cantidad_disponible'];

    public function detalleFactura(){
		return $this->hasMany(DetalleFactura::class, 'id_producto','id');
	}
    
}
