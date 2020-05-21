<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\CompraBoleto;

class ErrorVerificacionPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $compraBoleto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(CompraBoleto $compraBoleto)
    {
        $this->compraBoleto = $compraBoleto;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.pagos.errorVerificacion')
            ->subject('Priveair - Error en la verificación de su pago');
    }
}
