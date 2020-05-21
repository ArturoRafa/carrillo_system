<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Aeropuerto;
use App\User;

class EnvioPushNotifitionsNuevaRutaViaje implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $origen;
    public $destino;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Aeropuerto $origen, Aeropuerto $destino)
    {
        $this->origen  = $origen;
        $this->destino = $destino;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Cuentos los usuarios no excluidos
        $countUsers = User::whereNotNull('expo_token')
            ->count();

        // cuento las veces que debo hacer el ciclo
        $offset = 100;
        $times  = (int) ceil($countUsers / $offset);
        $data = json_encode( [
            'type'    => 'nueva_ruta',
            'origen'  => $this->origen->toArray(),
            'destino' => $this->destino->toArray(),
        ]);

        for ($i=0; $i < $times; $i++) { 
            $request = User::whereNotNull('expo_token')
                ->take($offset)
                ->offset($i * $offset)
                ->get()->map(function($item, $key) use ($data){
                    $item->notificaciones()->create([
                        'descripcion' => "Nueva ruta de vuelo disponible: {$this->origen->nombre}-{$this->destino->nombre}.",
                        'fecha'       => \Carbon\Carbon::now()->toDateString(),
                        'tipo'        => '0',
                        'payload'     => $data
                    ]);
                    $arr          = [];
                    $arr['to']    = $item->expo_token;
                    $arr['sound'] = 'default';
                    $arr['body']  = "Nueva ruta de vuelo disponible: {$this->origen->nombre}-{$this->destino->nombre}.";
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
        }
    }
}
