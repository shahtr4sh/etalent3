<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAppPanelAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Default: pemohon sahaja
        if ($user && $user->hasRole('pemohon')) {
            return $next($request);
        }

        abort(403);
    }
}
