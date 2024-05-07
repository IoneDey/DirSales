<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class Checkroles {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response {
        //dd(Auth::user()->username, $roles);
        if (!in_array(Auth::user()->roles ?? '', $roles)) {
            // Jika pengguna tidak memiliki role yang diperlukan, redirect ke halaman utama
            return redirect('/');
        }

        return $next($request);
    }
}
