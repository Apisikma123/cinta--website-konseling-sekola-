<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\TeacherApproval;
use App\Services\OtpService;
use App\Services\RecaptchaService;
use App\Models\OtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private RecaptchaService $recaptchaService;

    public function __construct(RecaptchaService $recaptchaService)
    {
        $this->recaptchaService = $recaptchaService;
    }

    // Tampilkan form daftar guru
    public function showRegisterForm()
    {
        $schools = School::where('is_active', true)->orderBy('name')->get();
        return view('auth.register-teacher', compact('schools'));
    }

    // Daftar guru (dengan verifikasi rahasia)
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

        // Only require recaptcha_token if reCAPTCHA is enabled
        if (config('recaptcha.enabled', false)) {
            $rules['recaptcha_token'] = 'required|string';
        }

        $request->validate($rules);

        // Verify reCAPTCHA token only if enabled
        if (config('recaptcha.enabled', false)) {
            if (!$this->recaptchaService->verify($request->string('recaptcha_token'), 0.5)) {
                Log::warning('reCAPTCHA verification failed for registration attempt', [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                ]);
                throw ValidationException::withMessages([
                    'email' => 'Verifikasi keamanan gagal. Silakan coba lagi.',
                ]);
            }
        }

        // Cek jawaban rahasia (hanya guru yang tahu)
        if ($request->verification_answer !== env('TEACHER_SECRET_ANSWER', 'rahasia123')) {
            return back()->withErrors(['verification_answer' => 'Jawaban salah. Hanya guru yang tahu.']);
        }

        // Buat akun (belum disetujui admin)
        $school = School::findOrFail($request->school_id);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'school' => $school->name,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'is_approved' => false,
        ]);

        TeacherApproval::create([
            'user_id' => $user->id,
            'verification_question' => 'Secret question',
            'verification_answer' => $request->verification_answer
        ]);

        // Kirim OTP menggunakan OtpService
        try {
            $otp = app(OtpService::class)->generateForEmail($user->email, $user->id);
            // Simpan timestamp dengan benar
            session([
                'otp_sent_at' => $otp->created_at->timestamp,
                'pending_email' => $user->email
            ]);
            $flash = ['success' => 'Cek email Anda untuk kode OTP.'];
        } catch (\Exception $e) {
            // Jika cooldown, gunakan timestamp dari OTP yang ada
            $existing = OtpVerification::where('email', $user->email)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            $sentAt = $existing?->created_at?->timestamp ?? now()->timestamp;

            // Jika timestamp di masa depan, clamp ke now()
            if ($sentAt > now()->timestamp) {
                Log::warning("Clamping otp_sent_at for {$user->email} from future created_at {$existing?->created_at}");
                $sentAt = now()->timestamp;
            }

            session([
                'otp_sent_at' => $sentAt,
                'pending_email' => $user->email
            ]);

            $retry = $e->getCode() ?: 60;
            $flash = ['warning' => $e->getMessage() . ' (' . $retry . 's)'];
        }

        return redirect()->route('verify.otp.form')->with($flash);
    }

    // Resend OTP (AJAX)
    public function resendOtp(Request $request)
    {
        $email = session('pending_email') ?? $request->input('email');
        if (! $email) {
            return response()->json(['error' => 'Tidak ada email yang dapat dikirim OTP.'], 400);
        }

        try {
            app(OtpService::class)->generateForEmail($email);
        } catch (\Exception $e) {
            $retry = $e->getCode() ?: 60;
            return response()->json(['message' => $e->getMessage(), 'retry_after' => $retry], 429);
        }

        session(['otp_sent_at' => now()->timestamp]);
        return response()->json(['message' => 'OTP terkirim.']);
    }

    // Display login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Tampilkan form verifikasi OTP
    public function showOtpForm()
    {
        if (!session('pending_email')) {
            return redirect('/register/teacher');
        }
        return view('auth.verify-otp');
    }

    // Verifikasi OTP & set password
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
            return redirect('/register/teacher');
        }

        $ok = app(OtpService::class)->verify($email, (string) $request->otp);
        if (!$ok) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau kadaluarsa.']);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            // mark email as verified
            $user->email_verified_at = now();
            $user->save();

            // clear pending email
            session()->forget('pending_email');

            // if admin already approved, send them to login immediately
            if ($user->is_approved) {
                return redirect()->route('login')->with('success', 'Email terverifikasi! Akun Anda telah disetujui. Silakan login.');
            }

            // otherwise show the success page that explains waiting for admin approval
            return redirect()->route('verify.success')->with('verified_name', $user->name);
        }

        return redirect()->route('login')->with('success', 'Email terverifikasi! Menunggu persetujuan admin.');
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

        // Only require recaptcha_token if reCAPTCHA is enabled
        if (config('recaptcha.enabled', false)) {
            $rules['recaptcha_token'] = 'required|string';
        }

        $request->validate($rules);

        // Verify reCAPTCHA token only if enabled
        if (config('recaptcha.enabled', false)) {
            if (!$this->recaptchaService->verify($request->string('recaptcha_token'), 0.5)) {
                Log::warning('reCAPTCHA verification failed for login attempt', [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                ]);
                return back()->withErrors(['email' => 'Verifikasi keamanan gagal. Silakan coba lagi.']);
            }
        }

        // Cek credentials tanpa login
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        // Cek approval untuk teacher
        if ($user->role === 'teacher' && !$user->is_approved) {
            return back()->withErrors(['email' => 'Akun Anda belum disetujui admin.']);
        }

        // Jika role adalah admin, langsung login tanpa OTP
        if ($user->role === 'admin') {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->to('/admin/dashboard');
        }

        // Untuk guru (teacher), kirim OTP dan redirect ke verifikasi
        session(['login_user_id' => $user->id]);

        // Kirim OTP ke email
        try {
            app(OtpService::class)->generateForEmail($user->email, $user->id);
            session(['otp_sent_at' => now()->timestamp]);
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
        return view('auth.login-otp');
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

        $ok = app(OtpService::class)->verify($user->email, (string) $request->otp);
        if (!$ok) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau kadaluarsa.']);
        }

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

        app(OtpService::class)->generateForEmail($request->email);
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

        $ok = app(OtpService::class)->verify($request->email, (string) $request->otp);
        if (!$ok) {
            return response()->json(['error' => 'OTP salah'], 400);
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }
}