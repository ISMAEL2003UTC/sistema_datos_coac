<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/estilos_registro.css') }}">
</head>
<body>

<div class="container" style="max-width: 420px; margin-top: 80px;">
    <h2>Crear Cuenta</h2>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" required value="{{ old('nombre') }}">
        </div>

        <div class="form-group">
            <label>Apellido</label>
            <input type="text" name="apellido" required value="{{ old('apellido') }}">
        </div>

        <div class="form-group">
            <label>Cédula</label>
            <input type="text" name="cedula" required value="{{ old('cedula') }}">
        </div>

        <div class="form-group">
            <label>Ciudad</label>
            <input type="text" name="ciudad" value="{{ old('ciudad') }}">
        </div>

        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" value="{{ old('direccion') }}">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="{{ old('email') }}">
        </div>

        <div class="form-group">
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Registrarse
        </button>

        <p style="margin-top:15px; text-align:center;">
            ¿Ya tienes cuenta? 
            <a href="{{ route('login') }}">Iniciar sesión</a>
        </p>
    </form>
</div>

</body>
</html>
