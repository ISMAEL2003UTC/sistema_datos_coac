<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Hola {{ $usuario->nombre_completo }}</h2>

    <p>Has sido registrado en el sistema. Para activar tu cuenta, confirma tu correo electrónico:</p>

    <a href="{{ url('/verificar-correo/'.$usuario->email_verificacion_token) }}"
       style="padding:10px 15px;background:#0d6efd;color:#fff;text-decoration:none;">
        Verificar correo
    </a>

    <p>Si no solicitaste este registro, ignora este mensaje.</p>
</body>
</html>
