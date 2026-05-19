<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * We check if the user is authenticated, and if their role
     * matches the specific one we require for this route.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  The required role to access this route (e.g. 'admin')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Get the current logged in user
        $user = Auth::user();

        // Check if they have the required role
        if ($user->role !== $role) {
            // Alternatively, you could abort with 403: abort(403, 'Unauthorized access');
            return redirect('/dashboard')->with('error', "You do not have access to the {$role} area.");
        }

        // If authorized, proceed to the requested route
        return $next($request);
    }
}
