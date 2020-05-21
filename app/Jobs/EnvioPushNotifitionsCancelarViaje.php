<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

use App\Mail\VueloCanceladoMail;

use App\Viaje;
use App\Notificacion;


class EnvioPushNotifitionsCancelarViaje implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $viaje;
    public $motivo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Viaje $viaje, $motivo)
    {
        $this->viaje  = $viaje;
        $this->motivo = $motivo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $_motivo = $this->motivo;
        $_viaje  = $this->viaje;
        $data = [
            'motivo'   => $_motivo,
            'origen'   => $_viaje->origen->nombre,
            'destino'  => $_viaje->destino->nombre,
            'fecha'    => $_viaje->fecha,
            'hora'     => $_viaje->hora,
            'type'     => 'vuelo_cancelado',
            'id_viaje' => $_viaje->id
        ];
        $data = json_encode( $data );
        $request = $this->viaje->compraBoletos
            ->filter(function($item, $key){
                return !is_null($item->user->expo_token);
            })
            ->map(function($item, $key) use ($data, $_viaje) {
                $user         = $item->user;
                $arr          = [];
                $arr['to']    = $user->expo_token;
                $arr['sound'] = 'default';
                $arr['body']  = "Su vuelo {$_viaje->origen->nombre}-{$_viaje->destino->nombre} ha sido cancelado. Motivo: {$_viaje->motivo_cancelacion}.";
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
            'descripcion' => "Su vuelo {$_viaje->origen->nombre}-{$_viaje->destino->nombre} ha sido cancelado. Motivo: {$_viaje->motivo_cancelacion}.",
            'fecha'       => \Carbon\Carbon::now()->toDateString(),
            'id_viaje'    => $this->viaje->id,
            'tipo'        => '2',
            'payload'     => $data
        ]);

        $this->viaje->compraBoletos
            ->map(function($item, $key) use ($_viaje) {
                $user = $item->user;
                Mail::to($user->email)
                    ->send(new VueloCanceladoMail($_viaje));
                return;
            });
    }
}
