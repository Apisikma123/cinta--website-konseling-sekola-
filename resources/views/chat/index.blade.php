@php
    $isStudentLayout = $isStudent ?? (!auth()->check() || !auth()->user()->isTeacher());
    $chatLayout      = $chatLayout ?? ($isStudentLayout ? 'layouts.student' : 'layouts.teacher');
    $chatPostUrl     = $isStudentLayout
        ? route('chat.murid.store',    ['tracking_code' => $report->tracking_code])
        : route('teacher.chat.store',  ['tracking_code' => $report->tracking_code]);
    $chatMessagesUrl = $isStudentLayout
        ? route('chat.murid.messages',   ['tracking_code' => $report->tracking_code])
        : route('teacher.chat.messages', ['tracking_code' => $report->tracking_code]);
    $chatStatusUrl = $isStudentLayout
        ? route('chat.murid.status', ['tracking_code' => $report->tracking_code])
        : route('teacher.chat.status', ['tracking_code' => $report->tracking_code]);
    $currentUserId = auth()->check() ? auth()->id() : null;
    $recipientId   = $isStudentLayout ? $report->guru_id : null;
@endphp

@extends($chatLayout)

@section('content')
<div class="max-w-4xl mx-auto" data-recipient-id="{{ $recipientId }}">

    {{-- ── Header ── --}}
    <div class="bg-[#2d224d] rounded-t-2xl shadow-lg border-b border-purple-900/20 p-5 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-500/10 blur-3xl rounded-full pointer-events-none"></div>
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4 flex-1">

                @if($isStudentLayout)
                    @if($report->guru && $report->guru->profile_photo)
                        <img src="{{ asset('storage/' . $report->guru->profile_photo) }}?v={{ time() }}"
                             alt="{{ $report->guru->name }}"
                             class="w-12 h-12 rounded-xl object-cover border border-purple-400/30 shadow-sm flex-shrink-0">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-purple-600 flex items-center justify-center text-white text-lg font-bold shadow-sm flex-shrink-0">
                            {{ $report->guru ? strtoupper(substr($report->guru->name, 0, 1)) : 'G' }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="text-white font-bold text-base tracking-tight">{{ $report->guru->name ?? 'Konselor BK' }}</h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="relative flex h-2.5 w-2.5">
                                <span id="onlinePing" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                                <span id="onlineStatus" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            </span>
                            <span id="onlineText" class="text-white text-[10px] font-black uppercase tracking-widest">Mengecek...</span>
                        </div>
                    </div>
                @else
                    @php
                        $studentName = $report->nama_murid ?? 'Anonim';
                        $initials    = collect(explode(' ', $studentName))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->join('');
                    @endphp
                    <div class="w-12 h-12 rounded-xl bg-purple-800/50 border border-purple-400/30 flex items-center justify-center text-purple-200 text-lg font-bold shadow-sm flex-shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-white font-bold text-base tracking-tight truncate">{{ $studentName }}</h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="relative flex h-2.5 w-2.5 flex-shrink-0">
                                <span id="onlinePing" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                                <span id="onlineStatus" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            </span>
                            <span id="onlineText" class="text-white text-[10px] font-black uppercase tracking-widest">Mengecek...</span>
                        </div>
                    </div>
                @endif

            </div>
            <a href="{{ !$isStudentLayout ? route('teacher.reports.show', $report->id) : route('track.status', $report->tracking_code) }}"
               class="w-10 h-10 flex items-center justify-center text-white hover:bg-white/10 rounded-xl transition border border-transparent hover:border-white/20 active:scale-90 flex-shrink-0">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-b-2xl border-x border-b border-gray-100 shadow-xl overflow-hidden flex flex-col">

        {{-- ── Safety Alert ── --}}
        <div class="bg-amber-50 border-b border-amber-100 p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shield-halved text-sm"></i>
            </div>
            <div class="text-xs text-amber-800 leading-relaxed font-medium">
                <span class="font-bold">Keamanan Data:</span> Komunikasi ini dienkripsi secara internal. Riwayat akan dihapus otomatis dalam 3 hari untuk menjaga kerahasiaan murid.
            </div>
        </div>

        {{-- ── Chat Box ── --}}
        <div id="chat-box" class="h-[500px] overflow-y-auto overflow-x-hidden p-6 bg-chat-pattern scroll-smooth w-full">

            <div id="messages-list" class="space-y-2">
                @foreach($chats as $chat)
                    @php
                        $isTeacherSender  = $chat->sender_type === 'teacher';
                        $name             = $isTeacherSender ? ($chat->sender->name ?? 'Guru BK') : ($report->nama_murid ?? 'Murid');
                        $isCurrentUserMsg = ($isStudentLayout && $chat->sender_type === 'student')
                                         || (!$isStudentLayout && auth()->check() && auth()->user()->isTeacher()
                                             && $chat->sender_type === 'teacher' && (int)$chat->sender_id === (int)auth()->id());
                        $isDeleted        = $chat->deleted_for_everyone ?? false;
                    @endphp

                    <div class="flex {{ $isCurrentUserMsg ? 'justify-end' : 'justify-start' }} group items-end gap-2 w-full"
                         data-message-wrapper="{{ $chat->id }}">
                        <div class="flex flex-col min-w-0 bubble-col {{ $isCurrentUserMsg ? 'items-end' : 'items-start' }}">
                            <div class="flex items-center gap-2 mb-1 px-1">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $name }}</span>
                                <span class="text-[10px] text-slate-300 font-medium tabular-nums">{{ $chat->created_at->format('H:i') }}</span>
                            </div>

                            @if($isDeleted)
                                <div id="bubble-{{ $chat->id }}" class="chat-bubble bg-gray-100 border border-gray-200 text-slate-400 rounded-2xl px-5 py-3">
                                    <div class="text-sm italic text-slate-400" id="msg-text-{{ $chat->id }}">
                                        <i class="fas fa-ban text-xs mr-1"></i> Pesan ini telah dihapus
                                    </div>
                                </div>
                            @else
                                <div id="bubble-{{ $chat->id }}"
                                     class="chat-bubble rounded-2xl px-5 py-3 {{ $isCurrentUserMsg ? 'bg-purple-600 text-white bubble-me' : 'bg-white border border-gray-200 text-slate-700 bubble-other' }}"
                                     style="{{ $isCurrentUserMsg ? 'background-color:#7c3aed;color:#fff;' : 'background-color:#fff;color:#334155;' }}">
                                    <div class="text-sm leading-relaxed whitespace-pre-wrap break-words"
                                         id="msg-text-{{ $chat->id }}"
                                         data-msg="{{ e($chat->message) }}">{{ $chat->message }}@if($chat->edited_at)<span class="text-[9px] opacity-60 italic ml-1">(diedit)</span>@endif</div>
                                </div>

                                @if($isCurrentUserMsg)
                                    <div id="edit-box-{{ $chat->id }}" class="edit-box w-full bg-white border border-purple-200 rounded-xl p-2 shadow-lg mt-1">
                                        <textarea id="edit-input-{{ $chat->id }}" rows="2"
                                                  class="w-full text-sm p-2 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-purple-200 resize-none text-slate-700"></textarea>
                                        <div class="flex justify-end gap-2 mt-2">
                                            <button onclick="cancelEdit('{{ $chat->id }}')" type="button"
                                                    class="text-xs px-3 py-1.5 rounded-lg text-slate-500 hover:bg-slate-100">
                                                <i class="fas fa-times"></i> Batal
                                            </button>
                                            <button onclick="saveEdit('{{ $chat->id }}')" type="button"
                                                    class="text-xs px-3 py-1.5 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600">
                                                <i class="fas fa-check"></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-1 px-1 flex items-center gap-1 text-[9px] font-black uppercase tracking-tighter {{ $chat->is_read ? 'text-emerald-500' : 'text-slate-300' }} read-status"
                                         data-status-id="{{ $chat->id }}">
                                        <i class="fas {{ $chat->is_read ? 'fa-check-double' : 'fa-check' }}"></i>
                                        {{ $chat->is_read ? 'Terbaca' : 'Terkirim' }}
                                    </div>
                                @endif
                            @endif
                        </div>

                        @if($isCurrentUserMsg && !$isDeleted)
                            <div class="relative flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity mb-6">
                                <button onclick="toggleMenu('{{ $chat->id }}')" type="button"
                                        class="w-7 h-7 flex items-center justify-center bg-white rounded-full shadow-sm border border-gray-100 text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-ellipsis-v text-xs"></i>
                                </button>
                                <div id="menu-{{ $chat->id }}"
                                     class="msg-menu absolute bottom-8 right-0 bg-white rounded-lg shadow-xl border border-gray-100 w-32 text-sm overflow-hidden py-1">
                                    <button onclick="startEdit('{{ $chat->id }}')" type="button"
                                            class="w-full text-left px-4 py-2 hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                                        <i class="fas fa-pen text-xs"></i> Edit
                                    </button>
                                    <button onclick="confirmDelete('{{ $chat->id }}')" type="button"
                                            class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 flex items-center gap-2">
                                        <i class="fas fa-trash text-xs"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            <div id="delete-confirm-{{ $chat->id }}"
                                 class="del-modal fixed inset-0 z-[999] items-center justify-center bg-black/30 backdrop-blur-sm">
                                <div class="bg-white rounded-2xl p-5 shadow-2xl w-60 text-center mx-4">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-trash text-red-500"></i>
                                    </div>
                                    <p class="text-slate-700 font-semibold text-sm mb-1">Hapus pesan?</p>
                                    <p class="text-slate-400 text-xs mb-4">Pesan akan dihapus untuk semua.</p>
                                    <div class="flex gap-2">
                                        <button onclick="cancelDelete('{{ $chat->id }}')" type="button"
                                                class="flex-1 py-2 bg-slate-100 rounded-xl text-slate-600 font-medium text-sm">Batal</button>
                                        <button onclick="executeDelete('{{ $chat->id }}')" type="button"
                                                class="flex-1 py-2 bg-red-500 rounded-xl text-white font-medium text-sm">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- ── TYPING BUBBLE (using custom CSS classes to control visibility)
                 Previously fixed conflicting Tailwind display utilities by using
                 custom CSS classes .typing-hidden and .typing-visible instead
            --}}
            <div id="typing-indicator" class="typing-hidden mt-2">
                <div class="flex justify-start items-end gap-2 pb-1">
                    <div class="flex flex-col items-start bubble-col">
                        <div class="flex items-center gap-2 mb-1 px-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest" id="typing-name"></span>
                            <span class="text-[10px] text-purple-400 font-semibold italic">sedang mengetik</span>
                        </div>
                        <div class="chat-bubble bg-white border border-gray-200 bubble-other px-5 py-3.5 shadow-sm">
                            <div class="flex items-center gap-1.5 h-4">
                                <span class="typing-dot"></span>
                                <span class="typing-dot" style="animation-delay:.18s"></span>
                                <span class="typing-dot" style="animation-delay:.36s"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Input ── --}}
        <div class="px-4 py-3 bg-white border-t border-gray-100">
            <div class="flex items-end gap-3">
                <div class="flex-1">
                    <textarea id="chat-message" rows="1"
                              class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3 text-sm
                                     focus:bg-white focus:ring-4 focus:ring-purple-100 focus:border-purple-300
                                     transition-all resize-none shadow-inner leading-5"
                              style="max-height:160px;overflow-y:auto;"
                              placeholder="Ketik pesan konsultasi..."></textarea>
                </div>
                <button id="chatSubmitBtn" type="button"
                        class="w-12 h-12 bg-purple-600 text-white rounded-2xl shadow-lg shadow-purple-200
                               flex items-center justify-center hover:bg-purple-700 active:scale-95
                               transition-all flex-shrink-0 self-end">
                    <i class="fas fa-paper-plane text-base"></i>
                </button>
            </div>
        </div>

    </div>
</div>

<style>
/* ── Scrollbar ── */
#chat-box::-webkit-scrollbar          { width:4px }
#chat-box::-webkit-scrollbar-track    { background:transparent }
#chat-box::-webkit-scrollbar-thumb    { background:#E2E8F0;border-radius:10px }
#chat-box::-webkit-scrollbar-thumb:hover { background:#CBD5E1 }

/* ── Background ── */
.bg-chat-pattern {
    background-color:#f0f2f5;
    background-image:radial-gradient(#cbd5e1 1px,transparent 1px);
    background-size:20px 20px;
}

/* ── Bubble column: responsive max-width ── */
.bubble-col   { max-width:85% }
@media(min-width:768px){ .bubble-col { max-width:65% } }

/* ── Bubble shapes ── */
.chat-bubble  { word-break:break-word; overflow-wrap:break-word }
.bubble-me    { border-radius:18px 18px 4px 18px !important; box-shadow:0 1px 2px rgba(0,0,0,.15) }
.bubble-other { border-radius:18px 18px 18px 4px !important; box-shadow:0 1px 2px rgba(0,0,0,.08) }

/* ── FIX MENU NABRAK: stacking context per-wrapper ── */
[data-message-wrapper] {
    animation: msgIn .14s ease-out both;
    position: relative;
    z-index: 0;
}
[data-message-wrapper]:hover   { z-index: 20; }
[data-message-wrapper].menu-open { z-index: 50 !important; }

@keyframes msgIn {
    from { opacity:0; transform:translateY(6px) scale(.97) }
    to   { opacity:1; transform:translateY(0) scale(1) }
}
[data-message-wrapper].sending { opacity:.55 }

/* ── Edit box: default sembunyi ── */
.edit-box      { display: none; }
.edit-box.open { display: block; }

/* ── Context menu: default sembunyi ── */
.msg-menu      { display: none; }
.msg-menu.open { display: block; }

/* ── Delete modal: default sembunyi ── */
.del-modal      { display: none; }
.del-modal.open { display: flex; }

/* ── FIX TYPING BUBBLE: pakai class khusus, bukan Tailwind hidden/flex ──
   Tailwind hidden = display:none !important → override flex → bubble tidak pernah muncul
── */
.typing-hidden  { display: none; }
.typing-visible {
    display: block;
    animation: typingFadeIn .2s ease-out both;
}
@keyframes typingFadeIn {
    from { opacity:0; transform:translateY(4px) }
    to   { opacity:1; transform:translateY(0) }
}

/* ── Typing dots ── */
.typing-dot {
    display: inline-block;
    width: 8px; height: 8px;
    background: #a78bfa;
    border-radius: 50%;
    animation: typingBounce .9s infinite ease-in-out;
}
@keyframes typingBounce {
    0%,60%,100% { transform:translateY(0);    background:#c4b5fd }
    30%          { transform:translateY(-7px); background:#7c3aed }
}
</style>

<script>
(function () {
    /* ── Config ── */
    const CSRF         = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const POST_URL     = @json($chatPostUrl);
    const MESSAGES_URL = @json($chatMessagesUrl);
    const STATUS_URL   = @json($chatStatusUrl);
    const IS_STUDENT   = @json($isStudentLayout);
    const CURRENT_UID  = @json($currentUserId);
    const TRACKING     = @json($report->tracking_code);
    const STUDENT_NAME = @json($report->nama_murid ?? 'Murid');
    const GURU_NAME    = @json($report->guru->name ?? 'Guru BK');

    const BASE_ACTION = IS_STUDENT
        ? `/chat-murid/${TRACKING}/messages/`
        : `/teacher/chat/${TRACKING}/messages/`;

    const UPDATES_URL = IS_STUDENT
        ? `/chat-murid/${TRACKING}/updates`
        : `/teacher/chat/${TRACKING}/updates`;

    const TYPING_URL  = IS_STUDENT
        ? `/chat-murid/${TRACKING}/typing`
        : `/teacher/chat/${TRACKING}/typing`;

    /* ── DOM ── */
    const chatBox        = document.getElementById('chat-box');
    const textarea       = document.getElementById('chat-message');
    const sendBtn        = document.getElementById('chatSubmitBtn');
    const typingEl       = document.getElementById('typing-indicator');
    const typingName     = document.getElementById('typing-name');
    const elOnlineStatus = document.getElementById('onlineStatus');
    const elOnlinePing   = document.getElementById('onlinePing');
    const elOnlineText   = document.getElementById('onlineText');

    /* ── State ── */
    let lastId          = 0;
    let autoScroll      = true;
    let pollingMsg      = false;
    let pollingStatus   = false;
    let isTypingSent    = false;
    let typingDebounce  = null;

    /* ── Adaptive Polling ── */
    const POLL_FAST     = 2000;
    const POLL_SLOW     = 8000;
    const STATUS_FAST   = 3000;
    const STATUS_SLOW   = 10000;
    const IDLE_AFTER    = 30000;
    let lastActivity    = Date.now();
    let msgInterval     = null;
    let statusInterval  = null;
    let updInterval     = null;

    const rendered = new Set();
    // Track deleted message IDs so polling never re-renders them
    const deletedIds = new Set();
    // Track edit timestamps: id -> edited_at ISO string (prevents redundant DOM flicker)
    const editedAt = {};

    document.querySelectorAll('[data-message-wrapper]').forEach(el => {
        const n = parseInt(el.dataset.messageWrapper, 10);
        if (!isNaN(n)) { rendered.add(String(n)); if (n > lastId) lastId = n; }
    });
    // Seed deletedIds from server-rendered deleted bubbles
    document.querySelectorAll('[data-message-wrapper]').forEach(el => {
        const id = el.dataset.messageWrapper;
        const txt = document.getElementById(`msg-text-${id}`);
        if (txt && txt.classList.contains('italic')) deletedIds.add(id);
    });

    /* ── Helpers ── */
    const esc = t => String(t)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');

    const scrollBottom = (force = false) => {
        if (force || autoScroll) chatBox.scrollTop = chatBox.scrollHeight;
    };

    const isMine = chat => IS_STUDENT
        ? chat.sender_type === 'student'
        : chat.sender_type === 'teacher' && CURRENT_UID != null && Number(chat.sender_id) === Number(CURRENT_UID);

    const fmtTime = iso =>
        new Date(iso).toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });

    /* ── Auto-grow textarea ── */
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 160) + 'px';
    });

    /* ── Typing ping: kirim maksimal sekali per 2.5 detik selama mengetik ── */
    textarea.addEventListener('input', () => {
        if (!isTypingSent) {
            isTypingSent = true;
            fetch(TYPING_URL, {
                method: 'POST',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF() },
                body: JSON.stringify({ is_student: IS_STUDENT })
            }).catch(() => {});
            
            setTimeout(() => { isTypingSent = false; }, 2500);
        }
    });

    textarea.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });
    sendBtn.addEventListener('click', sendMessage);

    /* ── Build bubble ── */
    function buildBubble(chat, isOptimistic = false) {
        const mine    = isOptimistic || isMine(chat);
        const name    = chat.sender_type === 'teacher' ? (chat.sender?.name ?? GURU_NAME) : STUDENT_NAME;
        const deleted = chat.deleted_for_everyone || chat.is_deleted;
        const edited  = (chat.edited_at || chat.is_edited) && !deleted;
        const time    = fmtTime(chat.created_at ?? new Date().toISOString());
        const id      = chat.id;

        const bubbleCls = mine
            ? 'bg-purple-600 text-white bubble-me'
            : 'bg-white border border-gray-200 text-slate-700 bubble-other';

        const bubbleStyle = mine
            ? 'background-color:#7c3aed;color:#fff;'
            : 'background-color:#fff;color:#334155;';

        const bodyHtml = deleted
            ? `<div class="text-sm italic text-slate-400" id="msg-text-${id}">
                   <i class="fas fa-ban text-xs mr-1"></i> Pesan ini telah dihapus
               </div>`
            : `<div class="text-sm leading-relaxed whitespace-pre-wrap break-words"
                    id="msg-text-${id}" data-msg="${esc(chat.message)}"
               >${esc(chat.message)}${edited ? ' <span class="text-[9px] opacity-60 italic ml-1">(diedit)</span>' : ''}</div>`;

        const editBox = (mine && !isOptimistic && !deleted) ? `
            <div id="edit-box-${id}" class="edit-box w-full bg-white border border-purple-200 rounded-xl p-2 shadow-lg mt-1">
                <textarea id="edit-input-${id}" rows="2"
                          class="w-full text-sm p-2 bg-gray-50 rounded-lg border border-gray-200 focus:ring-2 focus:ring-purple-200 resize-none text-slate-700"></textarea>
                <div class="flex justify-end gap-2 mt-2">
                    <button onclick="cancelEdit('${id}')" type="button"
                            class="text-xs px-3 py-1.5 rounded-lg text-slate-500 hover:bg-slate-100">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button onclick="saveEdit('${id}')" type="button"
                            class="text-xs px-3 py-1.5 rounded-lg bg-emerald-500 text-white hover:bg-emerald-600">
                        <i class="fas fa-check"></i> Simpan
                    </button>
                </div>
            </div>` : '';

        const deleteModal = (mine && !isOptimistic && !deleted) ? `
            <div id="delete-confirm-${id}" class="del-modal fixed inset-0 z-[999] items-center justify-center bg-black/30 backdrop-blur-sm">
                <div class="bg-white rounded-2xl p-5 shadow-2xl w-60 text-center mx-4">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-trash text-red-500"></i>
                    </div>
                    <p class="text-slate-700 font-semibold text-sm mb-1">Hapus pesan?</p>
                    <p class="text-slate-400 text-xs mb-4">Pesan akan dihapus untuk semua.</p>
                    <div class="flex gap-2">
                        <button onclick="cancelDelete('${id}')" type="button"
                                class="flex-1 py-2 bg-slate-100 rounded-xl text-slate-600 font-medium text-sm">Batal</button>
                        <button onclick="executeDelete('${id}')" type="button"
                                class="flex-1 py-2 bg-red-500 rounded-xl text-white font-medium text-sm">Hapus</button>
                    </div>
                </div>
            </div>` : '';

        const readStatus = mine
            ? (isOptimistic
                ? `<div class="mt-1 px-1 flex items-center gap-1 text-[9px] font-black uppercase tracking-tighter text-slate-300">
                       <i class="fas fa-clock"></i> Mengirim…
                   </div>`
                : `<div class="mt-1 px-1 flex items-center gap-1 text-[9px] font-black uppercase tracking-tighter
                              ${chat.is_read ? 'text-emerald-500' : 'text-slate-300'} read-status"
                        data-status-id="${id}">
                       <i class="fas ${chat.is_read ? 'fa-check-double' : 'fa-check'}"></i>
                       ${chat.is_read ? 'Terbaca' : 'Terkirim'}
                   </div>`)
            : '';

        const ctxMenu = (mine && !isOptimistic && !deleted) ? `
            <div class="relative flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity mb-6">
                <button onclick="toggleMenu('${id}')" type="button"
                        class="w-7 h-7 flex items-center justify-center bg-white rounded-full shadow-sm border border-gray-100 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-ellipsis-v text-xs"></i>
                </button>
                <div id="menu-${id}" class="msg-menu absolute bottom-8 right-0 bg-white rounded-lg shadow-xl border border-gray-100 w-32 text-sm overflow-hidden py-1">
                    <button onclick="startEdit('${id}')" type="button"
                            class="w-full text-left px-4 py-2 hover:bg-slate-50 flex items-center gap-2 text-slate-600">
                        <i class="fas fa-pen text-xs"></i> Edit
                    </button>
                    <button onclick="confirmDelete('${id}')" type="button"
                            class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 flex items-center gap-2">
                        <i class="fas fa-trash text-xs"></i> Hapus
                    </button>
                </div>
            </div>` : '';

        return `
            <div class="flex ${mine ? 'justify-end' : 'justify-start'} group items-end gap-2 w-full${isOptimistic ? ' sending' : ''}"
                 data-message-wrapper="${id}">
                <div class="flex flex-col min-w-0 bubble-col ${mine ? 'items-end' : 'items-start'}">
                    <div class="flex items-center gap-2 mb-1 px-1">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">${esc(name)}</span>
                        <span class="text-[10px] text-slate-300 font-medium tabular-nums">${time}</span>
                    </div>
                    <div id="bubble-${id}" class="chat-bubble rounded-2xl px-5 py-3 ${deleted ? 'bg-gray-100 border border-gray-200 text-slate-400' : bubbleCls}" style="${deleted ? '' : bubbleStyle}">
                        ${bodyHtml}
                    </div>
                    ${editBox}
                    ${deleteModal}
                    ${readStatus}
                </div>
                ${ctxMenu}
            </div>`;
    }

    /* ── renderMessage: handles new messages AND updates to existing ones ── */
    function renderMessage(chat) {
        const key = String(chat.id);

        if (rendered.has(key)) {
            // Already rendered — check for updates (edit / delete)
            applyMessageUpdate({
                id: chat.id,
                message: chat.deleted_for_everyone ? null : chat.message,
                is_deleted: !!(chat.is_deleted || chat.deleted_for_everyone),
                is_edited: !!(chat.is_edited || chat.edited_at),
            });
            // Update read status if needed
            if (chat.is_read) {
                const s = document.querySelector(`[data-status-id="${chat.id}"]`);
                if (s && !s.classList.contains('text-emerald-500')) {
                    s.className = s.className.replace('text-slate-300','text-emerald-500');
                    s.innerHTML = '<i class="fas fa-check-double"></i> Terbaca';
                }
            }
            return;
        }
        rendered.add(key);
        typingEl.insertAdjacentHTML('beforebegin', buildBubble(chat));
        if (chat.id > lastId) lastId = chat.id;
        scrollBottom();

        if (!isMine(chat) && !chat.is_read) {
            fetch(`${BASE_ACTION}${chat.id}/mark-read`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF() }
            }).catch(() => {});
        }
    }

    /* ── applyMessageUpdate: apply edit or delete to an already-rendered bubble ── */
    function applyMessageUpdate(upd) {
        const id     = String(upd.id);
        const bubble = document.getElementById(`bubble-${id}`);
        const txt    = document.getElementById(`msg-text-${id}`);
        if (!bubble || !txt) return; // message not in DOM yet – poll() will handle it

        // ── DELETED ──
        if (upd.is_deleted) {
            if (deletedIds.has(id)) return; // already shown as deleted, skip
            deletedIds.add(id);
            bubble.className = 'chat-bubble bg-gray-100 border border-gray-200 text-slate-400 rounded-2xl px-5 py-3';
            txt.innerHTML    = '<i class="fas fa-ban text-xs mr-1"></i> Pesan ini telah dihapus';
            txt.className    = 'text-sm italic text-slate-400';
            document.querySelector(`[data-message-wrapper="${id}"] .relative.flex-shrink-0`)?.remove();
            document.querySelector(`[data-status-id="${id}"]`)?.remove();
            return;
        }

        // ── EDITED ──
        if (upd.is_edited && upd.message != null) {
            // Dedup: skip if edited_at unchanged (prevents DOM flicker every 2s poll)
            if (upd.edited_at && editedAt[id] === upd.edited_at) return;
            editedAt[id]    = upd.edited_at ?? String(Date.now());
            txt.dataset.msg = upd.message;
            txt.innerHTML   = esc(upd.message)
                + ' <span class="text-[9px] opacity-60 italic ml-1">(diedit)</span>';
        }
    }

    /* ── Send ── */
    function sendMessage() {
        const msg = textarea.value.trim();
        if (!msg) return;

        textarea.value = '';
        textarea.style.height = 'auto';
        textarea.focus();

        const fakeId   = `opt-${Date.now()}-${Math.random().toString(36).slice(2,5)}`;
        const fakeChat = {
            id: fakeId, sender_type: IS_STUDENT ? 'student' : 'teacher',
            sender_id: CURRENT_UID, message: msg,
            is_read: false, created_at: new Date().toISOString()
        };
        typingEl.insertAdjacentHTML('beforebegin', buildBubble(fakeChat, true));
        scrollBottom(true);

        fetch(POST_URL, {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF() },
            body: JSON.stringify({ message: msg })
        })
        .then(r => r.json())
        .then(data => {
            const fake = document.querySelector(`[data-message-wrapper="${fakeId}"]`);
            if (data.chat && fake) {
                fake.outerHTML = buildBubble(data.chat);
                rendered.add(String(data.chat.id));
                if (data.chat.id > lastId) lastId = data.chat.id;
            } else { fake?.remove(); }
            scrollBottom(true);
        })
        .catch(() => {
            document.querySelector(`[data-message-wrapper="${fakeId}"]`)?.remove();
        });
    }

    /* ── Poll messages (adaptive) ── */
    async function poll() {
        if (pollingMsg) return;
        pollingMsg = true;
        try {
            const url  = lastId > 0 ? `${MESSAGES_URL}?after_id=${lastId}` : MESSAGES_URL;
            const data = await fetch(url, { cache: 'no-store', signal: AbortSignal.timeout(5000) }).then(r => r.json());
            if (Array.isArray(data)) {
                data.forEach(renderMessage);
                if (data.length > 0) touchActivity();
            }
        } catch(_) {}
        finally { pollingMsg = false; }
    }

    /* ── Poll updates: sync edits & deletes to OTHER party in real-time ── */
    let pollingUpdates = false;
    async function pollUpdates() {
        if (pollingUpdates) return;
        pollingUpdates = true;
        try {
            // ?secs=12 tells server to look back 12s – slightly longer than our 2s poll
            // so no gap regardless of clock drift. Server dedupes via edited_at tracking.
            const r = await fetch(`${UPDATES_URL}?secs=12`, {
                cache: 'no-store',
                signal: AbortSignal.timeout(4000)
            });
            if (!r.ok) {
                console.error('[chat] pollUpdates error', r.status, await r.text().catch(()=>''));
                return;
            }
            const data = await r.json();
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(applyMessageUpdate);
            }
        } catch(err) {
            console.warn('[chat] pollUpdates fetch failed:', err?.message ?? err);
        } finally {
            pollingUpdates = false;
        }
    }

    /* ── Combined poll: typing + presence in ONE request ── */
    async function pollStatus() {
        if (pollingStatus) return; // Allow background polling so read receipts update instantly
        pollingStatus = true;
        try {
            const url  = `${STATUS_URL}?is_student=${IS_STUDENT ? 1 : 0}&_t=${Date.now()}`;
            const data = await fetch(url, { cache: 'no-store', signal: AbortSignal.timeout(4000) }).then(r => r.json());

            // Typing
            const theyType = IS_STUDENT ? data.typing?.teacher : data.typing?.student;
            if (theyType) {
                if (typingName) typingName.textContent = IS_STUDENT ? GURU_NAME : STUDENT_NAME;
                typingEl.className = 'typing-visible mt-2';
                if (autoScroll) scrollBottom(true);
            } else {
                typingEl.className = 'typing-hidden mt-2';
            }

            // Presence
            const online = IS_STUDENT ? data.teacher : data.student;
            setPresence(online);

            // Read Statuses
            if (data.read_ids && Array.isArray(data.read_ids)) {
                data.read_ids.forEach(id => {
                    const s = document.querySelector(`[data-status-id="${id}"]`);
                    if (s && !s.classList.contains('text-emerald-500')) {
                        s.className = s.className.replace('text-slate-300', 'text-emerald-500');
                        s.innerHTML = '<i class="fas fa-check-double"></i> Terbaca';
                    }
                });
            }

        } catch(_) {}
        finally { pollingStatus = false; }
    }

    /* ── Adaptive polling: speed up when active, slow down when idle ── */
    function touchActivity() { lastActivity = Date.now(); adjustPolling(); }

    function isIdle() { return (Date.now() - lastActivity) > IDLE_AFTER; }

    function adjustPolling() {
        const idle      = isIdle();
        const wantMsg   = idle ? POLL_SLOW : POLL_FAST;
        const wantStat  = idle ? STATUS_SLOW : STATUS_FAST;
        const wantUpd   = idle ? 6000 : 2000; // updates interval

        if (msgInterval?._ms !== wantMsg) {
            clearInterval(msgInterval);
            msgInterval = setInterval(poll, wantMsg);
            msgInterval._ms = wantMsg;
        }
        if (statusInterval?._ms !== wantStat) {
            clearInterval(statusInterval);
            statusInterval = setInterval(pollStatus, wantStat);
            statusInterval._ms = wantStat;
        }
        if (updInterval?._ms !== wantUpd) {
            clearInterval(updInterval);
            updInterval = setInterval(pollUpdates, wantUpd);
            updInterval._ms = wantUpd;
        }
    }

    // Track user activity
    textarea.addEventListener('focus', touchActivity);
    textarea.addEventListener('input', touchActivity);
    chatBox.addEventListener('scroll', touchActivity);

    /* ── Update dot online/offline di header ── */
    function setPresence(online) {
        if (!elOnlineStatus) return;
        if (online) {
            elOnlineStatus.className = 'relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500';
            elOnlinePing.className   = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75';
            elOnlineText.textContent = 'Online';
            elOnlineText.className   = 'text-white text-[10px] font-black uppercase tracking-widest';
        } else {
            elOnlineStatus.className = 'relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500';
            elOnlinePing.className   = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75';
            elOnlineText.textContent = 'Offline';
            elOnlineText.className   = 'text-white text-[10px] font-black uppercase tracking-widest';
        }
    }

    chatBox.addEventListener('scroll', () => {
        autoScroll = chatBox.scrollTop >= chatBox.scrollHeight - chatBox.clientHeight - 80;
    });

    /* ── Context menu: pakai CSS class ── */
    window.toggleMenu = id => {
        document.querySelectorAll('.msg-menu.open').forEach(m => {
            if (m.id !== `menu-${id}`) {
                m.classList.remove('open');
                m.closest('[data-message-wrapper]')?.classList.remove('menu-open');
            }
        });
        const menu    = document.getElementById(`menu-${id}`);
        const wrapper = document.querySelector(`[data-message-wrapper="${id}"]`);
        if (!menu || !wrapper) return;
        const opening = !menu.classList.contains('open');
        menu.classList.toggle('open', opening);
        wrapper.classList.toggle('menu-open', opening);
    };

    window.startEdit = id => {
        document.getElementById(`menu-${id}`)?.classList.remove('open');
        document.querySelector(`[data-message-wrapper="${id}"]`)?.classList.remove('menu-open');
        const msgEl = document.getElementById(`msg-text-${id}`);
        const inp   = document.getElementById(`edit-input-${id}`);
        if (inp && msgEl) inp.value = msgEl.dataset.msg ?? '';
        document.getElementById(`edit-box-${id}`)?.classList.add('open');
    };

    window.cancelEdit = id => document.getElementById(`edit-box-${id}`)?.classList.remove('open');

    window.saveEdit = id => {
        const inp    = document.getElementById(`edit-input-${id}`);
        const newMsg = inp?.value.trim();
        if (!newMsg) return;
        const msgEl = document.getElementById(`msg-text-${id}`);
        if (msgEl) {
            msgEl.dataset.msg = newMsg;
            msgEl.innerHTML   = esc(newMsg) + ' <span class="text-[9px] opacity-60 italic ml-1">(diedit)</span>';
        }
        window.cancelEdit(id);
        // Track locally so pollUpdates doesn't overwrite the sender's just-edited DOM
        // The real edited_at from server will match this sentinel after first pollUpdates
        editedAt[String(id)] = '__pending__';
        fetch(`${BASE_ACTION}${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF() },
            body: JSON.stringify({ message: newMsg })
        }).then(r => r.ok ? r.json() : null)
          .then(data => {
              // Store real edited_at from server so dedup comparison works correctly
              if (data?.chat?.edited_at) editedAt[String(id)] = data.chat.edited_at;
          })
          .catch(() => {});
    };

    window.confirmDelete = id => {
        document.getElementById(`menu-${id}`)?.classList.remove('open');
        document.querySelector(`[data-message-wrapper="${id}"]`)?.classList.remove('menu-open');
        document.getElementById(`delete-confirm-${id}`)?.classList.add('open');
    };

    window.cancelDelete = id => document.getElementById(`delete-confirm-${id}`)?.classList.remove('open');

    window.executeDelete = id => {
        window.cancelDelete(id);
        const idStr  = String(id);
        const bubble = document.getElementById(`bubble-${id}`);
        const txt    = document.getElementById(`msg-text-${id}`);
        const ctxBtn = document.querySelector(`[data-message-wrapper="${id}"] .relative.flex-shrink-0`);
        const status = document.querySelector(`[data-status-id="${id}"]`);
        if (bubble) bubble.className = 'chat-bubble bg-gray-100 border border-gray-200 text-slate-400 rounded-2xl px-5 py-3';
        if (txt)    { txt.innerHTML = '<i class="fas fa-ban text-xs mr-1"></i> Pesan ini telah dihapus'; txt.className = 'text-sm italic text-slate-400'; }
        ctxBtn?.remove();
        status?.remove();
        // Mark as deleted locally so it never reappears from polling
        deletedIds.add(idStr);
        fetch(`${BASE_ACTION}${id}`, {
            method: 'DELETE',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF() }
        }).catch(() => {});
    };

    document.addEventListener('click', e => {
        if (!e.target.closest('.msg-menu') && !e.target.closest('[onclick^="toggleMenu"]')) {
            document.querySelectorAll('.msg-menu.open').forEach(m => {
                m.classList.remove('open');
                m.closest('[data-message-wrapper]')?.classList.remove('menu-open');
            });
        }
    });

    /* ── Boot ── */
    scrollBottom(true);
    poll();
    pollStatus();
    pollUpdates();

    // Start adaptive polling
    msgInterval    = setInterval(poll, POLL_FAST);
    msgInterval._ms = POLL_FAST;
    statusInterval = setInterval(pollStatus, STATUS_FAST);
    statusInterval._ms = STATUS_FAST;
    updInterval    = setInterval(pollUpdates, 2000);
    updInterval._ms = 2000;

    // Re-check idle every 15s and adjust intervals
    setInterval(adjustPolling, 15000);

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            touchActivity();
            poll();
            pollStatus();
            pollUpdates();
        }
    });

})();
</script>
@endsection