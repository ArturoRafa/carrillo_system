<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Viaje;
use App\User;

class EnvioPushNotifitionsPrecioBoleto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $viaje;
    public $body;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Viaje $viaje, $body, $data)
    {
        $this->viaje = $viaje;
        $this->body  = $body;
        $this->data  = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $_body = $this->body;
        $_data = $this->data;
        // Excluyo a los que ya compraron
        $exclude = $this->viaje->compraBoletos->map(function($item, $key){
            return $item->email_usuario;
        });

        // Cuentos los usuarios no excluidos
        $countUsers = User::whereNotIn('email', $exclude)
            ->whereNotNull('expo_token')
            ->whereTipoUsuario(0)
            ->count();

        // cuento las veces que debo hacer el ciclo
        $offset = 100;
        $times  = (int) ceil($countUsers / $offset);
        $data = json_encode( $_data );

        for ($i=0; $i < $times; $i++) { 
            $request = User::whereNotIn('email', $exclude)
                ->whereNotNull('expo_token')
                ->whereTipoUsuario(0)
                ->take($offset)
                ->offset($i * $offset)
                ->get()->map(function($item, $key) use ($_body, $data){
                    $item->notificaciones()->create([
                        'descripcion' => $_body,
                        'fecha'       => \Carbon\Carbon::now()->toDateString(),
                        'tipo'        => '0',
                        'payload'     => $data
                    ]);
                    $arr = [];
                    $arr['to'] = $item->expo_token;
                    $arr['sound'] = 'default';
                    $arr['body'] = $_body;
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
