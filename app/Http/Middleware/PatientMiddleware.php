<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PatientMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = $request->user();

        abort_unless(
            $user && $user->role === 'patient',
            403
        );

        if ($user->status !== 'approved') {

            $userId = $user->id;

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $request->session()->put(
                'registration_user_id',
                $userId
            );

            return redirect()->route('registration.status');
        }

        return $next($request);
    }
}