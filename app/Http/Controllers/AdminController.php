<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\TeacherApproval;
use App\Models\School;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function approveTeachers()
    {
        // Only show teachers yang sudah verifikasi OTP dan belum di-approve
        $pendingTeachers = User::where('role', 'teacher')
                              ->where('otp_verified', true)
                              ->where('approval_status', 'pending')
                              ->with('teacherApproval')
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);

        return view('admin.approve-teachers', compact('pendingTeachers'));
    }

    public function teachers()
    {
        $teachers = User::where('role', 'teacher')
            ->where('approval_status', 'approved')
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('admin.teachers', compact('teachers'));
    }

    public function toggleTeacherStatus(User $teacher)
    {
        $teacher->update(['is_active' => ! $teacher->is_active]);
        return back()->with('success', 'Status guru berhasil diperbarui.');
    }

    public function destroyTeacher(User $teacher)
    {
        $teacher->delete();
        return back()->with('success', 'Guru berhasil dihapus.');
    }

    public function dashboard()
    {
        // Total teachers with OTP verified
        $totalTeachers = User::where('role', 'teacher')
            ->where('otp_verified', true)
            ->count();

        // Pending approval count
        $pendingTeachers = User::where('role', 'teacher')
            ->where('otp_verified', true)
            ->where('approval_status', 'pending')
            ->count();

        // Approved teachers count
        $approvedTeachers = User::where('role', 'teacher')
            ->where('approval_status', 'approved')
            ->count();

        // Schools stats
        $schoolStats = School::selectRaw('COUNT(*) as total, SUM(IF(is_active=true, 1, 0)) as active, SUM(IF(is_active=false, 1, 0)) as inactive', [])
            ->first();

        $stats = [
            'active_schools' => $schoolStats->active ?? 0,
            'inactive_schools' => $schoolStats->inactive ?? 0,
            'total_schools' => $schoolStats->total ?? 0,
            'total_teachers' => $totalTeachers,
            'pending_teachers' => $pendingTeachers,
            'approved_teachers' => $approvedTeachers,
        ];

        $schoolsChart = [
            'aktif' => $stats['active_schools'],
            'nonaktif' => $stats['inactive_schools'],
        ];

        // Latest 5 teachers (verified OTP, any status)
        $teachers = User::where('role', 'teacher')
            ->where('otp_verified', true)
            ->select(['id', 'name', 'email', 'school', 'whatsapp', 'profile_photo', 'approval_status', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'schoolsChart', 'teachers'));
    }

    public function approveTeacher($id)
    {
        $user = User::findOrFail($id);
        $user->update(['approval_status' => 'approved']);

        Notification::route('mail', $user->email)
            ->notify(new \App\Notifications\TeacherApprovedNotification($user));

        return back()->with('success', 'Guru berhasil disetujui.');
    }

    public function settings()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return view('admin.settings', compact('user'));
    }

    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB input validation
        ]);

        // Handle Profile Photo Upload
        if ($request->hasFile('profile_photo')) {
            $photo = $request->file('profile_photo');
            
            // Generate unique filename
            $filename = 'profile/' . time() . '_' . Str::random(10) . '.jpg';

            // Ensure directory exists
            if (!Storage::disk('public')->exists('profile')) {
                Storage::disk('public')->makeDirectory('profile');
            }

            // Store file directly - file sudah dikompres di frontend (300x300, JPEG quality 0.8)
            // Ukuran file seharusnya sudah ±50-150KB
            Storage::disk('public')->putFileAs('profile', $photo, basename($filename));

            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $data['profile_photo'] = $filename;
        }

        $user->update($data);

        if ($request->filled('password')) {
            $this->validate($request, [
                'current_password' => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            if (!Hash::check($request->input('current_password'), $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
            }

            $user->password = Hash::make($request->input('password'));
            $user->save();
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function schools()
    {
        $schools = School::orderBy('name', 'asc')->paginate(10);
        return view('admin.schools', compact('schools'));
    }

    public function storeSchool(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        School::create($data);

        return back()->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function updateSchool(Request $request, School $school)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $school->update($data);

        return back()->with('success', 'Sekolah berhasil diperbarui.');
    }

    public function destroySchool(School $school)
    {
        $school->delete();
        return back()->with('success', 'Sekolah berhasil dihapus.');
    }

    // Testimonials Management
    public function testimonials()
    {
        $testimonials = Testimonial::with(['report', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.testimonials', compact('testimonials'));
    }

    public function hideTestimonial(Testimonial $testimonial)
    {
        $testimonial->update(['is_visible' => false]);
        return back()->with('success', 'Testimoni berhasil disembunyikan.');
    }

    public function showTestimonial(Testimonial $testimonial)
    {
        $testimonial->update(['is_visible' => true]);
        return back()->with('success', 'Testimoni berhasil ditampilkan kembali.');
    }
}
