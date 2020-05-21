<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Viaje;
use App\Notificacion;

class EnviarRecordatorioSieteDiasViaje extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordatorio:siete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorio de vuelos dentro de siete días';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sieteDias = \Carbon\Carbon::now()->addDays(7)->toDateString();

        $viajes = Viaje::where('fecha', $sieteDias)->get();

        $viajes->map(function($viaje, $key) {
            $query = DB::table('viaje')
                ->join('boleto', 'viaje.id', '=', 'boleto.id_viaje')
                ->join('usuario', 'boleto.email_usuario', '=', 'usuario.email')
                ->join('aeropuerto as origen', 'viaje.id_aeropuerto_origen', '=', 'origen.id')
                ->join('aeropuerto as destino', 'viaje.id_aeropuerto_destino', '=', 'destino.id')
                ->where('viaje.id',$viaje->id)
                ->where('boleto.status', 1)
                ->groupBy('usuario.email', 'usuario.expo_token', 'origen.nombre', 'destino.nombre', 'viaje.id', 'viaje.fecha', 'viaje.hora')
                ->select(DB::raw('usuario.email, usuario.expo_token, origen.nombre as origen, destino.nombre as destino, viaje.id, viaje.fecha, viaje.hora'))
                ->get();
            $i = 0;
            while($i < count($query)) {
                $count = 0;
                $arr = [];
                while ($count < 100 && $i < count($query)) {
                    $arr[] = [
                        'to' => $query[$i]->expo_token,
                        'sound' => 'default',
                        'body' => 'Su vuelo es en 7 días',
                        'data' => json_encode([
                            'origen'  => $viaje->origen->nombre,
                            'destino' => $viaje->destino->nombre,
                            'fecha'   => $viaje->fecha,
                            'hora'    => $viaje->hora,
                        ])
                    ];
                    $i = $i + 1;
                    $count = $count + 1;
                }
                $request = json_encode($arr);

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
            Notificacion::create([
                'descripcion' => 'Su vuelo es en 7 días',
                'fecha'       => \Carbon\Carbon::now()->toDateString(),
                'id_viaje'    => $viaje->id,
                'payload'     => json_encode( $viaje->toArray() ),
                'tipo'        => '2'
            ]);
        });
    }
}
