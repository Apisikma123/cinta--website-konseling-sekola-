<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class UpdateUserLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Update last_activity for authenticated users (using direct DB update for performance)
        if (auth()->check()) {
            User::where('id', auth()->id())->update(['last_activity' => now()]);
        }

        return $next($request);
    }
}
