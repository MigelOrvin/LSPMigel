<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (
            !$request->user() ||
            $request->user()->role !== 'admin' ||
            $request->user()->status !== 'approved'
        ) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return $next($request);
    }
}