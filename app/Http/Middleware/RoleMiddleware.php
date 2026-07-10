<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login dan role-nya sesuai parameter
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Lempar ke halaman error 403 Forbidden jika tidak berhak
            abort(403, 'Anda tidak memiliki akses ke halaman ini.'); 
        }

        return $next($request);
    }
}