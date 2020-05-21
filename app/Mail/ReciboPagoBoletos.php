<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;
use App\CompraBoleto;

class ReciboPagoBoletos extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $compraBoleto;
    public $boletos;
    public $viaje;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $usuario, CompraBoleto $compraBoleto)
    {
        $this->usuario      = $usuario;
        $this->compraBoleto = $compraBoleto;
        $this->boletos      = $compraBoleto->boletos;
        $this->viaje        = $compraBoleto->viaje;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Priveair - Confimación de pago')
            ->markdown('emails.pagos.pagoBoletos');
    }
}
