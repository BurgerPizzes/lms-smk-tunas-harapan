<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     * Only allow users with 'admin' role to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasRole('admin')) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Admin.');
        }

        return $next($request);
    }
}
