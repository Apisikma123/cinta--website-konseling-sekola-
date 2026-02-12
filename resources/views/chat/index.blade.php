@php
    $isStudentLayout = $isStudent ?? (!auth()->check() || !auth()->user()->isTeacher());
    
    $chatLayout = $chatLayout ?? ($isStudentLayout ? 'layouts.student' : 'layouts.teacher');
    
    $chatPostUrl = $isStudentLayout ? route('chat.murid.store', ['tracking_code' => $report->tracking_code]) : route('chat.store', ['tracking_code' => $report->tracking_code]);
    $chatMessagesUrl = $isStudentLayout ? route('chat.murid.messages', ['tracking_code' => $report->tracking_code]) : route('chat.messages', ['tracking_code' => $report->tracking_code]);
    $currentUserId = auth()->check() ? auth()->id() : null;
    $recipientId = $isStudentLayout ? $report->guru_id : null; 
@endphp

@extends($chatLayout)

@section('content')
<div class="max-w-4xl mx-auto" data-recipient-id="{{ $recipientId }}">
    
    <!-- Professional Chat Header -->
    <div class="bg-[#2d224d] rounded-t-2xl shadow-lg border-b border-purple-900/20 p-5 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-500/10 blur-3xl rounded-full pointer-events-none"></div>
        <div class="flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4 flex-1">
                @if($isStudentLayout)
                    @if($report->guru && $report->guru->profile_photo)
                        <img src="{{ asset('storage/' . $report->guru->profile_photo) }}?v={{ time() }}" 
                             alt="{{ $report->guru->name }}" 
                             class="w-12 h-12 rounded-xl object-cover border border-purple-400/30 shadow-sm">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-purple-600 flex items-center justify-center text-white text-lg font-bold shadow-sm">
                            {{ $report->guru ? strtoupper(substr($report->guru->name, 0, 1)) : 'G' }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="text-white font-bold text-base tracking-tight">{{ $report->guru->name ?? 'Konselor BK' }}</h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75" id="onlinePing"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-400/30" id="onlineStatus"></span>
                            </span>
                            <span id="onlineText" class="text-purple-300 text-[10px] font-black uppercase tracking-widest">Offline</span>
                        </div>
                    </div>
                @else
                    @php
                        $studentName = $report->nama_murid ?? 'Anonim';
                        $initials = collect(explode(' ', $studentName))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->join('');
                    @endphp
                    <div class="w-12 h-12 rounded-xl bg-purple-800/50 border border-purple-400/30 flex items-center justify-center text-purple-200 text-lg font-bold shadow-sm">
                        {{ $initials }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-white font-bold text-base tracking-tight">{{ $studentName }}</h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75" id="onlinePing"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-400/30" id="onlineStatus"></span>
                            </span>
                            <span id="onlineText" class="text-purple-300 text-[10px] font-black uppercase tracking-widest">Offline</span>
                        </div>
                    </div>
                @endif
            </div>
            
            <a href="{{ !$isStudentLayout ? route('teacher.reports.show', $report->id) : route('track.status', ['tracking_code' => $report->tracking_code]) }}" 
               class="w-10 h-10 flex items-center justify-center text-white hover:bg-white/10 rounded-xl transition border border-transparent hover:border-white/20 active:scale-90">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-b-2xl border-x border-b border-gray-100 shadow-xl overflow-hidden">
        <!-- Safety Alert -->
        <div class="bg-amber-50 border-b border-amber-100 p-4 flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shield-halved text-sm"></i>
            </div>
            <div class="text-xs text-amber-800 leading-relaxed font-medium">
                <span class="font-bold">Keamanan Data:</span> Komunikasi ini dienkripsi secara internal. Riwayat akan dihapus otomatis dalam 3 hari untuk menjaga kerahasiaan murid.
            </div>
        </div>

        <!-- Chat Container -->
        <div id="chat-box" class="h-[500px] overflow-y-auto p-6 space-y-6 bg-chat-pattern scroll-smooth">
            @foreach($chats as $chat)
                @php
                    $isTeacherSender = $chat->sender_type === 'teacher';
                    $name = $isTeacherSender ? ($chat->sender->name ?? 'Guru BK') : ($report->nama_murid ?? 'Murid');
                    
                    $isCurrentUserMessage = ($isStudentLayout && $chat->sender_type === 'student')
                        || (!$isStudentLayout && auth()->check() && auth()->user()->isTeacher() && $chat->sender_type === 'teacher' && $chat->sender_id === auth()->id());
                @endphp

                <div class="flex {{ $isCurrentUserMessage ? 'justify-end' : 'justify-start' }}">
                    <div class="flex flex-col {{ $isCurrentUserMessage ? 'items-end' : 'items-start' }} max-w-[85%] sm:max-w-[70%]">
                        <div class="flex items-center gap-2 mb-1.5 px-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $name }}</span>
                            <span class="text-[10px] text-slate-300 font-medium tabular-nums">{{ $chat->created_at->format('H:i') }}</span>
                        </div>
                        <div class="chat-bubble rounded-2xl px-5 py-3 shadow-sm {{ $isCurrentUserMessage ? 'bg-purple-600 text-white chat-bubble-me rounded-tr-none' : 'bg-white border border-gray-200 text-slate-700 chat-bubble-other rounded-tl-none' }}">
                            <div class="text-sm leading-relaxed whitespace-pre-line">{{ $chat->message }}</div>
                        </div>
                        @if($isCurrentUserMessage)
                            <div class="mt-1 px-2 flex items-center gap-1.5 text-[9px] font-black uppercase tracking-tighter {{ $chat->is_read ? 'text-emerald-500' : 'text-slate-300' }} read-status">
                                <i class="fas {{ $chat->is_read ? 'fa-check-double' : 'fa-check' }}"></i>
                                {{ $chat->is_read ? 'Terbaca' : 'Terkirim' }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input -->
        <div class="p-6 bg-white border-t border-gray-100">
            <form id="chat-form" class="flex gap-4">
                @csrf
                <div class="flex-1 relative group">
                    <textarea name="message" id="chat-message" required rows="1"
                              class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-sm focus:bg-white focus:ring-4 focus:ring-purple-100 focus:border-purple-300 transition-all resize-none shadow-inner"
                              placeholder="Ketik pesan konsultasi..."></textarea>
                    <div id="sendingText" class="absolute right-4 bottom-4 text-[10px] font-black text-purple-600 uppercase tracking-widest hidden animate-pulse">Mengirim...</div>
                </div>
                <button type="submit" id="chatSubmitBtn" 
                        class="w-14 h-14 bg-purple-600 text-white rounded-2xl shadow-lg shadow-purple-200 flex items-center justify-center hover:bg-purple-700 active:scale-95 transition-all flex-shrink-0 group">
                    <i class="fas fa-paper-plane text-xl transition group-hover:rotate-12"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('chat-form');
        const box = document.getElementById('chat-box');
        const messageInput = document.getElementById('chat-message');
        const chatPostUrl = '{{ $chatPostUrl }}';
        const sendingText = document.getElementById('sendingText');
        const chatSubmitBtn = document.getElementById('chatSubmitBtn');
        const onlineStatus = document.getElementById('onlineStatus');
        const onlinePing = document.getElementById('onlinePing');
        const onlineText = document.getElementById('onlineText');
        const messagesUrl = '{{ $chatMessagesUrl }}';
        const currentAuthId = {{ $currentUserId ?? 'null' }};
        const isStudentLayout = {{ json_encode($isStudentLayout) }};
        
        let lastMessageCount = 0;
        let isAutoScrollEnabled = true;
        let messageStates = {}; 
        let lastRecipientId = null; 
        let isPolling = false; 
        let lastPollTime = 0; 

        // Auto-expand textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Enter to send
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        function addMessageToChat(chat) {
            const isTeacher = chat.sender_type === 'teacher';
            const name = isTeacher ? (chat.sender?.name || 'Guru BK') : ('{{ $report->nama_murid }}' || 'Murid');
            const isCurrentUser = (isStudentLayout && chat.sender_type === 'student')
                || (!isStudentLayout && chat.sender_type === 'teacher' && chat.sender_id === currentAuthId);
            
            const timeStr = new Date(chat.created_at).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'});
            const readStatusHtml = isCurrentUser 
                ? `<div class="mt-1 px-2 flex items-center gap-1.5 text-[9px] font-black uppercase tracking-tighter ${chat.is_read ? 'text-emerald-500' : 'text-slate-300'} read-status" data-status-id="${chat.id}">
                    <i class="fas ${chat.is_read ? 'fa-check-double' : 'fa-check'}"></i>
                    ${chat.is_read ? 'Terbaca' : 'Terkirim'}
                   </div>`
                : '';
            
            const messageHtml = `
                <div class="flex ${isCurrentUser ? 'justify-end' : 'justify-start'}" data-message-id="${chat.id}">
                    <div class="flex flex-col ${isCurrentUser ? 'items-end' : 'items-start'}" style="max-width: 85%">
                        <div class="flex items-center gap-2 mb-1.5 px-2">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">${name}</span>
                            <span class="text-[10px] text-slate-300 font-medium tabular-nums">${timeStr}</span>
                        </div>
                        <div class="chat-bubble rounded-2xl px-5 py-3 shadow-sm ${isCurrentUser ? 'bg-purple-600 text-white chat-bubble-me rounded-tr-none' : 'bg-white border border-gray-200 text-slate-700 chat-bubble-other rounded-tl-none'}">
                            <div class="text-sm leading-relaxed whitespace-pre-line">${chat.message}</div>
                        </div>
                        ${readStatusHtml}
                    </div>
                </div>
            `;
            
            box.innerHTML += messageHtml;
            messageStates[chat.id] = { isRead: chat.is_read, isCurrentUser };
            
            if (!isCurrentUser && !chat.is_read) {
                markMessageAsRead(chat.id);
            }
        }

        function renderMessages(chats) {
            box.innerHTML = '';
            chats.forEach(addMessageToChat);
            if (isAutoScrollEnabled) {
                box.scrollTop = box.scrollHeight;
            }
        }

        async function markMessageAsRead(messageId) {
            try {
                const trackingCode = '{{ $report->tracking_code }}';
                const markReadUrl = isStudentLayout 
                    ? `/chat-murid/${trackingCode}/messages/${messageId}/mark-read`
                    : `/chat/${trackingCode}/messages/${messageId}/mark-read`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                await fetch(markReadUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json' }
                });
            } catch (error) {}
        }

        box?.addEventListener('scroll', function() {
            const scrolledUp = box.scrollTop < box.scrollHeight - box.clientHeight - 100;
            isAutoScrollEnabled = !scrolledUp;
        });

        form?.addEventListener('submit', async function (e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            sendingText.classList.remove('hidden');
            chatSubmitBtn.disabled = true;
            messageInput.disabled = true;

            try {
                const response = await fetch(chatPostUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ message })
                });

                if (response.ok) {
                    messageInput.value = '';
                    messageInput.style.height = 'auto';
                    await pollChat();
                }
            } finally {
                sendingText.classList.add('hidden');
                chatSubmitBtn.disabled = false;
                messageInput.disabled = false;
                messageInput.focus();
            }
        });

        async function pollChat() {
            if (isPolling) return;
            isPolling = true;
            
            try {
                // 1. Track Activity
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                fetch('/api/chat/active', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ 
                        report_id: {{ $report->id }},
                        is_student: {{ json_encode($isStudentLayout) }}
                    })
                }).catch(() => {});
                
                // 2. Poll Messages
                const response = await fetch(messagesUrl);
                const chats = await response.json();
                
                if (chats.length !== lastMessageCount) {
                    renderMessages(chats);
                    lastMessageCount = chats.length;
                }
                
                chats.forEach(chat => {
                    const oldState = messageStates[chat.id];
                    if (oldState && !oldState.isRead && chat.is_read && oldState.isCurrentUser) {
                        const marker = document.querySelector(`[data-status-id="${chat.id}"]`);
                        if (marker) {
                            marker.classList.remove('text-slate-300');
                            marker.classList.add('text-emerald-500');
                            marker.innerHTML = '<i class="fas fa-check-double"></i> Terbaca';
                        }
                        messageStates[chat.id].isRead = true;
                    }
                });
                
                // 3. Update Status Indicators (Both Sides)
                const presenceUrl = '{{ route("api.presence", ["tracking_code" => $report->tracking_code]) }}';
                const presenceResponse = await fetch(presenceUrl);
                const presenceData = await presenceResponse.json();
                
                const isRecipientOnline = isStudentLayout ? presenceData.teacher : presenceData.student;
                
                if (onlineStatus && onlinePing && onlineText) {
                    if (isRecipientOnline) {
                        onlineStatus.classList.replace('bg-purple-400/30', 'bg-emerald-500');
                        onlinePing.classList.replace('bg-slate-400', 'bg-emerald-400');
                        onlineText.textContent = 'Online';
                        onlineText.classList.replace('text-purple-300', 'text-emerald-500');
                    } else {
                        onlineStatus.classList.replace('bg-emerald-500', 'bg-purple-400/30');
                        onlinePing.classList.replace('bg-emerald-400', 'bg-slate-400');
                        onlineText.textContent = 'Offline';
                        onlineText.classList.replace('text-emerald-500', 'text-purple-300');
                    }
                }
            } finally {
                isPolling = false;
            }
        }

        setInterval(pollChat, 2500);
        document.addEventListener('visibilitychange', () => { if (!document.hidden) pollChat(); });
    })();
</script>

<style>
#chat-box::-webkit-scrollbar { width: 4px; }
#chat-box::-webkit-scrollbar-track { background: transparent; }
#chat-box::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
#chat-box::-webkit-scrollbar-thumb:hover { background: #CBD5E1; }

.bg-chat-pattern {
    background-color: #f0f2f5;
    background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
    background-size: 20px 20px;
}

.chat-bubble {
    position: relative;
    box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
}

.chat-bubble-me::before {
    content: "";
    position: absolute;
    top: 0;
    right: -8px;
    width: 0;
    height: 0;
    border: 8px solid transparent;
    border-top-color: #9333ea; /* purple-600 */
    border-left-color: #9333ea;
    border-bottom: 0;
    border-right: 0;
}

.chat-bubble-other::before {
    content: "";
    position: absolute;
    top: 0;
    left: -8px;
    width: 0;
    height: 0;
    border: 8px solid transparent;
    border-top-color: #fff;
    border-right-color: #fff;
    border-bottom: 0;
    border-left: 0;
}
</style>
@endsection