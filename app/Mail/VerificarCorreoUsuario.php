<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class VerificarCorreoUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Verificación de correo electrónico')
            ->view('emails.verificar_correo');
    }
}
