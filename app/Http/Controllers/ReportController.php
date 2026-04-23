<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\School;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Services\ReportService;
use Illuminate\Validation\ValidationException;
use App\Events\ReportCreated;

class ReportController extends Controller
{
    // HOMEPAGE = FORM BUAT LAPORAN
    public function index()
    {
        return view('welcome');
    }

    // BUAT LAPORAN (guest)
    public function create(Request $request, ReportService $reportService)
    {
        $request->merge([
            'school_id' => $request->input('school_id'),
            'nama_sekolah' => $request->input('nama_sekolah', $request->input('school')),
            'kelas' => $request->input('kelas', $request->input('class')),
            'isi_laporan' => $request->input('isi_laporan', $request->input('description')),
            'email_murid' => $request->input('email_murid', $request->input('email')),
        ]);

        try {
            $request->validate([
                'school_id' => 'required|exists:schools,id',
                'kelas' => 'required|string|max:100',
                'title' => 'required|string|max:255',
                'nama_murid' => 'required|string|max:255',
                'jenis_laporan' => 'required|string|max:100',
                'isi_laporan' => 'required|string',
                'email_murid' => 'required|email',
                'phone' => 'nullable|string|max:20',
                'secret_code' => 'required|string|max:100',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        }

        $school = School::findOrFail($request->input('school_id'));
        
        // Validate secret code
        if (!$school->secret_code || $request->input('secret_code') !== $school->secret_code) {
            return response()->json([
                'error' => 'Kode akses yang Anda masukkan salah. Mohon tanya kembali ke guru Anda.'
            ], 422);
        }

        $data = $request->only([
            'nama_murid',
            'email_murid',
            'kelas',
            'title',
            'isi_laporan',
            'phone',
            'secret_code'
        ]);
        $data['jenis_laporan'] = $request->input('jenis_laporan');
        $data['nama_sekolah'] = $school->name;
        $data['school_id'] = $request->input('school_id');

        try {
            $report = $reportService->create($data);

            // Set initial 2-minute resend cooldown (server-side, prevents bypass)
            if (!empty($report->email_murid)) {
                $expiresAt = now()->addMinutes(2)->timestamp;
                Cache::put('ml_cooldown_' . $report->tracking_code, $expiresAt, now()->addMinutes(2));
            }

            return response()->json([
                'tracking_code' => $report->tracking_code,
                'email_sent'    => !empty($report->email_murid),
                'email'         => !empty($report->email_murid)
                    ? substr($report->email_murid, 0, 3) . '***@***'
                    : null,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Report creation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal membuat laporan. Silakan coba lagi.'
            ], 500);
        }
    }

    public function createForm()
    {
        $schools = School::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('student.create', compact('schools'));
    }

    // Show student track form
    public function showTrackForm()
    {
        return view('student.track-form');
    }

    // SHOW RESULT (after submit)
    public function result($tracking_code)
    {
        $report = Report::where('tracking_code', $tracking_code)->firstOrFail();
        return view('student.result', compact('report'));
    }

    // TRACKING LAPORAN
    public function track($code)
    {
        $report = Report::where('tracking_code', $code)->firstOrFail();
        return view('student.track', compact('report'));
    }

    // GURU UPDATE STATUS (harus login)
    public function updateStatus(Request $request, $id, ReportService $reportService)
    {
        $request->validate([
            'status' => 'required|in:'.Report::STATUS_DIPROSES.','.Report::STATUS_SELESAI
        ]);

        $report = Report::findOrFail($id);

        $this->authorize('updateStatus', $report);

        /** @var User $authUser */
        $guruId = Auth::id();

        $reportService->changeStatus($report, $request->input('status'), $guruId);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Bug 1 fix: Validate signed magic link and show the kode-unik page.
     * Route: GET /verify-laporan?tracking_code=X&signature=...
     */
    public function verify(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('home')
                ->with('error', 'Tautan verifikasi tidak valid atau sudah kedaluwarsa. Silakan minta kiriman ulang.');
        }

        $report = Report::where('tracking_code', $request->input('tracking_code'))->firstOrFail();

        // Stamp email_verified_at jika belum (baru pertama kali klik link)
        if (is_null($report->email_verified_at)) {
            $report->email_verified_at = now();
            $report->save();
        }

        // Notifikasi guru (hanya sekali) setelah murid verifikasi magic link
        $notifiedKey = 'teacher_notified_' . $report->id;
        if (!Cache::has($notifiedKey)) {
            event(new ReportCreated($report));
            Cache::put($notifiedKey, true, now()->addDays(30));
        }

        return view('student.kode-unik', compact('report'));
    }

    /**
     * Fitur Baru 1 & Bug 2 fix: Resend magic link with 2-minute server-side cooldown.
     * Always processes regardless of existing report data (Bug 2).
     */
    public function resendMagicLink(
        Request $request,
        string $tracking_code,
        ReportService $reportService
    ) {
        $report   = Report::where('tracking_code', $tracking_code)->firstOrFail();
        $cacheKey = 'ml_cooldown_' . $tracking_code;

        // Bug 2 fix: if a notification already exists we do NOT block — we always allow resend.
        // Only enforce the 2-minute cooldown window.
        if (Cache::has($cacheKey)) {
            $expiresAt  = Cache::get($cacheKey);
            $retryAfter = max(0, $expiresAt - now()->timestamp);
            return response()->json([
                'error'       => 'Mohon tunggu sebelum mengirim ulang kode.',
                'retry_after' => $retryAfter,
            ], 429);
        }

        // Set 2-minute server-side cooldown
        $expiresAt = now()->addMinutes(2)->timestamp;
        Cache::put($cacheKey, $expiresAt, now()->addMinutes(2));

        // Resend magic link unconditionally
        $reportService->sendSuccessNotification($report);

        return response()->json([
            'success'     => true,
            'message'     => 'Kode verifikasi telah dikirim ulang ke email Anda. Mohon periksa kotak masuk atau folder spam/sampah Anda. Anda dapat mengirim ulang kembali setelah 2 menit.',
            'retry_after' => 120,
        ]);
    }
}