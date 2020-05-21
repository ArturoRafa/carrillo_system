<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\VueloMedida;
use App\Notificacion;

class EnvioPushNotifitionsVueloMedidaCreado implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $vueloMedida;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(VueloMedida $vueloMedida)
    {
        $this->vueloMedida = $vueloMedida;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->vueloMedida->user;

        if (!is_null($user->expo_token)) {
            $this->vueloMedida->load('viaje', 'viaje.origen', 'viaje.destino');
            $origen  = $this->vueloMedida->viaje->origen;
            $destino = $this->vueloMedida->viaje->destino;
            $req = [
                'to' => $user->expo_token,
                'sound' => 'default',
                'body' => "Su vuelo {$origen->ciudad}-{$destino->ciudad} ha sido asignado.",
                'data' => json_encode([
                    'type' => 'vuelo_medida_asignado',
                    'vuelo_medida' => $this->vueloMedida->toArray()
                ])
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode( $req ),
                CURLOPT_HTTPHEADER => array(
                    "Accept: application/json",
                    "Cache-Control: no-cache",
                    "Content-Type: application/json",
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            $viaje = $this->vueloMedida->viaje;

            Notificacion::create([
                'email_usuario' => $user->email,
                'descripcion'   => "Su vuelo medida de {$viaje->origen->nombre} a {$viaje->destino->nombre} ha sido asignado",
                'fecha'         => \Carbon\Carbon::now()->toDateString(),
                'tipo'          => 0,
                'id_viaje'      => $this->vueloMedida->id_viaje,
                'payload'       => json_encode([
                    'type' => 'vuelo_medida_asignado',
                    'vuelo_medida' => $this->vueloMedida->toArray()
                ])
            ]);
        }
    }
}
