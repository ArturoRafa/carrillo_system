<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\SolicitudServicio;
class SolicitudRechazadaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $solicitud;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SolicitudServicio $solicitud)
    {
        $this->solicitud = $solicitud;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject('Exxalted - Solicitud Rechazada')
        ->markdown('emails.solicitudServicio');
    }
}
