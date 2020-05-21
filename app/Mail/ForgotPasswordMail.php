<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class ForgotPasswordMail extends Mailable
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
        // SE MODIFICARÁ PARA QUE APUNTE AL URL DE LA APP
        $this->url = env('SITE_URL', 'https://api.exxalted.com') . "/forgot-password/?email={$this->usuario->email}&token={$this->usuario->reset_password_token}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.forgotPassword')
        ->subject('Exxalted - Olvidó su contraseña');
    }
}
