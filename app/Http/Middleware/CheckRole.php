<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // dd($roles );
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }
        $user = Auth::user();
        if (!isset($user->role)) {
            abort(403, 'There are no specific permissions for this user.');
        }
        if (!in_array($user->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
