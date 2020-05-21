<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\SolicitudServicio;
class SolicitudAceptadaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $solicitud;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(solicitudServicio $solicitud)
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
        ->subject('Exxalted - Solicitud registrada')
        ->markdown('emails.solicitudServicio');
    }
}
