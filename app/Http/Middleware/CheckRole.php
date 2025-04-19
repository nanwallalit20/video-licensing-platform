<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , ...$roles): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }
        // Check if user has any of the allowed roles
        if (!in_array($request->user()->getUserProfile->role_id->value, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
