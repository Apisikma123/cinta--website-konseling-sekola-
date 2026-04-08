<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Redirect to dashboard based on role
            if (auth()->user()->role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            } elseif (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }

        return $next($request);
    }
}
