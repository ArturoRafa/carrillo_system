<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Viaje;
use App\Notificacion;

class EnvioPushNotificationsViajeCalificado implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $viaje;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Viaje $viaje)
    {
        $this->viaje = $viaje;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $piloto   = $this->viaje->piloto;
        $copiloto = $this->viaje->copiloto;

        $data = json_encode([
            'viaje' => $this->viaje->toArray(),
            'type'  => 'viaje_calificado'
        ]);

        if (!is_null($piloto->usuario->expo_token)) {
            $req = [
                'to'    => $piloto->usuario->expo_token,
                'sound' => 'default',
                'body'  => 'El vuelo ha sido calificado',
                'data'  => $data,
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
                'email_usuario' => $piloto->email_usuario,
                'descripcion'   => 'El vuelo ha sido calificado',
                'fecha'         => \Carbon\Carbon::now()->toDateString(),
                'payload'       => $data
            ]);
        }

        if (!is_null($copiloto) && !is_null($copiloto->usuario->expo_token)) {
            $req = [
                'to'    => $copiloto->usuario->expo_token,
                'sound' => 'default',
                'body'  => 'El vuelo ha sido calificado',
                'data'  => $data,
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
                'email_usuario' => $copiloto->email_usuario,
                'descripcion'   => 'El vuelo ha sido calificado',
                'fecha'         => \Carbon\Carbon::now()->toDateString(),
                'payload'       => $data
            ]);
        }
    }
}
