<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\SolicitudServicio;
use App\Notificacion;
use App\User;

class EnvioPushSolicitudAceptada implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $solicitud;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SolicitudServicio $solicitud)
    {
        $this->solicitud = $solicitud;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
            /*$user = $this->solicitud->user;*/
            $user = User::find($this->solicitud->email_usuario);
            if (!is_null($user->expo_token)) {
            $req = [
                'to' => $user->expo_token,
                'sound' => 'default',
                'body' => "Su Solicitud ha sido aceptada",
                'data' => json_encode([
                    'type' => 'solicitud_rechazada',
                    'solicitud' => $this->solicitud
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
        }
        Notificacion::create([
                'email_usuario' => $user->email,
                'descripcion'   => "Su Solicitud  ha sido Aceptada.",
                'fecha'         => \Carbon\Carbon::now()->toDateString(),
                'tipo'          => 1,
                'id_solicitud'      => $this->solicitud->id,
                'payload'       => json_encode([
                    'type' => 'Solicitud_servicio_aceptada',
                    'solicitud_servicio' => $this->solicitud
                ])
            ]);
         

        

        
        //
    }
}
