<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Cached report lookup — avoids hitting DB on every poll request.
     * Cache lasts 5 minutes, auto-refreshed on access.
     */
    private function findReport(string $trackingCode): Report
    {
        return Cache::remember(
            "report_tc_{$trackingCode}",
            300, // 5 minutes
            fn () => Report::where('tracking_code', $trackingCode)->firstOrFail()
        );
    }

    /**
     * Lightweight report lookup — only grabs id + guru_id + claimed_by.
     * Used for presence/typing endpoints that don't need full model.
     */
    private function findReportLite(string $trackingCode): Report
    {
        return Cache::remember(
            "report_lite_{$trackingCode}",
            300,
            fn () => Report::where('tracking_code', $trackingCode)
                           ->select(['id', 'guru_id', 'claimed_by', 'tracking_code'])
                           ->firstOrFail()
        );
    }
    public function index($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        if ($user && $user->isTeacher()) {
            if ($report->claimed_by && $report->claimed_by !== $user->id) {
                return response()->view('chat.claimed', compact('report'), 403);
            }

            if (! ($user->school && $report->nama_sekolah === $user->school)) {
                $isMatch = false;
                $teacherSchool = strtolower($user->school);
                $reportSchool  = strtolower($report->nama_sekolah);

                if (str_contains($reportSchool, $teacherSchool) || str_contains($teacherSchool, $reportSchool)) {
                    $isMatch = true;
                } else {
                    $tokens = preg_split('/[^a-z0-9]+/i', $teacherSchool);
                    foreach ($tokens as $tok) {
                        if (strlen($tok) < 3) continue;
                        if (str_contains($reportSchool, $tok)) { $isMatch = true; break; }
                    }
                }

                if (!$isMatch) abort(403);
            }
        }

        // ✅ FIX: Mark ONLY student messages as read, saat GURU (receiver) buka halaman chat.
        // Logic: UPDATE chats SET is_read=true WHERE report_id=? AND sender_type='student' AND is_read=false
        // Pesan dari 'student' artinya receiver-nya adalah 'teacher' — valid untuk di-mark saat guru buka.
        Chat::where('report_id', $report->id)
            ->where('sender_type', 'student')   // pesan dari murid = penerima adalah guru
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $chats = Chat::select(['id','sender_type','sender_id','message','message_encrypted','message_iv','is_encrypted','is_read','created_at','report_id','edited_at','deleted_for_everyone'])
                     ->with('sender:id,name')
                     ->where('report_id', $report->id)
                     ->orderBy('created_at', 'asc')
                     ->get();

        $chatLayout = 'layouts.chat';
        $isStudent  = false;

        return view('chat.index', compact('report', 'chats', 'chatLayout', 'isStudent'));
    }

    public function studentIndex($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        // ✅ FIX: Mark ONLY teacher messages as read, saat MURID (receiver) buka halaman chat.
        // Logic: UPDATE chats SET is_read=true WHERE report_id=? AND sender_type='teacher' AND is_read=false
        // Pesan dari 'teacher' artinya receiver-nya adalah 'student' — valid untuk di-mark saat murid buka.
        Chat::where('report_id', $report->id)
            ->where('sender_type', 'teacher')   // pesan dari guru = penerima adalah murid
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $chats = Chat::select(['id','sender_type','sender_id','message','message_encrypted','message_iv','is_encrypted','is_read','created_at','report_id','edited_at','deleted_for_everyone'])
                     ->with('sender:id,name')
                     ->where('report_id', $report->id)
                     ->orderBy('created_at', 'asc')
                     ->get();

        $chatLayout = 'layouts.chat';
        $isStudent  = true;

        return view('chat.index', compact('report', 'chats', 'chatLayout', 'isStudent'));
    }

    public function messages($trackingCode)
    {
        $report = $this->findReport($trackingCode);

        $query = Chat::select(['id','sender_type','sender_id','message','message_encrypted','message_iv','is_encrypted','is_read','created_at','report_id','edited_at','deleted_for_everyone'])
                     ->with('sender:id,name')
                     ->where('report_id', $report->id);

        if (request()->has('after_id')) {
            $query->where('id', '>', request('after_id'));
        }

        $chats = $query->orderBy('created_at', 'asc')->limit(100)->get();

        $chatData = $chats->map(function (Chat $chat) {
            $arr            = $chat->toArray();
            // ✅ FIX: paksa pakai accessor — kalau deleted tampilkan teks deleted
            $arr['message']    = $chat->deleted_for_everyone ? 'Pesan ini telah dihapus' : $chat->message;
            $arr['is_deleted'] = (bool) ($chat->deleted_for_everyone ?? false);
            $arr['is_edited']  = ! is_null($chat->edited_at);
            $arr['sender']     = $chat->sender ? ['id' => $chat->sender->id, 'name' => $chat->sender->name] : null;
            return $arr;
        });

        return response()->json($chatData);
    }

    public function store(Request $request, $trackingCode)
    {
        $report = $this->findReport($trackingCode);

        $request->validate(['message' => 'required|string|max:2000']);

        $isRecipientOnline = false;

        if ($request->route()->named('chat.murid.store')) {
            $senderType = 'student';
            $senderId   = null;
            $teacherId  = $report->claimed_by ?? $report->guru_id;
            if ($teacherId) {
                $last = Cache::get('presence_teacher_' . $teacherId);
                if ($last && (time() - $last) < 45) {
                    $isRecipientOnline = true;
                }
            }
        } else {
            /** @var \App\Models\User|null $user */
            $user = auth()->user();
            if ($user && $user->isTeacher()) {
                \App\Models\User::where('id', $user->id)->update(['last_activity' => now()]);
                $senderType = 'teacher';
                $senderId   = $user->id;
                
                $last = Cache::get('presence_student_' . $report->id);
                if ($last && (time() - $last) < 45) {
                    $isRecipientOnline = true;
                }
            } else {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        // ✅ Simpan dulu
        $chat = Chat::create([
            'report_code' => $report->tracking_code,
            'report_id'   => $report->id,
            'sender_type' => $senderType,
            'sender_id'   => $senderId,
            'message'     => $request->input('message'),
            'is_read'     => $isRecipientOnline,
        ]);

        // ✅ Re-fetch agar relasi & cast benar
        $chat = Chat::with('sender:id,name')->find($chat->id);

        return response()->json([
            'ok'   => true,
            'chat' => [
                'id'                  => $chat->id,
                'report_id'           => $chat->report_id,
                'sender_type'         => $chat->sender_type,
                'sender_id'           => $chat->sender_id,
                // ✅ FIX UTAMA: pakai $request->input('message') (plain text dari user)
                // Menghindari accessor yang kadang return [ENCRYPTED] saat baru disimpan
                'message'             => $request->input('message'),
                'is_read'             => $isRecipientOnline,
                'is_deleted'          => false,
                'is_edited'           => false,
                'edited_at'           => null,
                'deleted_for_everyone'=> false,
                'created_at'          => $chat->created_at,
                'sender'              => $chat->sender
                    ? ['id' => $chat->sender->id, 'name' => $chat->sender->name]
                    : null,
            ],
        ]);
    }

    // ✅ Typing — pakai is_student boolean (sesuai blade)
    public function typing(Request $request, $trackingCode)
    {
        $report = $this->findReportLite($trackingCode);
        $role   = $request->boolean('is_student') ? 'student' : 'teacher';
        Cache::put("typing_{$role}_{$report->id}", true, 6);
        return response()->json(['ok' => true]);
    }

    public function getTyping($trackingCode)
    {
        $report = $this->findReportLite($trackingCode);
        return response()->json([
            'student' => (bool) Cache::get("typing_student_{$report->id}"),
            'teacher' => (bool) Cache::get("typing_teacher_{$report->id}"),
        ]);
    }

    /**
     * Combined endpoint: typing + presence in ONE request.
     * Reduces API calls by 50% (was 2 separate polls, now 1).
     */
    public function chatStatus(Request $request, $trackingCode)
    {
        $report    = $this->findReportLite($trackingCode);
        $isStudent = $request->boolean('is_student');

        // ── Track presence via cache only (NO DB write) ──
        if ($isStudent) {
            Cache::put('presence_student_' . $report->id, time(), 60);
        } else {
            /** @var \App\Models\User|null $user */
            $user = auth()->user();
            if ($user && $user->isTeacher()) {
                Cache::put('presence_teacher_' . $user->id, time(), 60);
            }
        }

        // ── Typing status ──
        $typing = [
            'student' => (bool) Cache::get("typing_student_{$report->id}"),
            'teacher' => (bool) Cache::get("typing_teacher_{$report->id}"),
        ];

        // ── Presence status ──
        $teacherId       = $report->claimed_by ?? $report->guru_id;
        $isTeacherOnline = false;
        if ($teacherId) {
            $last            = Cache::get('presence_teacher_' . $teacherId);
            $isTeacherOnline = $last && ((time() - $last) < 45);
        }
        $lastStudent     = Cache::get('presence_student_' . $report->id);
        $isStudentOnline = $lastStudent && ((time() - $lastStudent) < 45);

        // Fetch read message IDs sent by this user
        $senderType = $isStudent ? 'student' : 'teacher';
        $readIds = Chat::where('report_id', $report->id)
                        ->where('sender_type', $senderType)
                        ->where('is_read', true)
                        ->pluck('id');

        return response()->json([
            'typing'   => $typing,
            'teacher'  => $isTeacherOnline,
            'student'  => $isStudentOnline,
            'read_ids' => $readIds,
        ]);
    }

    public function whatsapp($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();

        $wa = $report->guru && $report->guru->whatsapp ? $report->guru->whatsapp : null;

        if (! $wa && $report->nama_sekolah) {
            $fallback = User::where('role', 'teacher')
                ->where('school', $report->nama_sekolah)
                ->where('is_active', true)
                ->whereNotNull('whatsapp')
                ->where('whatsapp', '!=', '')
                ->inRandomOrder()
                ->first();

            if (!$fallback) {
                $teachers     = User::where('role','teacher')->where('is_active',true)->whereNotNull('school')->whereNotNull('whatsapp')->where('whatsapp','!=','')->get();
                $reportSchool = strtolower(trim($report->nama_sekolah));

                foreach ($teachers as $teacher) {
                    $teacherSchool = strtolower(trim($teacher->school));
                    if (str_contains($reportSchool, $teacherSchool) || str_contains($teacherSchool, $reportSchool)) {
                        $fallback = $teacher; break;
                    }
                    $rToks = preg_split('/[^a-z0-9]+/i', $reportSchool);
                    $tToks = preg_split('/[^a-z0-9]+/i', $teacherSchool);
                    $hits  = 0;
                    foreach ($rToks as $r) {
                        if (strlen($r) < 3) continue;
                        foreach ($tToks as $t) { if (strlen($t) >= 3 && $r === $t) $hits++; }
                    }
                    if ($hits > 0) { $fallback = $teacher; break; }
                }
            }

            if ($fallback) $wa = $fallback->whatsapp;
        }

        if (! $wa) {
            return redirect()->back()->with('error', 'Nomor WhatsApp guru BK dari sekolah Anda belum tersedia. Silakan gunakan chat langsung.');
        }

        $wa = preg_replace('/\D+/', '', $wa);
        if (str_starts_with($wa, '0')) $wa = '62' . substr($wa, 1);

        $message = urlencode("Halo, saya ingin menanyakan tentang laporan {$report->tracking_code}");
        return redirect()->away("https://wa.me/{$wa}?text={$message}");
    }

    public function markAsRead(Request $request, $trackingCode, $id)
    {
        $report = $this->findReportLite($trackingCode);

        // ✅ FIX: Tentukan siapa receiver berdasarkan route (guru atau murid)
        // Pesan hanya bisa di-mark-read oleh si Penerima (receiver), bukan pengirim.
        $isStudentRoute = $request->route()->named('chat.murid.mark-read');

        if ($isStudentRoute) {
            // Murid yang buka — hanya boleh mark pesan dari 'teacher' (yang ditujukan ke murid)
            $receiverSenderType = 'teacher';
        } else {
            // Guru yang buka — hanya boleh mark pesan dari 'student' (yang ditujukan ke guru)
            /** @var \App\Models\User|null $user */
            $user = auth()->user();
            if (!$user || !$user->isTeacher()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $receiverSenderType = 'student';
        }

        // ✅ Validasi: pastikan pesan ini memang milik report ini DAN dari sender yang tepat
        $updated = Chat::where('id', $id)
            ->where('report_id', $report->id)       // pastikan pesan milik report yang sama
            ->where('sender_type', $receiverSenderType) // pastikan user adalah receiver
            ->where('is_read', false)                // skip kalau sudah terbaca
            ->update(['is_read' => true]);

        return response()->json(['ok' => true, 'updated' => $updated > 0]);
    }

    public function unreadCount(Request $request, $trackingCode)
    {
        try {
            $report = Report::where('tracking_code', $trackingCode)->firstOrFail();
            
            // For student result/track pages, we want to know how many unread 'teacher' messages there are
            $count = Chat::where('report_id', $report->id)
                ->where('sender_type', 'teacher')
                ->where('is_read', false)
                ->count();
                
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0]);
        }
    }

    public function readStatus($trackingCode)
    {
        $report   = $this->findReportLite($trackingCode);
        $statuses = Chat::where('report_id', $report->id)->select(['id','is_read'])->get();
        return response()->json($statuses);
    }

    public function deleteMessage($trackingCode, $id)
    {
        $report = $this->findReport($trackingCode);
        $chat   = Chat::where('report_id', $report->id)->findOrFail($id);

        if (request()->route()->named('chat.murid.delete')) {
            if ($chat->sender_type !== 'student') abort(403, 'Aksi tidak diizinkan');
        } else {
            /** @var \App\Models\User|null $user */
            $user = auth()->user();
            if (!$user || $chat->sender_id != $user->id) abort(403, 'Aksi tidak diizinkan');
        }

        $chat->update(['deleted_at' => now(), 'deleted_for_everyone' => true]);

        return response()->json(['ok' => true, 'id' => $id]);
    }

    public function editMessage(Request $request, $trackingCode, $id)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $report = $this->findReport($trackingCode);
        $chat   = Chat::where('report_id', $report->id)->findOrFail($id);

        if ($request->route()->named('chat.murid.edit')) {
            if ($chat->sender_type !== 'student') abort(403, 'Aksi tidak diizinkan');
        } else {
            /** @var \App\Models\User|null $user */
            $user = auth()->user();
            if (!$user || $chat->sender_id != $user->id) abort(403, 'Aksi tidak diizinkan');
        }

        if ($chat->deleted_for_everyone) abort(403, 'Pesan yang sudah dihapus tidak dapat diedit');

        // Update using update() method to avoid readonly property issues
        $chat->update([
            'message' => $request->input('message'),
            'edited_at' => now(),
        ]);

        return response()->json([
            'ok'   => true,
            'chat' => [
                'id'        => $chat->id,
                // ✅ FIX: pakai $request->input('message') bukan accessor
                'message'   => $request->input('message'),
                'is_edited' => true,
                'edited_at' => $chat->edited_at,
            ],
        ]);
    }

    public function trackActivity(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user      = auth()->user();
        $reportId  = $request->input('report_id');
        $isStudent = $request->boolean('is_student');

        if ($isStudent && $reportId) {
            Cache::put('presence_student_' . $reportId, time(), 60);
        }

        if ($user && $user->isTeacher()) {
            $user->update(['last_activity' => now()]);
            Cache::put('presence_teacher_' . $user->id, time(), 60);
        }

        return response()->json(['ok' => true]);
    }

    public function getPresence(Request $request, $trackingCode)
    {
        $report = $this->findReportLite($trackingCode);

        // ── Track presence via cache only (NO DB write) ──
        if ($request->has('is_student')) {
            $isStudent = $request->boolean('is_student');
            if ($isStudent) {
                Cache::put('presence_student_' . $report->id, time(), 60);
            } else {
                /** @var \App\Models\User|null $user */
                $user = auth()->user();
                if ($user && $user->isTeacher()) {
                    // ✅ FIX: Cache only — no more $user->update() on every poll
                    Cache::put('presence_teacher_' . $user->id, time(), 60);
                }
            }
        }

        $isTeacherOnline = false;
        $teacherId       = $report->claimed_by ?? $report->guru_id;
        if ($teacherId) {
            $last            = Cache::get('presence_teacher_' . $teacherId);
            $isTeacherOnline = $last && ((time() - $last) < 45);
        }

        $lastStudent     = Cache::get('presence_student_' . $report->id);
        $isStudentOnline = $lastStudent && ((time() - $lastStudent) < 45);

        return response()->json(['teacher' => $isTeacherOnline, 'student' => $isStudentOnline]);
    }

    public function getBulkPresence(Request $request)
    {
        $reportIds = $request->input('report_ids') ?? [];
        if (empty($reportIds)) return response()->json([]);

        $presence = [];
        foreach ($reportIds as $id) {
            $last         = Cache::get('presence_student_' . $id);
            $presence[$id] = $last && ((time() - $last) < 45);
        }

        return response()->json($presence);
    }

    public function checkUserOnline($userId)
    {
        $user = User::find($userId);
        if (!$user) return response()->json(['isOnline' => false, 'reason' => 'user_not_found']);

        $isOnline = $user->last_activity && $user->last_activity->diffInSeconds(now()) < 30;
        if (!$isOnline) return response()->json(['isOnline' => false]);

        return response()->json([
            'isOnline'     => true,
            'lastActivity' => $user->last_activity,
            'secondsAgo'   => $user->last_activity->diffInSeconds(now()),
        ]);
    }

    public function claimReport($trackingCode)
    {
        $report = Report::where('tracking_code', $trackingCode)->firstOrFail();
        /** @var \App\Models\User|null $user */
        $user   = auth()->user();

        if (!$user || !$user->isTeacher()) abort(403);

        if ($report->claimed_by && $report->claimed_by !== $user->id) {
            return back()->with('error', 'Laporan ini sudah diambil oleh guru lain.');
        }

        $report->update(['claimed_by' => $user->id, 'claimed_at' => now()]);

        return redirect()->route('teacher.reports.show', $report->id)
                         ->with('success', 'Laporan berhasil diambil! Data murid kini terbuka untuk Anda.');
    }
}