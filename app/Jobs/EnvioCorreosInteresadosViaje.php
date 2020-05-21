<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

use App\Mail\OfertaInteresadoMail;

use App\Viaje;

class EnvioCorreosInteresadosViaje implements ShouldQueue
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
        $interesados = $this->viaje->interesados;

        foreach ($interesados as $key => $value) {
            Mail::to($value->email)
                ->send(new OfertaInteresadoMail($value, $this->viaje));
        }
    }
}
