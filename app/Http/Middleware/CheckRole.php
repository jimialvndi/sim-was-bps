<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Pastikan baris ini ada

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // GUNAKAN Auth::check() (Huruf Besar A, titik dua dua kali)
        if (!Auth::check()) {
            return redirect('login');
        }

        // GUNAKAN Auth::user()
        $userRole = Auth::user()->role;

        // Cek role
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}