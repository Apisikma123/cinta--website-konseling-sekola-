<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_id' => 'required|exists:reports,id',
            'content' => 'required|string|min:10|max:500',
            'rating' => 'required|integer|min:1|max:5',
            'is_anonymous' => 'nullable|boolean'
        ]);

        // Get student name from report
        $report = Report::findOrFail($validated['report_id']);
        
        Testimonial::create([
            'report_id' => $validated['report_id'],
            'user_id' => null, // Student tidak perlu login
            'student_name' => $validated['is_anonymous'] ? 'Murid Anonim' : $report->nama_murid,
            'content' => $validated['content'],
            'rating' => $validated['rating'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'is_approved' => false // Harus di-approve guru
        ]);

        return response()->json(['ok' => true]);
    }

    // Guru approve testimonial
    public function approve($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_approved' => true]);
        // Clear cache so homepage shows updated testimonials
        Cache::forget('home_testimonials');

        // Also clear per-school cache if this testimonial has a related report with sekolah
        try {
            $school = $testimonial->report->nama_sekolah ?? null;
            if ($school) {
                $perSchoolKey = 'home_testimonials_school_' . md5($school);
                Cache::forget($perSchoolKey);
            }
        } catch (\Exception $e) {
            // ignore
        }

        return back()->with('success', 'Testimoni disetujui.');
    }

    // Guru reject testimonial
    public function reject($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();
        
        // No need to clear cache for reject since it wasn't approved anyway
        
        return back()->with('success', 'Testimoni ditolak.');
    }

    // Ambil 3 testimoni random yang disetujui (untuk homepage)
    public function randomApproved()
    {
        return Testimonial::where('is_approved', true)
                          ->inRandomOrder()
                          ->limit(3)
                          ->get();
    }
}