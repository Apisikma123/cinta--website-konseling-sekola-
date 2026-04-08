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
            // Check if OTP verified
            if (!Auth::user()->otp_verified) {
                Auth::logout();
                return redirect('/login')->with('error', 'Email Anda belum diverifikasi. Silakan selesaikan verifikasi OTP.');
            }

            // Check if approved by admin
            if (Auth::user()->approval_status !== 'approved') {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun Anda belum disetujui admin.');
            }

            // Check if account is active
            if (Auth::user()->is_active === false) {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun Anda dinonaktifkan. Hubungi admin.');
            }
        }
        return $next($request);
    }
}