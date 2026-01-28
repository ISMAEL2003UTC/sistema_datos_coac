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
        $request->validate([
            'nombre_completo' => 'required|min:3',
            'email' => 'required|email|unique:usuarios',
            'password' => 'required|min:6|confirmed',
        ]);

        $usuario = Usuario::create([
            'nombre_completo' => $request->nombre_completo,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'operador', // rol por defecto
            'estado' => 'activo'
        ]);

        Auth::login($usuario);

        return redirect()->route('index');
    }
}
