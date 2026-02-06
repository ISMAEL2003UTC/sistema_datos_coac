<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OneSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            $user = Auth::user();
            $currentSessionId = Session::getId();

            // Si hay una sesión distinta a la guardada
            if ($user->session_id && $user->session_id !== $currentSessionId) {

                // 🔥 matar todas las sesiones anteriores
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();

                Session::regenerate();
            }

            // Guardar la sesión válida
            $user->session_id = Session::getId();
            $user->save();
        }

        return $next($request);
    }
}
