<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\TeacherApproval;
use App\Models\OtpVerification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // Tampilkan form daftar guru
    public function showRegisterForm()
    {
        $schools = School::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('auth.register-teacher', compact('schools'));
    }

    // Daftar guru (dengan verifikasi rahasia) - JANGAN simpan ke database
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'school_id' => 'required|exists:schools,id',
            'whatsapp' => 'required|string|max:20',
            'verification_answer' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ];

        $request->validate($rules);

        // Cek jawaban rahasia (hanya guru yang tahu)
        if ($request->input('verification_answer') !== env('TEACHER_SECRET_ANSWER', 'rahasia123')) {
            return back()->withErrors(['verification_answer' => 'Jawaban salah. Hanya guru yang tahu.']);
        }

        // JANGAN buat user di database - simpan ke session saja
        $school = School::findOrFail($request->input('school_id'));

        // Simpan data register ke session (temporary)
        session([
            'register_data' => [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'school' => $school->name,
                'school_id' => $request->input('school_id'),
                'whatsapp' => $request->input('whatsapp'),
                'password' => Hash::make($request->input('password')),
                'verification_answer' => $request->input('verification_answer'),
            ]
        ]);

        // Generate OTP untuk email (tanpa user_id karena user belum di database)
        try {
            app(OtpService::class)->generateForEmail($request->input('email'));
            session([
                'pending_email' => $request->input('email'),
                'otp_sent_at' => now()->timestamp
            ]);
            session()->save(); // Explicitly save session before redirect
        } catch (\Exception $e) {
            // Jika cooldown atau error, tetap ke halaman OTP
            $retry = $e->getCode() ?: 60;
            session(['pending_email' => $request->input('email')]);
            session()->save();
            return back()->withErrors(['email' => $e->getMessage() . ' (' . $retry . 's)']);
        }

        // JANGAN gunakan flash message (bisa trigger auto-redirect di OTP form)
        return redirect()->route('verify.otp.form');
    }

    // Resend OTP (AJAX)
    public function resendOtp(Request $request)
    {
        // Try to get email from multiple sources
        $email = $request->input('email') ?? session('pending_email');
        
        if (! $email) {
            Log::warning('Resend OTP failed: No email provided');
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan. Silakan isi kembali email.'
            ], 200);
        }

        try {
            Log::info("Attempting to resend OTP to {$email}");
            app(OtpService::class)->generateForEmail($email);
            Log::info("OTP resent successfully to {$email}");
            
            // Update session to make sure email is stored
            session(['pending_email' => $email, 'otp_sent_at' => now()->timestamp]);
            session()->save(); // Explicitly save session
            
            return response()->json([
                'success' => true,
                'message' => 'OTP terkirim ke ' . $email
            ], 200);
            
        } catch (\Exception $e) {
            $retry = $e->getCode() ?: 30;
            Log::warning("OTP resend failed for {$email}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'retry_after' => $retry
            ], 200);
        }
    }

    // Display login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Tampilkan form verifikasi OTP (JANGAN redirect ke home)
    public function showOtpForm()
    {
        $email = session('pending_email');
        
        if (!$email) {
            return redirect()->route('register.teacher.form');
        }
        
        return view('auth.verify-otp', compact('email'));
    }

    // Verifikasi OTP & buat user ke database (jika OTP valid)
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus 6 digit angka.',
        ]);

        $email = session('pending_email');
        if (!$email) {
            return redirect()->route('register.teacher.form');
        }

        // Verifikasi OTP
        $ok = app(OtpService::class)->verify($email, (string) $request->input('otp'));
        if (!$ok) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau kadaluarsa.']);
        }

        // OTP VALID - Ambil data dari session dan simpan ke database
        $registerData = session('register_data');
        if (!$registerData) {
            return redirect()->route('register.teacher.form')->withErrors(['error' => 'Data register tidak ditemukan. Silakan daftar ulang.']);
        }

        // Create user dengan status otp_verified = true
        $user = User::create([
            'name' => $registerData['name'],
            'email' => $registerData['email'],
            'school' => $registerData['school'],
            'whatsapp' => $registerData['whatsapp'],
            'password' => $registerData['password'],
            'role' => 'teacher',
            'is_approved' => false,
            'otp_verified' => true,  // OTP sudah terverifikasi
            'approval_status' => 'pending',  // Menunggu approval admin
            'email_verified_at' => now(),
        ]);

        // Create teacher approval record
        TeacherApproval::create([
            'user_id' => $user->id,
            'verification_question' => 'Secret answer',
            'verification_answer' => $registerData['verification_answer']
        ]);

        // Clear session data
        session()->forget(['register_data', 'pending_email', 'otp_sent_at']);

        // Redirect ke success page
        return redirect()->route('verify.success')->with('verified_name', $user->name);
    }

    // Show post-OTP verification success page (waiting approval)
    public function showVerifySuccess()
    {
        $name = session('verified_name') ?? 'Pengguna';
        return view('auth.verify-success', compact('name'));
    }

    // Login guru/admin dengan OTP
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $request->validate($rules);

        // Cek credentials tanpa login
        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // Check if account is deactivated (for all roles)
        if ($user->is_active === false) {
            return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh admin. Silakan hubungi admin untuk informasi lebih lanjut.']);
        }

        // Cek approval untuk teacher
        if ($user->role === 'teacher') {
            // Check if OTP verified
            if (!$user->otp_verified) {
                return back()->withErrors(['email' => 'Email Anda belum diverifikasi. Silakan selesaikan verifikasi OTP terlebih dahulu.']);
            }

            // Check if approved by admin
            if ($user->approval_status !== 'approved') {
                return back()->withErrors(['email' => 'Akun Anda belum disetujui oleh admin. Silakan tunggu persetujuan.']);
            }
        }

        // Jika role adalah admin, langsung login tanpa OTP
        if ($user->role === 'admin') {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->to('/admin/dashboard');
        }

        // Untuk guru (teacher), cek bypass OTP
        $deviceToken = request()->cookie('device_token');
        $otpVerifiedAt = $user->otp_verified_at;
        $otpExpirationHours = config('app.otp_expiration_hours', env('OTP_EXPIRATION_HOURS', 24));

        if ($user->role === 'teacher' && 
            $deviceToken && 
            $deviceToken === $user->last_device_identifier && 
            $otpVerifiedAt && 
            now()->diffInHours($otpVerifiedAt) < $otpExpirationHours) {
            
            Auth::login($user);
            $request->session()->regenerate();
            
            // Perpanjang cookie
            Cookie::queue('device_token', $deviceToken, $otpExpirationHours * 60);
            
            return redirect()->to('/teacher/dashboard');
        }

        // Untuk guru (teacher) jika tidak bypass, kirim OTP dan redirect ke verifikasi
        session(['login_user_id' => $user->id, 'pending_email' => $user->email]);
        session()->save();

        // Kirim OTP ke email
        try {
            app(OtpService::class)->generateForEmail($user->email, $user->id);
            session(['otp_sent_at' => now()->timestamp]);
            session()->save();
        } catch (\Exception $e) {
            $retry = $e->getCode() ?: 60;
            return back()->withErrors(['email' => $e->getMessage() . ' (' . $retry . 's)']);
        }

        // Redirect ke halaman verifikasi OTP login
        return redirect()->route('login.otp.form')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // Tampilkan form verifikasi OTP untuk login
    public function showLoginOtpForm()
    {
        if (!session('login_user_id')) {
            return redirect('/login');
        }
        
        $email = session('pending_email');
        return view('auth.login-otp', compact('email'));
    }

    // Verifikasi OTP untuk login
    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus 6 digit angka.',
        ]);

        $userId = session('login_user_id');
        if (!$userId) {
            return redirect('/login');
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget('login_user_id');
            return redirect('/login');
        }

        // Check if account is deactivated before OTP verification
        if ($user->is_active === false) {
            session()->forget('login_user_id');
            return redirect('/login')->withErrors(['email' => 'Akun Anda telah dinonaktifkan oleh admin. Silakan hubungi admin.']);
        }

        $ok = app(OtpService::class)->verify($user->email, (string) $request->input('otp'));
        if (!$ok) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau kadaluarsa.']);
        }

        // Generate persistent device token for OTP bypass
        $deviceToken = \Illuminate\Support\Str::random(60);
        $otpExpirationHours = config('app.otp_expiration_hours', env('OTP_EXPIRATION_HOURS', 24));
        
        $user->update([
            'last_device_identifier' => $deviceToken,
            'otp_verified_at' => now(),
        ]);

        // Set persistent cookie
        Cookie::queue('device_token', $deviceToken, $otpExpirationHours * 60);

        // Login user
        Auth::login($user);
        $request->session()->regenerate();

        // Clear session
        session()->forget('login_user_id');

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->to('/admin/dashboard');
        } elseif ($user->role === 'teacher') {
            return redirect()->to('/teacher/dashboard');
        }

        return redirect()->to('/');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    // Kirim OTP untuk ganti password
    public function requestPasswordReset(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        app(OtpService::class)->generateForEmail($request->input('email'));
        return response()->json(['message' => 'OTP dikirim ke email Anda.']);
    }

    // Ganti password dengan OTP
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:6|confirmed'
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus 6 digit angka.',
        ]);

        $ok = app(OtpService::class)->verify($request->input('email'), (string) $request->input('otp'));
        if (!$ok) {
            return response()->json(['error' => 'OTP salah'], 400);
        }

        User::where('email', $request->input('email'))->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }
}