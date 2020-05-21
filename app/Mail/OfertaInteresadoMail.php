<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Interesado;
use App\Viaje;

class OfertaInteresadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $interesado;
    public $viaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Interesado $interesado, Viaje $viaje)
    {
        $this->interesado = $interesado;
        $this->viaje      = $viaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // TODO CREAR MARKDOWN
        return $this
            ->subject('Priveair - Oferta en vuelo')
            ->markdown('emails.ofertaInteresado');
    }
}
