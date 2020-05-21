<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\SolicitudServicio;
class SolicitudServicioMail extends Mailable
{
    use Queueable, SerializesModels;
    public $solicitudServicio;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SolicitudServicio $solicitudServicio)
    {
        $this->solicitudServicio = $solicitudServicio;
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
        ->subject('Exxalted - Solicitud registrada')
        ->markdown('emails.solicitudServicio');
    }
}
