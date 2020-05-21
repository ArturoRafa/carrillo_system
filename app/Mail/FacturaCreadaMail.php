<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\DetalleFactura;
use App\Factura;

class FacturaCreadaMail extends Mailable
{
    use Queueable, SerializesModels;
    public $factura;
    public $detalle;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Factura $factura) {

        $this->factura = $factura;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject('Exxalted - Factura Generada')
        ->markdown('emails.facturaCreada');
    }
}
