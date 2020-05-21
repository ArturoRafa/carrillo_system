<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Mail\ReciboPagoBoletos;
use App\Mail\PagoRechazadoMail;

use App\Traits\BitacoraTrait;

use App\Webhook;

use App\CompraBoleto;
use App\User;

class WebhookController extends Controller
{

    use BitacoraTrait;
    /**
     * Handle a Stripe webhook.
     *
     * @param  array  $payload
     * @return Response
     */
    public function handleInvoicePaymentSucceeded(Request $request)
    {
        $payload = $request->input();

        // // Pago exitoso
        if ($payload['type'] == 'charge.succeeded') {
            $metadata = $payload['data']['object']['metadata'];

            $idCompraBoleto = $metadata['id'];
            $emailUsuario   = $metadata['email_usuario'];

            $compraBoleto = CompraBoleto::find($idCompraBoleto);
            $usuario = User::find($emailUsuario);

            $compraBoleto->boletos->map(function($item, $key) {
                $item->pagar();

                return;
            });

            Mail::to($usuario->email)
                ->queue(new ReciboPagoBoletos($usuario, $compraBoleto));

            $viaje = $compraBoleto->viaje;

            $totalVentas    = $viaje->boletos()->where('status', '1')->sum('precio');
            $cantidadVentas = $viaje->boletos()->where('status', '1')->count();

            if ($viaje->monto_minimo <= $totalVentas || $cantidadVentas >= $viaje->minimo_asientos) {
                // Se actualiza el viaje a confirmado porque se alcanzó
                // el mínimo de asiento o ventas
                $viaje->update([
                    'status' => 1
                ]);
            }

        }

        // // Pago fallido
        if ($payload['type'] == 'charge.failed') {
            $response = $payload['data']['object'];
            $metadata = $payload['data']['object']['metadata'];

            $idCompraBoleto = $metadata['id'];
            $emailUsuario   = $metadata['email_usuario'];

            $compraBoleto = CompraBoleto::find($idCompraBoleto);
            $usuario      = User::find($emailUsuario);

            $compraBoleto->boletos->map(function($item, $key) {
                $item->cancelar();

                return;
            });

            if ($compraBoleto->uso_saldo > 0) {
                $saldo = $usuario->saldo;
                $usuario->update([
                    'saldo' => ($saldo + $compraBoleto->uso_saldo)
                ]);
            }

            Mail::to($usuario->email)
                ->queue(new PagoRechazadoMail($response['outcome']['seller_message']));
            
            /**
             * Obtener id del compra_boleto
             * cambiar status a cancelado
             * enviar correo de rechazo de pago
             */
        }

        return response()->json(['success' => 'success'], 200);
    }
}