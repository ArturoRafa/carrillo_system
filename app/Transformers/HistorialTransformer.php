<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

use App\CompraBoleto;

class HistorialTransformer extends TransformerAbstract
{

    // protected $defaultIncludes = [
    //     'viaje',
    // ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(CompraBoleto $compra)
    {
        $calificacion = $compra->viaje->calificacion()
            ->whereEmailUsuario($compra->email_usuario)
            ->first();
        return [
            'id' => $compra->id,
            'fecha_compra' => $compra->created_at->toDateString(),
            'fecha_vuelo' => $compra->viaje->fecha,
            'hora_vuelo' => $compra->viaje->hora,
            'origen' => $compra->viaje->origen->nombre,
            'destino' => $compra->viaje->destino->nombre,
            'asientos' => $compra->boletos()->count(),
            'monto' => $compra->boletos->reduce(function($carry, $boleto){
                return $carry + $boleto->precio;
            }),
            'estado' => $compra->viaje->status,
            'calificacion' => $calificacion ? $calificacion->estrellas : 0,
        ];
    }

    // public function includeViaje(CompraBoleto $compra) {
    //     $viaje = $compra->viaje;

    //     return $this->item($viaje, new ViajeTransformer());
    // }

}
