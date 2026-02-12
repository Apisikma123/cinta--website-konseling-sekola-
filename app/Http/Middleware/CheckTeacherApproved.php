<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTeacherApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'teacher') {
            if (!Auth::user()->is_approved) {
                return redirect('/login')->with('error', 'Akun Anda belum disetujui admin.');
            }

            if (Auth::user()->is_active === false) {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
            }
        }
        return $next($request);
    }
}