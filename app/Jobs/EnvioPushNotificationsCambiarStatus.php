<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Viaje;
use App\Notificacion;

class EnvioPushNotificationsCambiarStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $viaje;
    public $mensaje;
    public $tipo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Viaje $viaje, $mensaje, $tipo )
    {
        $this->viaje   = $viaje;
        $this->mensaje = $mensaje;
        $this->tipo    = $tipo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $_viaje   = $this->viaje;
        $_mensaje = $this->mensaje;
        $data = json_encode([
            'type'     => $this->tipo,
            'viaje'    => $this->viaje->toArray()
        ]);
        
        $request = $this->viaje->compraBoletos
        ->filter(function($item, $key){
            return !is_null($item->user->expo_token);
        })
        ->filter(function($item, $key){
            $boletos = $item->boletos;
            $res = false;
            foreach ($boletos as $key => $value) {
                if ($value->status == '1' || $value->status == '3' || $value->status == '4') {
                    $res = true;
                }
            }
            return $res;
        })
        ->map(function($item, $key) use ($data, $_mensaje) {
            $user         = $item->user;
            $arr          = [];
            $arr['to']    = $user->expo_token;
            $arr['sound'] = 'default';
            $arr['body']  = $_mensaje;
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
            'descripcion' => $this->mensaje,
            'fecha'       => \Carbon\Carbon::now()->toDateString(),
            'id_viaje'    => $this->viaje->id,
            'payload'     => $data,
            'tipo'        => '2'
        ]);
    }
}
