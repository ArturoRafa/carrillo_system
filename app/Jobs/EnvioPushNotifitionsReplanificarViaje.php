<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Viaje;
use App\Replanificacion;
use App\Notificacion;

class EnvioPushNotifitionsReplanificarViaje implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $viaje;
    public $replanificacion;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Viaje $viaje, Replanificacion $replanificacion)
    {
        $this->viaje           = $viaje;
        $this->replanificacion = $replanificacion;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $_replanificacion = $this->replanificacion;
        $data             = $_replanificacion->toArray();
        $data['type']     = 'viaje_replanificado';
        $data['origen']   = $_replanificacion->viaje->origen->nombre;
        $data['destino']  = $_replanificacion->viaje->destino->nombre;
        $data['id_viaje'] = $_replanificacion->viaje->id;
        $data             = json_encode( $data );

        $request          = $this->viaje->compraBoletos
            ->filter(function($item, $key){
                return !is_null($item->user->expo_token);
            })
            ->map(function($item, $key) use ($data) {
                $user         = $item->user;
                $arr          = [];
                $arr['to']    = $user->expo_token;
                $arr['sound'] = 'default';
                $arr['body']  = "Su vuelo {$this->viaje->origen->nombre}-{$this->viaje->destino->nombre} ha sido replanificado para el día: {$this->viaje->fecha}.";
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
            'descripcion' => 'Su vuelo ha sido replanificado',
            'fecha'       => \Carbon\Carbon::now()->toDateString(),
            'id_viaje'    => $this->viaje->id,
            'tipo'        => '2',
            'payload'     => $data
        ]);
    }
}
