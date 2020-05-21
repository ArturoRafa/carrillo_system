<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\VueloMedida;
use App\Notificacion;

class EnvioPushNotifitionsVueloMedidaRechazado implements ShouldQueue
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
            $req = [
                'to' => $user->expo_token,
                'sound' => 'default',
                'body' => "Su vuelo solicitado {$this->vueloMedida->origen->nombre}-{$this->vueloMedida->destino->nombre} ha sido rechazado.",
                'data' => json_encode([
                    'type' => 'vuelo_medida_rechazado',
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

            Notificacion::create([
                'email_usuario' => $user->email,
                'descripcion'   => "Su vuelo solicitado {$this->vueloMedida->origen->nombre}-{$this->vueloMedida->destino->nombre} ha sido rechazado.",
                'fecha'         => \Carbon\Carbon::now()->toDateString(),
                'tipo'          => 0,
                'id_viaje'      => $this->vueloMedida->id_viaje,
                'payload'       => json_encode([
                    'type' => 'vuelo_medida_rechazado',
                    'vuelo_medida' => $this->vueloMedida->toArray()
                ])
            ]);

           
        }
    }
}
