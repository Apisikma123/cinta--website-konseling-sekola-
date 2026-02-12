<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        $user = auth()->user();
        if ($user && $user->isTeacher()) {
            if (! ($user->school && $report->nama_sekolah === $user->school)) {
                // Check if fuzzy match works before aborting
                // Allow if teacher's school name is contained in report's school name or vice versa
                // Or if token match succeeds (reuse logic from HomeController/TeacherController)
                $isMatch = false;
                $teacherSchool = strtolower($user->school);
                $reportSchool = strtolower($report->nama_sekolah);
                
                if (str_contains($reportSchool, $teacherSchool) || str_contains($teacherSchool, $reportSchool)) {
                    $isMatch = true;
                } else {
                    $tokens = preg_split('/[^a-z0-9]+/i', $teacherSchool);
                    foreach ($tokens as $tok) {
                        if (strlen($tok) < 3) continue;
                        if (str_contains($reportSchool, $tok)) {
                            $isMatch = true;
                            break;
                        }
                    }
                }

                if (!$isMatch) {
                    abort(403);
                }
            }
        }

        // Guest (student) may access via tracking code

        $chats = Chat::select('id', 'sender_type', 'sender_id', 'message', 'is_read', 'created_at', 'report_id')
                    ->with('sender:id,name')
                    ->where('report_id', $report->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        // Use the dedicated chat layout
        $chatLayout = 'layouts.chat';
        $isStudent = false;

        return view('chat.index', compact('report', 'chats', 'chatLayout', 'isStudent'));
    }

    public function studentIndex($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        $chats = Chat::select('id', 'sender_type', 'sender_id', 'message', 'is_read', 'created_at', 'report_id')
                    ->with('sender:id,name')
                    ->where('report_id', $report->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        // Use the dedicated chat layout
        $chatLayout = 'layouts.chat';
        $isStudent = true;

        return view('chat.index', compact('report', 'chats', 'chatLayout', 'isStudent'));
    }

    // Lightweight polling endpoint
    public function messages($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        $chats = Chat::select('id', 'sender_type', 'sender_id', 'message', 'is_read', 'created_at', 'report_id')
                    ->with('sender:id,name')
                    ->where('report_id', $report->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

        return response()->json($chats);
    }

    public function store(Request $request, $trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        $request->validate(['message' => 'required|string|max:2000']);

        // If this is the student-facing route, always treat as student (even when a teacher is logged in)
        if ($request->route()->named('chat.murid.store')) {
            $senderType = 'student';
            $senderId = null;
        } else {
            $user = auth()->user();
            if ($user && $user->isTeacher()) {
                // teacher send - update last_activity immediately (direct DB update for performance)
                \App\Models\User::where('id', $user->id)->update(['last_activity' => now()]);
                $senderType = 'teacher';
                $senderId = $user->id;
            } else {
                // fallback: anonymous student
                $senderType = 'student';
                $senderId = null;
            }
        }

        Chat::create([
            'report_code' => $report->tracking_code,
            'report_id' => $report->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $request->message,
        ]);

        return response()->json(['ok' => true]);
    }

    public function whatsapp($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        // Prefer the assigned guru
        $wa = $report->guru && $report->guru->whatsapp ? $report->guru->whatsapp : null;

        // Fallback: find any teacher in the same school who has a whatsapp number
        if (! $wa && $report->nama_sekolah) {
            // 1. Try exact match
            $fallback = \App\Models\User::where('role', 'teacher')
                ->where('school', $report->nama_sekolah)
                ->whereNotNull('whatsapp')
                ->where('whatsapp', '!=', '')
                ->first();
                
            // 2. If exact match fails, try fuzzy search (Teacher="SMAN 1 Jkt", Report="SMAN 1")
            if (!$fallback) {
                // Get all teachers with whatsapp
                $teachers = \App\Models\User::where('role', 'teacher')
                    ->whereNotNull('school')
                    ->whereNotNull('whatsapp')
                    ->where('whatsapp', '!=', '')
                    ->get();
                    
                $reportSchool = strtolower(trim($report->nama_sekolah));
                
                foreach ($teachers as $teacher) {
                    $teacherSchool = strtolower(trim($teacher->school));
                    
                    // Direct containment check (most likely to succeed)
                    if (str_contains($reportSchool, $teacherSchool) || str_contains($teacherSchool, $reportSchool)) {
                        $fallback = $teacher;
                        break;
                    }
                    
                    // Token intersection check
                    $reportTokens = preg_split('/[^a-z0-9]+/i', $reportSchool);
                    $teacherTokens = preg_split('/[^a-z0-9]+/i', $teacherSchool);
                    
                    $matches = 0;
                    foreach ($reportTokens as $rt) {
                         if (strlen($rt) < 3) continue;
                         foreach ($teacherTokens as $tt) {
                             if (strlen($tt) < 3) continue;
                             if ($rt === $tt) {
                                 $matches++;
                             }
                         }
                    }
                    
                    // If we have at least one significant matching token, take it
                    if ($matches > 0) {
                         $fallback = $teacher;
                         break; 
                    }
                }
            }
            
            // 3. Last Resort: If still no teacher found, pick ANY teacher (Fail-safe)
            // This ensures the link never dead-ends or redirects back to the result page.
            if (!isset($fallback)) {
                $fallback = \App\Models\User::where('role', 'teacher')
                    ->whereNotNull('whatsapp')
                    ->where('whatsapp', '!=', '')
                    ->inRandomOrder() // Spread the load if multiple generic teachers exist
                    ->first();
            }
            
            if ($fallback) {
                $wa = $fallback->whatsapp;
            }
        }

        if (! $wa) {
            return redirect()->back()->with('error', 'Nomor WhatsApp guru belum tersedia untuk sekolah ini.');
        }

        // Format WA number: remove non-digits, replace leading 0 with 62
        $wa = preg_replace('/\D+/', '', $wa);
        if (str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }

        $message = urlencode("Halo, saya ingin menanyakan tentang laporan {$report->tracking_code}");
        return redirect()->away("https://wa.me/{$wa}?text={$message}");
    }

    // Mark a chat message as read
    public function markAsRead($trackingCode, $id)
    {
        $chat = Chat::findOrFail($id);
        $chat->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    /**
     * Track real-time activity for both teacher and student
     */
    public function trackActivity(Request $request)
    {
        $user = auth()->user();
        $reportId = $request->report_id;
        $isStudent = $request->is_student;

        // Track student if explicitly in student context
        if ($isStudent && $reportId) {
            \Illuminate\Support\Facades\Cache::put('presence_student_' . $reportId, now(), 60);
        } 
        
        // Track teacher if logged in (even if in student view, they are "online" as a teacher)
        if ($user && $user->isTeacher()) {
            $user->update(['last_activity' => now()]);
            \Illuminate\Support\Facades\Cache::put('presence_teacher_' . $user->id, now(), 60);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Get presence status for both sides of a report
     */
    public function getPresence($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->select('id', 'guru_id')->firstOrFail();
        
        $isTeacherOnline = false;
        if ($report->guru_id) {
            $lastActive = \Illuminate\Support\Facades\Cache::get('presence_teacher_' . $report->guru_id);
            $isTeacherOnline = $lastActive && $lastActive->gt(now()->subSeconds(45));
        }

        $lastStudentActive = \Illuminate\Support\Facades\Cache::get('presence_student_' . $report->id);
        $isStudentOnline = $lastStudentActive && $lastStudentActive->gt(now()->subSeconds(45));

        return response()->json([
            'teacher' => $isTeacherOnline,
            'student' => $isStudentOnline
        ]);
    }

    /**
     * Get presence status for multiple reports (for dashboard)
     */
    public function getBulkPresence(Request $request)
    {
        $reportIds = $request->report_ids ?? [];
        if (empty($reportIds)) return response()->json([]);

        $presence = [];
        $now = now();
        foreach ($reportIds as $id) {
            $lastActive = \Illuminate\Support\Facades\Cache::get('presence_student_' . $id);
            $presence[$id] = $lastActive && $lastActive->gt($now->subSeconds(45));
        }

        return response()->json($presence);
    }

    /**
     * Check if a user is online
     */
    public function checkUserOnline($userId)
    {
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return response()->json(['isOnline' => false, 'reason' => 'user_not_found']);
        }
        
        // Check if user was active within last 30 seconds (must be in chat)
        $isOnline = $user->last_activity && $user->last_activity->diffInSeconds(now()) < 30;
        if (!$isOnline) {
            return response()->json(['isOnline' => false]);
        }
        
        return response()->json([
            'isOnline' => $isOnline,
            'lastActivity' => $user->last_activity,
            'secondsAgo' => $user->last_activity ? $user->last_activity->diffInSeconds(now()) : null
        ]);
    }
}