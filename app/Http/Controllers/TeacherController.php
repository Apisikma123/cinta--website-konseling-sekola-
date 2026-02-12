<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\School;
use App\Models\OtpVerification;
use App\Services\OtpService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function home()
    {
        $teacher = auth()->user();

        $reports = Report::with([
            'detail.handledBy:id,name'
        ])
        ->when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
        ->latest()
        ->take(3)
        ->get();

        $totalReports = Report::when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
            ->count();
        $studentCount = Report::when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
            ->distinct('nama_murid')
            ->count('nama_murid');

        $statusChart = [
            'baru' => Report::where('status', Report::STATUS_BARU)
                ->when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
                ->count(),
            'diproses' => Report::where('status', Report::STATUS_DIPROSES)
                ->when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
                ->count(),
            'selesai' => Report::where('status', Report::STATUS_SELESAI)
                ->when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
                ->count(),
        ];

        $dailyReports = Report::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
            ->groupBy('date')
            ->orderBy('date')
            ->limit(7)
            ->get();

        return view('teacher.dashboard', compact('reports', 'totalReports', 'studentCount', 'statusChart', 'dailyReports', 'teacher'));
    }

    public function reports()
    {
        $teacher = auth()->user();

        $reports = Report::with([
            'detail.handledBy:id,name'
        ])
        ->when($teacher->school, fn($q) => $q->where('nama_sekolah', $teacher->school))
        ->latest()
        ->paginate(10);

        return view('teacher.reports', compact('reports', 'teacher'));
    }

    public function settings()
    {
        $user = auth()->user();
        return view('teacher.settings', compact('user'));
    }

    public function profile()
    {
        $user = auth()->user();
        $teacher = $user;
        $reportsCount = Report::where('nama_sekolah', $teacher->school)->count();
        return view('teacher.profile', compact('user', 'teacher', 'reportsCount'));
    }

    public function showReport(Report $report)
    {
        $this->authorize('view', $report);

        return view('teacher.report-detail', compact('report'));
    }

    public function updateSettings(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB source
        ]);

        // Filter out null values to avoid overwriting with null if field not present in form
        $data = array_filter($data, fn($value) => !is_null($value));

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            $filename = 'profile-photos/' . time() . '_' . \Illuminate\Support\Str::random(10) . '.jpg';

            // Check if directory exists
            if (!file_exists(public_path('storage/profile-photos'))) {
                mkdir(public_path('storage/profile-photos'), 0755, true);
            }

            // Use Intervention Image to resize and compress
            // Resize to max 800px width/height, maintain aspect ratio
            $image = \Intervention\Image\Laravel\Facades\Image::read($photo)
                ->scale(width: 800);

            // Compress options
            $encoded = $image->toJpeg(90);

            // Simple loop to ensure < 1MB (1048576 bytes)
            $quality = 90;
            while (strlen($encoded) > 1048576 && $quality > 10) {
                $quality -= 10;
                $encoded = $image->toJpeg($quality);
            }

            // Save to storage/app/public which is linked to public/storage
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, (string) $encoded);

            // Delete old photo if exists
            if ($user->profile_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }

            $data['profile_photo'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Informasi berhasil diperbarui.');
    }

    // Change Password - Step 1: Request OTP
    public function changePasswordRequest(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
        }

        // Generate OTP dan simpan timestamp
        try {
            $otp = $this->otpService->generateForEmail($user->email, $user->id);
            session([
                'pending_password_change' => Hash::make($request->password),
                'otp_sent_at' => $otp->created_at->timestamp,
                'otp_email' => $user->email
            ]);
        } catch (\Exception $e) {
            // Jika cooldown, tetap lanjutkan dengan OTP yang ada
            session([
                'pending_password_change' => Hash::make($request->password),
                'otp_sent_at' => time(),
                'otp_email' => $user->email
            ]);
        }

        return redirect()->route('teacher.verify-otp-password')
            ->with('info', 'Kode OTP telah dikirim ke email Anda. Masukkan kode untuk melanjutkan.');
    }

    // Verify OTP for password change
    public function verifyOtpPassword(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('teacher.verify-otp-password');
        }

        $request->validate(['otp' => 'required|numeric|digits:6']);

        $user = auth()->user();
        $isValid = $this->otpService->verify($user->email, $request->otp);

        if (!$isValid) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah expired.']);
        }

        // Update password
        $user->update(['password' => session('pending_password_change')]);
        session()->forget('pending_password_change');
        session()->forget('otp_sent_at');

        return redirect()->route('teacher.settings')
            ->with('success', 'Password berhasil diubah. Silakan login kembali dengan password baru.');
    }

    // Resend OTP for password change
    public function resendOtpPassword(Request $request)
    {
        $user = auth()->user();

        try {
            $this->otpService->generateForEmail($user->email, $user->id);
            session(['otp_sent_at' => time()]);

            return response()->json(['success' => true, 'message' => 'OTP baru telah dikirim.']);
        } catch (\Exception $e) {
            $retryAfter = $e->getCode() > 0 ? $e->getCode() : 60;
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'retry_after' => $retryAfter
            ], 429);
        }
    }

    // Change Email - Step 1: Request OTP
    public function changeEmailRequest(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();

        // Generate OTP dan simpan timestamp
        try {
            $otp = $this->otpService->generateForEmail($user->email, $user->id);
            session([
                'pending_email_change' => $request->new_email,
                'otp_sent_at' => $otp->created_at->timestamp,
                'otp_email' => $user->email
            ]);
        } catch (\Exception $e) {
            // Jika cooldown, tetap lanjutkan dengan OTP yang ada
            session([
                'pending_email_change' => $request->new_email,
                'otp_sent_at' => time(),
                'otp_email' => $user->email
            ]);
        }

        return redirect()->route('teacher.verify-otp-email')
            ->with('info', 'Kode OTP telah dikirim ke email lama Anda. Masukkan kode untuk melanjutkan.');
    }

    // Verify OTP for email change
    public function verifyOtpEmail(Request $request)
    {
        if ($request->method() === 'GET') {
            return view('teacher.verify-otp-email');
        }

        $request->validate(['otp' => 'required|numeric|digits:6']);

        $user = auth()->user();
        $isValid = $this->otpService->verify($user->email, $request->otp);

        if (!$isValid) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah expired.']);
        }

        // Update email
        $newEmail = session('pending_email_change');
        $user->update(['email' => $newEmail]);
        session()->forget('pending_email_change');
        session()->forget('otp_sent_at');

        return redirect()->route('teacher.settings')
            ->with('success', 'Email berhasil diubah.');
    }

    // Resend OTP for email change
    public function resendOtpEmail(Request $request)
    {
        $user = auth()->user();

        try {
            $this->otpService->generateForEmail($user->email, $user->id);
            session(['otp_sent_at' => time()]);

            return response()->json(['success' => true, 'message' => 'OTP baru telah dikirim.']);
        } catch (\Exception $e) {
            $retryAfter = $e->getCode() > 0 ? $e->getCode() : 60;
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'retry_after' => $retryAfter
            ], 429);
        }
    }

    // Manage secret code untuk sekolah
    public function secretManagement()
    {
        $user = auth()->user();
        $schoolName = $user->school;

        if (!$schoolName) {
            return back()->with('error', 'Anda belum terdaftar di sekolah manapun.');
        }

        $school = School::where('name', $schoolName)->firstOrFail();

        $secret = (object) [
            'code' => $school->secret_code,
            'updated_at' => $school->secret_code_generated_at ?? $school->updated_at
        ];

        return view('teacher.secret-management', compact('school', 'user', 'secret'));
    }

    public function generateSecretCode(Request $request)
    {
        $user = auth()->user();
        $schoolName = $user->school;
        if (!$schoolName) {
            return back()->with('error', 'Anda belum terdaftar di sekolah manapun.');
        }

        $school = School::where('name', $schoolName)->firstOrFail();

        // Generate 4 digit random code
        $code = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $school->update([
            'secret_code' => $code,
            'secret_code_generated_at' => now(),
        ]);

        return back()->with('success', 'Kode rahasia baru berhasil dibuat: <strong class="text-2xl">' . $code . '</strong>. Bagikan kode ini kepada murid Anda.');
    }

    // Show testimonials for approval
    public function testimonials()
    {
        $teacher = auth()->user();

        // Fetch testimonials with related report then filter in PHP to allow
        // flexible matching between teacher->school and report->nama_sekolah.
        $testimonials = \App\Models\Testimonial::with('report')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($t) use ($teacher) {
                if (!$t->report) return false;
                if (empty($teacher->school)) return false;
                $reportSchool = strtolower($t->report->nama_sekolah ?? '');
                $teacherSchool = strtolower($teacher->school);

                // Exact containment quick check
                if (str_contains($reportSchool, $teacherSchool) || str_contains($teacherSchool, $reportSchool)) {
                    return true;
                }

                // Token-based matching: if any substantial token appears in the other string
                $toksTeacher = preg_split('/[^a-z0-9]+/i', $teacherSchool);
                foreach ($toksTeacher as $tok) {
                    $tok = trim($tok);
                    if (strlen($tok) < 3) continue;
                    if (str_contains($reportSchool, $tok)) return true;
                }

                $toksReport = preg_split('/[^a-z0-9]+/i', $reportSchool);
                foreach ($toksReport as $tok) {
                    $tok = trim($tok);
                    if (strlen($tok) < 3) continue;
                    if (str_contains($teacherSchool, $tok)) return true;
                }

                return false;
            });

        $pending = $testimonials->where('is_approved', false);
        $approved = $testimonials->where('is_approved', true);

        // If nothing matched for this teacher (possible name mismatch),
        // fall back to showing global approved testimonials so teacher can review.
        $fallbackUsed = false;
        if ($pending->isEmpty() && $approved->isEmpty()) {
            $fallbackUsed = true;
            $approved = \App\Models\Testimonial::with('report')
                ->where('is_approved', true)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        return view('teacher.testimonials', compact('pending', 'approved', 'fallbackUsed'));
    }
}
