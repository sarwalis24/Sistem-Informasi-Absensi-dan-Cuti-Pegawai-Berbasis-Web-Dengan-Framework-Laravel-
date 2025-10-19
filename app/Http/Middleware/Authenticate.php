<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Check if the session has expired
        if (!Auth::check()) {
            // Clear any existing session data
            $request->session()->flush();
            $request->session()->regenerate();

            // Set flash message for expired session
            session()->flash('message', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        return route('loginuser');
    }
}
