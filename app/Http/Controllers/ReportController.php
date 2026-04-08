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


class ReportController extends Controller
{
    // HOMEPAGE = FORM BUAT LAPORAN
    public function index()
    {
        return view('welcome');
    }

    // BUAT LAPORAN (guest)
    public function create(Request $request, \App\Services\ReportService $reportService)
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
                'email_murid' => 'nullable|email',
                'phone' => 'nullable|string|max:20',
                'secret_code' => 'required|string|max:100',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
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
            return response()->json(['tracking_code' => $report->tracking_code], 200);
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
    public function updateStatus(Request $request, $id, \App\Services\ReportService $reportService)
    {
        $request->validate([
            'status' => 'required|in:'.Report::STATUS_DIPROSES.','.Report::STATUS_SELESAI
        ]);

        $report = Report::findOrFail($id);

        $this->authorize('updateStatus', $report);

        /** @var \App\Models\User $authUser */
        $guruId = Auth::id();

        $reportService->changeStatus($report, $request->input('status'), $guruId);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}