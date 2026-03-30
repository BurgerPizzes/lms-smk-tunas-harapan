<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSiswa
{
    /**
     * Handle an incoming request.
     * Only allow users with 'siswa' role to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasRole('siswa')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Siswa.');
        }

        return $next($request);
    }
}
