<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Jobs\EnvioPushNotificationOfertas;
use App\Jobs\EnvioPushNotificationOfertasDestinosFrecuentes;

use App\User;
use App\Viaje;

class EnviarOfertas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:ofertas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar ofertas a los usuarios';

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
        // Listar los usuarios
        $usuarios = User::all();

        // Buscar viajes, listar por orden de asientos disponibles
        // $idsViajes = DB::table('viaje')
        //     ->join('boleto', 'viaje.id', '=', 'boleto.id_viaje')
        //     ->whereIn('boleto.status', [0, 1])
        //     ->select(DB::raw("viaje.id, (viaje.maximo_asientos - count(boleto.id)) as vendidos"))
        //     ->groupBy('viaje.id')
        //     ->orderBy('vendidos')
        //     ->get()->map(function($item, $key) {
        //         return $item->id;
        //     });
        // $viajes = Viaje::whereIn('id', $idsViajes)->get();

        // Mapear los usuarios
        $usuarios->map(function($item, $key) use ($viajes) {
            $destinosFrecuentes = $item->destinos_frecuentes;

            // No tiene destinos frecuentes
            if ($destinosFrecuentes->isEmpty()) {
                EnvioPushNotificationOfertas::dispatch($item)
                    ->onQueue('notificaciones');
            } else {
                EnvioPushNotificationOfertasDestinosFrecuentes::dispatch($item)
                    ->onQueue('notificaciones');
            }
        });
    }
}
