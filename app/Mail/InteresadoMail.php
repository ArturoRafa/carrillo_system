<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Interesado;
use App\User;
use App\Viaje;

class InteresadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $interesado;
    public $usuario;
    public $viaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $usuario, Interesado $interesado, Viaje $viaje)
    {
        $this->interesado = $interesado;
        $this->usuario    = $usuario;
        $this->viaje      = $viaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Priveair - Interesado')
            ->markdown('emails.interesado');
    }
}
