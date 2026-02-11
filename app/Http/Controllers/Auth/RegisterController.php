<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validación de los campos según tu tabla
        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'apellido' => 'required|string|min:3|max:100',
            'cedula' => 'required|string|max:30|unique:usuarios',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6|confirmed',
            'ciudad' => 'nullable|string|max:100',
            'direccion' => 'nullable|string|max:255',
        ]);

        // Crear usuario en la base de datos
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'cedula' => $request->cedula,
            'ciudad' => $request->ciudad,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'operador', // rol por defecto
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Loguear al usuario
        Auth::login($usuario);

        return redirect()->route('index');
    }
}
