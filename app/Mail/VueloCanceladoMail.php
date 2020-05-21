<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Viaje;

class VueloCanceladoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $viaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Viaje $viaje)
    {
        $this->viaje = $viaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Priveair - Su viaje ha sido cancelado')
            ->markdown('emails.viaje.cancelado');
    }
}
