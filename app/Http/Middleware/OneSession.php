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
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $currentSessionId = Session::getId();

            // Buscar otras sesiones activas del mismo usuario
            $otherSessions = DB::table('sessions')
                ->where('user_id', Auth::id())
                ->where('id', '<>', $currentSessionId)
                ->count();

            if ($otherSessions > 0) {
                // Cerrar sesión actual si hay otra activa
                Auth::logout();
                Session::flush();

                return redirect('/login')->with('error', 'Tu sesión fue cerrada porque iniciaste sesión en otro dispositivo.');
            }
        }

        return $next($request);
    }
}
