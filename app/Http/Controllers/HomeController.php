<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;
use App\Models\Report;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application home page.
     */
    public function index()
    {
        $totalReports = Report::count();
        $completed = Report::where('status', 'selesai')->count();
        $stats = [
            'total' => $totalReports ?: 0,
            'completed_percent' => $totalReports ? round($completed / $totalReports * 100) : 0,
            'schools' => School::count(),
        ];

        // Get testimonials grouped by school
        $allTestimonials = Testimonial::where('is_approved', 1)
            ->where('is_visible', true)
            ->with(['report.school'])
            ->latest()
            ->get();

        // Group by school name
        $testimonials = $allTestimonials->groupBy(function ($t) {
            return $t->report?->school?->name ?? $t->report?->nama_sekolah ?? 'Sekolah Lainnya';
        })->map(function ($group) {
            return $group->take(3); // Max 3 per school on homepage
        });

        $total = Report::count() ?: 1;
        $jenisBreakdown = Report::select(DB::raw('COALESCE(jenis_laporan, "lainnya") as jenis'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('jenis_laporan')
            ->get()
            ->map(function ($r) use ($total) {
                return [
                    'jenis' => $r->jenis,
                    'percent' => round($r->cnt / $total * 100),
                ];
            });

        $counselors = User::where('role', 'teacher')->where('is_active', 1)->get();
        
        // Teachers grouped by school for counselor section
        $teachers = User::where('role', 'teacher')
            ->where('is_active', 1)
            ->get()
            ->groupBy('school');

        return view('home', compact('stats', 'testimonials', 'jenisBreakdown', 'counselors', 'teachers'));
    }

    /**
     * Return HTML partial for counselors (used by AJAX on home page).
     */
    public function counselorsPartial()
    {
        $counselors = User::where('role', 'teacher')->where('is_active', 1)->limit(8)->get();

        return view('partials.counselors', compact('counselors'))->render();
    }
}