<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Check if user has required role
        if (in_array($user->role, $roles)) {
            return $next($request);
        }
        
        // Redirect based on user's role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard')->with('error', 'Unauthorized access.');
            case 'driver':
                return redirect()->route('driver.dashboard')->with('error', 'Unauthorized access.');
            case 'client':
                return redirect()->route('client.dashboard')->with('error', 'Unauthorized access.');
            default:
                abort(403, 'Unauthorized access - You do not have permission to view this page.');
        }
    }
}