<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Viaje;

class ActualizarPrecio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $viaje;
    public $precio;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Viaje $viaje, $precio)
    {
        $this->viaje   = $viaje;
        $this->precio  = $precio;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->viaje->update([
            'oferta' => 1,
            'precio_boleto' => $this->precio
        ]);

        EnvioCorreosInteresadosViaje::dispatch($this->viaje)->onQueue('notificaciones');
        
        $data = [
            'id_viaje'   => $this->viaje->id,
            'type'       => 'vuelo_precio_actualizado'
        ];

        EnvioPushNotifitionsPrecioBoleto::dispatch(
            $this->viaje,
            "Oferta en el vuelo {$this->viaje->origen->nombre}-{$this->viaje->destino->nombre}. Fecha: {$this->viaje->fecha}",
            $data
        )->onQueue('notificaciones');
    }
}
