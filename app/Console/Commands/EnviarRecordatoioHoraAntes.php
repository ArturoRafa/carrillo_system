<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Notificacion;
use App\SolicitudServicio;
class EnviarRecordatoioHoraAntes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recordatoio:horainicio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorio de Solicitud una hora antes de inicio del servicio';

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
        
        

        
    }
}
