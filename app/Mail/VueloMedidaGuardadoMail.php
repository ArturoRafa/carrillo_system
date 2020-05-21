<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\VueloMedida;

class VueloMedidaGuardadoMail extends Mailable
{
    use Queueable, SerializesModels;


    public $vuelo;
    public $usuario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(VueloMedida $vuelo)
    {
        $this->vuelo = $vuelo;
        $this->usuario = $vuelo->user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Priveair - Vuelo Medida Guardado')
            ->markdown('emails.vuelomedida.guardado');
    }
}
