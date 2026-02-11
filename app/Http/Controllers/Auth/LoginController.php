<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email', $request->email)
            ->where('estado', 'activo')
            ->first();

        if (!$usuario) {
            return back()->withErrors([
                'email' => 'Usuario no encontrado o inactivo'
            ]);
        }

        if (!password_verify($request->password, $usuario->password)) {
            return back()->withErrors([
                'password' => 'Contraseña incorrecta'
            ]);
        }

        // 🔐 Login
        Auth::login($usuario);

        // 🔥 Matar TODAS las sesiones anteriores
        DB::table('sessions')
            ->where('user_id', $usuario->id)
            ->delete();

        // 🔄 Regenerar sesión
        $request->session()->regenerate();

        // 💾 Guardar la sesión válida
        $usuario->session_id = $request->session()->getId();
        $usuario->save();

        return redirect('/');
    }

    public function logout(Request $request)
    {
        $usuario = Auth::user();

        if ($usuario) {
            $usuario->session_id = null;
            $usuario->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
