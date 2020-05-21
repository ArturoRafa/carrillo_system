<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class ValidateEmailPilotoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $usuario)
    {
        $this->usuario = $usuario;
        $this->url = config('app.url') . "/validar-email/?token={$this->usuario->validate_email_token}&email={$this->usuario->email}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Priveair - Validar Email Piloto')
            ->markdown('emails.validationPiloto');
    }
}
