<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleManager
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role user sesuai dengan yang diminta di route
        // Gunakan strtolower untuk menghindari kesalahan huruf besar/kecil
        if (strtolower($request->user()->role) !== strtolower($role)) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
