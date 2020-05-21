<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Viaje;
use App\Notificacion;

class EnvioPushNotificationsFinalizarViaje implements ShouldQueue
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
        $_viaje = $this->viaje;
        $data = json_encode([
            'type'     => 'viaje_finalizado',
            'origen'   => $_viaje->origen->nombre,
            'destino'  => $_viaje->destino->nombre,
            'fecha'    => $_viaje->fecha,
            'hora'     => $_viaje->hora,
            'id_viaje' => $_viaje->id
        ]);
        
        $request = $this->viaje->compraBoletos
        ->filter(function($item, $key){
            return !is_null($item->user->expo_token);
        })
        ->map(function($item, $key) use ($data) {
            $user         = $item->user;
            $arr          = [];
            $arr['to']    = $user->expo_token;
            $arr['sound'] = 'default';
            $arr['body']  = "Su vuelo {$this->viaje->origen->nombre}-{$this->viaje->destino->nombre} ha finalizado.";
            $arr['data'] = $data;

            return $arr;
        });

        $request = json_encode($request);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request,
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
            'descripcion' => 'Su vuelo ha finalizado',
            'fecha'       => \Carbon\Carbon::now()->toDateString(),
            'id_viaje'    => $this->viaje->id,
            'payload'     => $data,
            'tipo'        => '2'
        ]);
    }
}
