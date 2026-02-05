<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class OneSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está logueado
        if (Auth::check()) {
            $currentUserId = Auth::id();
            $currentSessionId = Session::getId();
            $currentUserAgent = $request->header('User-Agent');

            // Buscar otras sesiones activas de este usuario en otros dispositivos
            $otherSessions = DB::table('sessions')
                ->where('user_id', $currentUserId)
                ->where('id', '<>', $currentSessionId)
                ->where('user_agent', '<>', $currentUserAgent) // Ignora la misma pestaña
                ->count();

            if ($otherSessions > 0) {
                // Opción 1: Cerrar la sesión anterior (como WhatsApp)
                DB::table('sessions')
                    ->where('user_id', $currentUserId)
                    ->where('id', '<>', $currentSessionId)
                    ->delete();

                // Regenerar ID de sesión para seguridad
                Session::regenerate();
            }
        }

        return $next($request);
    }
}
