<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Usuario;

class VerificarCorreoUsuario extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $usuario;
    public $url;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;

        // URL segura y correcta (funciona en Railway, local y prod)
        $this->url = route(
            'verificar.correo',
            $this->usuario->email_verificacion_token
        );
    }

    public function build()
    {
        return $this->subject('Verificación de correo electrónico')
                    ->view('emails.verificar_correo');
    }
}
