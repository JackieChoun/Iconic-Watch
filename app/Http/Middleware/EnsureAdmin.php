<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Autorise uniquement les utilisateurs avec le rôle "admin".
     *
     * Convention actuelle: roles.id = 2 => admin
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ((int) ($user->id_role ?? 1) !== 2) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
