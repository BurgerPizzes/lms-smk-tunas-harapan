<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGuru
{
    /**
     * Handle an incoming request.
     * Only allow users with 'guru' role to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasRole('guru')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Guru.');
        }

        return $next($request);
    }
}
