<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Piloto;
use App\Notificacion;
use App\Viaje;

class EnvioPushNotifitionsPilotoViaje implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $piloto;
    public $viaje;
    // public $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, Piloto $piloto, Viaje $viaje)
    {
        $this->data   = $data;
        $this->piloto = $piloto;
        $this->viaje  = $viaje;
        // $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->piloto->usuario;
        if (!is_null($user->expo_token)) {
            $req = [
                'to'    => $user->expo_token,
                'sound' => 'default',
                'body'  => "Usted ha sido asignado como piloto al viaje {$this->viaje->origen->nombre}-{$this->viaje->destino->nombre} el día {$this->viaje->fecha}",
                'data'  => $this->data,
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
                'descripcion'   => "Usted ha sido asignado como piloto al viaje {$this->viaje->origen->nombre}-{$this->viaje->destino->nombre} el día {$this->viaje->fecha}",
                'fecha'         => \Carbon\Carbon::now()->toDateString(),
                'payload'       => $this->data
            ]);
        }
    }
}
