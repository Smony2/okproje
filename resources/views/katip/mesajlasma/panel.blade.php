
@extends('katip.layout.katip_master')

@section('title')
    <title>Mesaj Paneli | Katip</title>
@endsection

@section('cssler')
    <link rel="stylesheet" href="{{ asset('assets/css/chat12.css') }}">

@endsection

@section('main')
    <div class="row g-3">
        <!-- Sidebar (visible only on desktop) -->
        <div class="col-lg-3 col-md-5 d-none d-md-block">
            <div class="chat-sidebar card border-0 shadow-sm rounded-3 h-100">
                <div class="chat-sidebar-single active top-profile p-3">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-shrink-0 img">
                                @if(auth('katip')->user()->avatar)
                                    <img src="{{ asset(auth('katip')->user()->avatar->path) }}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('upload/no_image.jpg') }}" alt="Varsayılan Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="info">
                                <h6 class="mb-0 fw-semibold">{{ auth('katip')->user()->username }}</h6>
                                <p class="mb-0 small {{ auth('katip')->user()->is_active ? 'text-success' : 'text-muted' }}">
                                    {{ auth('katip')->user()->is_active ? 'Online' : 'Son Görülme: ' . (auth('katip')->user()->last_active_at ? \Carbon\Carbon::parse(auth('katip')->user()->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
                                </p>
                            </div>
                        </div>
                        <div class="action">
                            <div class="btn-group">
                                <button type="button" class="text-secondary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                    <iconify-icon icon="bi:three-dots"></iconify-icon>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-lg-end border">
                                    <li>
                                        <a href="{{ route('katip.profile.edit') }}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2">
                                            <iconify-icon icon="fluent:person-32-regular"></iconify-icon> Profil
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2">
                                            <iconify-icon icon="carbon:settings"></iconify-icon> Ayarlar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-1 pb-1">
                    <div class="chat-search">
                        <span class="icon">
                            <iconify-icon icon="iconoir:search"></iconify-icon>
                        </span>
                        <input type="text" name="#0" autocomplete="off" class="form-control ps-5 rounded-3" placeholder="Ara..." oninput="filterChats(this.value)">
                    </div>
                </div>
                <div class="chat-all-list" data-simplebar style="height: calc(100vh - 220px);">
                    @foreach($avukatlarWithConversations as $avukat)
                        <div class="chat-sidebar-single {{ $avukat->conversation ? ($loop->first ? 'active' : '') : 'new-chat' }}"
                             data-user-id="{{ $avukat->id }}"
                             data-user-type="Avukat"
                             onclick="{{ $avukat->conversation ? 'loadConversation(' . $avukat->conversation->id . ', \'' . route('katip.chat.show', $avukat->conversation->id) . '\', this)' : 'startNewConversation(' . $avukat->id . ')' }}">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-shrink-0 img">
                                        @if($avukat->avatar)
                                            <img src="{{ asset($avukat->avatar->path) }}" alt="{{ $avukat->username }} Avatar">
                                        @else
                                            <img src="{{ asset('upload/no_image.jpg') }}" alt="Varsayılan Avatar">
                                        @endif
                                    </div>
                                    <div class="info">
                                        <h6 class="text-sm mb-1">{{ $avukat->username }}</h6>
                                        <p class="mb-0 text-xs {{ $avukat->is_active ? 'text-success' : '' }}">
                                            {{ $avukat->is_active ? 'Online' : 'Son Görülme: ' . ($avukat->last_active_at ? \Carbon\Carbon::parse($avukat->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="action text-end">
                                    @if($avukat->conversation)
                                        <p class="mb-0 text-neutral-400 text-xs lh-1">
                                            {{ $avukat->conversation->updated_at ? \Carbon\Carbon::parse($avukat->conversation->updated_at)->format('H:i') : '' }}
                                        </p>

                                    @else
                                        <span class="badge bg-primary text-white">Yeni</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="col-lg-9 col-md-7">

            <div class="chat-main card border-0 shadow-sm rounded-3 h-100 d-flex flex-column">
                <div id="chat-area-content" class="flex-grow-1"></div>
            </div>
            <audio id="remoteAudio" autoplay playsinline></audio>
            <audio id="ringtoneAudio" src="/assets/sounds/ringtone.mp3" preload="auto" loop></audio>
            <div class="modal fade" id="incomingCallModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Gelen Çağrı</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                        </div>
                        <div class="modal-body">
                            <p>Bir çağrınız var. Kabul etmek ister misiniz?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="declineCallBtn" class="btn btn-outline-danger" data-bs-dismiss="modal" onclick="declineIncomingCall()">Reddet</button>
                            <button type="button" id="acceptCallBtn" class="btn btn-success">Kabul Et</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('jsler')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // GLOBAL DEĞIŞKENLER
        let currentChannel;
        let currentConversationId = null;
        let appPusher = null;
        let pusherSocketId = '';
        
        // LiveKit variables
        let lkRoom = null;
        let lkRoomName = null;
        let lkToken = null;
        let lkWsUrl = null;
        let localStream = null;

        // Güvenli HTML sanitization fonksiyonu
        function sanitizeHTML(html) {
            const div = document.createElement('div');
            div.innerHTML = html;
            // İzin verilen etiketler ve sınıflar
            const allowedTags = ['div', 'ul', 'li', 'strong', 'p', 'code', 'em'];
            const allowedClasses = ['system-notification', 'job-details'];
            const walk = (node) => {
                if (!node) return;
                if (node.nodeType === 1) { // Element node
                    const tagName = node.tagName.toLowerCase();
                    if (!allowedTags.includes(tagName)) {
                        node.parentNode.replaceChild(document.createTextNode(node.textContent), node);
                        return;
                    }
                    // Sadece izin verilen sınıfları koru
                    if (node.classList.length > 0) {
                        Array.from(node.classList).forEach(cls => {
                            if (!allowedClasses.includes(cls)) {
                                node.classList.remove(cls);
                            }
                        });
                    }
                    // Tüm öznitelikleri temizle (class hariç)
                    Array.from(node.attributes).forEach(attr => {
                        if (attr.name !== 'class') {
                            node.removeAttribute(attr.name);
                        }
                    });
                }
                Array.from(node.childNodes).forEach(walk);
            };
            walk(div);
            return div.innerHTML;
        }

        // Çevrimdışı durum bildirimi
        window.addEventListener('beforeunload', () => {
            fetch('/katip/offline', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            });
        });

        function relativeTime(dateStr) {
            if (!dateStr) return 'Bilinmiyor';
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return 'Bilinmiyor';
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 0) return 'Bilinmiyor';
            if (diffInSeconds < 60) return 'az önce';
            const minutes = Math.floor(diffInSeconds / 60);
            if (minutes < 60) return minutes + ' dakika önce';
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return hours + ' saat önce';
            const days = Math.floor(hours / 24);
            if (days < 30) return days + ' gün önce';
            const months = Math.floor(days / 30);
            if (months < 12) return months + ' ay önce';
            return Math.floor(months / 12) + ' yıl önce';
        }

        document.addEventListener('DOMContentLoaded', () => {
            appPusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", { cluster: "{{ env('PUSHER_APP_CLUSTER') }}" });
            appPusher.connection.bind('connected', () => { try { pusherSocketId = appPusher.connection.socket_id || ''; } catch {} });
            const statusChannel = appPusher.subscribe('user-status');
            statusChannel.bind('user-status-updated', function(data) {
                const statusElement = document.querySelector(`.chat-sidebar-single[data-user-id="${data.user_id}"][data-user-type="${data.user_type}"] .info p`);
                if (statusElement) {
                    statusElement.textContent = data.is_active ? 'Online' : `Son Görülme: ${relativeTime(data.last_active_at) || 'Bilinmiyor'}`;
                    statusElement.classList.toggle('text-success', data.is_active);
                    statusElement.classList.toggle('text-muted', !data.is_active);
                }
                const activeStatusElement = document.querySelector('#chat-area-content .info p');
                if (activeStatusElement && data.user_type === 'Avukat' && document.querySelector(`.chat-sidebar-single.active[data-user-id="${data.user_id}"]`)) {
                    activeStatusElement.textContent = data.is_active ? 'Online' : `Son Görülme: ${relativeTime(data.last_active_at) || 'Bilinmiyor'}`;
                    activeStatusElement.classList.toggle('text-success', data.is_active);
                    activeStatusElement.classList.toggle('text-muted', !data.is_active);
                }
            });

            // Kişisel kanal: sohbet açık olmasa bile arama çalsın
            const selfUserChannel = pusher.subscribe('user-Katip-{{ auth('katip')->user()->id }}');
            selfUserChannel.bind('call-invited', function (data) {
                console.log('Personal channel: Call invited event received:', data);
                lkRoomName = data.room;
                window.__incomingConversationId = data.conversation_id;
                startRingtone();
                renderCallCard('incoming');
                
                // Show incoming call modal
                try {
                    const modal = new bootstrap.Modal(document.getElementById('incomingCallModal'));
                    modal.show();
                    
                    // Setup accept button
                    const acceptBtn = document.getElementById('acceptCallBtn');
                    if (acceptBtn) {
                        acceptBtn.onclick = async () => {
                            modal.hide();
                            await acceptIncomingCall();
                        };
                    }
                } catch (e) {
                    console.error('Modal show failed:', e);
                }
                
                // Setup timeout
                try { if (window.__incomingTimeout) clearTimeout(window.__incomingTimeout); } catch {}
                window.__incomingTimeout = setTimeout(() => {
                    try { stopRingtone(); } catch {}
                    try { 
                        const modal = bootstrap.Modal.getInstance(document.getElementById('incomingCallModal'));
                        if (modal) modal.hide();
                    } catch {}
                    showNotification('Çağrı cevaplanmadı', 'danger');
                }, 30000);
                
                // Browser notification
                if (Notification && Notification.permission !== 'denied') {
                    if (Notification.permission === 'granted') {
                        new Notification('Gelen çağrı', { body: 'Bir çağrınız var.' });
                    } else {
                        Notification.requestPermission().then(p => { 
                            if (p === 'granted') new Notification('Gelen çağrı', { body: 'Bir çağrınız var.' }); 
                        });
                    }
                }
            });
            selfUserChannel.bind('call-accepted', function () {
                stopRingtone();
            });
            selfUserChannel.bind('call-ended', function () {
                stopRingtone();
                // Clear incoming call timeout
                try { 
                    if (window.__incomingTimeout) {
                        clearTimeout(window.__incomingTimeout);
                        window.__incomingTimeout = null;
                    }
                } catch {}
                
                // Clean up UI
                lkRoom = null; 
                lkRoomName = null; 
                lkToken = null;
                if (callActionIcon) callActionIcon.setAttribute('icon', 'mi:call');
                renderCallCard('ended');
            });

            const first = document.querySelector('.chat-all-list .chat-sidebar-single');
            if (first) first.click();
        });

        function setCurrentConversation(conversationId) {
            currentConversationId = conversationId;
        }

        function filterChats(query) {
            document.querySelectorAll('.chat-sidebar-single, .new-chat').forEach(el => {
                const name = el.querySelector('.info h6')?.textContent.toLowerCase();
                el.style.display = name?.includes(query.toLowerCase()) ? '' : 'none';
            });
        }

        function showNotification(message, type = 'success') {
            let notification = document.querySelector('.notification');
            if (!notification) {
                notification = document.createElement('div');
                notification.classList.add('notification');
                if (type === 'error' || type === 'danger') {
                    notification.classList.add('error');
                }
                document.body.appendChild(notification);
            }
            notification.textContent = message;
            notification.classList.add('visible');
            setTimeout(() => {
                notification.classList.remove('visible');
            }, 3000);
        }

        function loadConversation(id, url, clickedEl) {
            setCurrentConversation(id);

            document.querySelectorAll('.chat-sidebar-single').forEach(el => el.classList.remove('active'));
            clickedEl.classList.add('active');

            fetch(url, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Sohbet yüklenemedi: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    const chatArea = document.getElementById('chat-area-content');
                    chatArea.innerHTML = `
            <div class="chat-sidebar-single-ust p-1">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <button type="button" class="btn btn-link text-muted d-md-none me-2 p-0" data-bs-toggle="modal" data-bs-target="#chatModal">
                        <iconify-icon icon="ph:arrow-left" class="fs-5"></iconify-icon>
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <div class="flex-shrink-0 img">
                            <img src="${data.avukat_avatar || '{{ asset('upload/no_image.jpg') }}'}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <div class="info">
                            <h6 class="text-md mb-0">${data.avukat_name || 'Bilinmeyen Kullanıcı'}</h6>
                            <p class="mb-0 small ${data.avukat_is_active ? 'text-success' : 'text-muted'}">
                                ${data.avukat_is_active ? 'Online' : `Son Görülme: ${data.avukat_last_active_at ? relativeTime(data.avukat_last_active_at) : 'Bilinmiyor'}`}
                            </p>
                        </div>
                    </div>
                    <div class="action d-inline-flex align-items-center gap-3">
                        <button id="callActionBtn" type="button" class="text-xl text-primary-light" title="Ara">
                            <iconify-icon id="callActionIcon" icon="mi:call"></iconify-icon>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="text-primary-light text-xl" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                <iconify-icon icon="tabler:dots-vertical"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end border">
                                <li>
                                    <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2" type="button">
                                        <iconify-icon icon="mdi:clear-circle-outline"></iconify-icon> Tümünü Temizle
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2" type="button">
                                        <iconify-icon icon="ic:baseline-block"></iconify-icon> Engelle
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chat-message-list p-3 flex-grow-1" style="overflow-y: auto; height: calc(100vh - 180px);">
                ${data.messages.map(msg => {
                        // Handle call messages differently
                        if (msg.type && msg.type.startsWith('call_')) {
                            const metadata = msg.call_metadata || {};
                            const status = metadata.status || 'initiated';
                            const duration = metadata.duration || 0;
                            
                            const callIcon = status === 'answered' ? '📞' : 
                                           status === 'ended' ? '📞' : 
                                           status === 'missed' ? '📞❌' : '📞';
                            const callText = status === 'answered' ? 'Görüşme tamamlandı' :
                                           status === 'ended' ? 'Görüşme sonlandırıldı' :
                                           status === 'missed' ? 'Cevapsız arama' :
                                           'Arama başlatıldı';
                            
                            const durationText = duration > 0 ? ` · ${Math.floor(duration / 60)}:${String(duration % 60).padStart(2, '0')}` : '';
                            
                            return `
                            <div class="chat-single-message mb-3" data-message-id="${msg.id}" style="justify-content: center;">
                                <div class="chat-message-content" style="max-width: 300px; text-align: center;">
                                    <div class="mb-0 system-notification">
                                        ${callIcon} ${callText}${durationText}
                                    </div>
                                    <p class="chat-time mb-0">
                                        <span>${msg.created_at || 'Bilinmeyen zaman'}</span>
                                    </p>
                                </div>
                            </div>`;
                        }
                        
                        // Regular messages
                        const cls = msg.sender_type === 'Katip' ? 'right' : 'left';
                        const avatar = msg.sender_type === 'Katip' ? (data.katip_avatar || '{{ asset('upload/no_image.jpg') }}') : (data.avukat_avatar || '{{ asset('upload/no_image.jpg') }}');
                        const isHTMLMessage = msg.message && /<[a-z][\s\S]*>/i.test(msg.message);
                        const messageContent = isHTMLMessage ? sanitizeHTML(msg.message) : (msg.message ? msg.message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') : '');

                        return `
                        <div class="chat-single-message ${cls} mb-3" data-message-id="${msg.id}">
                            ${cls === 'left' ? `<img src="${avatar}" alt="Avatar" class="avatar-lg object-fit-cover rounded-circle">` : ''}
                            <div class="chat-message-content">
                                ${messageContent ? `<div class="mb-0 ${isHTMLMessage ? 'system-notification' : 'emoji'}">${messageContent}</div>` : ''}
                                ${msg.attachments && msg.attachments.length > 0 ? `
                                    <div class="attachment-list mt-2">
                                        ${msg.attachments.map(att => `
                                            <a href="${att.url}" target="_blank" style="color: ${cls === 'right' ? '#bfdbfe' : '#667eea'}; text-decoration: none;">
                                                <iconify-icon icon="ph:file"></iconify-icon>
                                                ${att.file_name} (${att.file_size} KB)
                                            </a>
                                        `).join('')}
                                    </div>
                                ` : ''}
                                <p class="chat-time mb-0">
                                    <span>${msg.created_at || 'Bilinmeyen zaman'}</span>
                                </p>
                            </div>
                            ${cls === 'right' ? `<img src="${avatar}" alt="Avatar" class="avatar-lg object-fit-cover rounded-circle">` : ''}
                        </div>`;
                    }).join('')}
            </div>
            <form class="chat-message-box p-3 border-top" id="chat-form" enctype="multipart/form-data">
                <div class="d-flex align-items-center w-100">
                    <input type="text" name="content" id="messageInput" class="form-control rounded-3 me-2" placeholder="Mesaj yaz..." style="flex: 1;">
                    <div class="chat-message-box-action d-flex align-items-center gap-2">
                        <input type="file" name="file" id="fileInput" style="display: none;" accept="image/*">
                        <button type="button" class="text-xl" id="fileUploadBtn" onclick="document.getElementById('fileInput').click();">
                            <iconify-icon icon="solar:gallery-linear"></iconify-icon>
                        </button>
                        <button type="button" class="text-xl emoji-btn" id="emojiBtn">
                            <iconify-icon icon="ph:smiley"></iconify-icon>
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary-600 radius-8 d-inline-flex align-items-center justify-content-center" id="sendButton">
                            <iconify-icon icon="f7:paperplane" style="font-size: 18px;"></iconify-icon>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="conversation_id" value="${data.conversation_id}">
                <div id="emojiPanel" class="emoji-panel">
                    <span class="emoji" data-emoji="😊">😊</span>
                    <span class="emoji" data-emoji="👍">👍</span>
                    <span class="emoji" data-emoji="😂">😂</span>
                    <span class="emoji" data-emoji="😍">😍</span>
                    <span class="emoji" data-emoji="😢">😢</span>
                    <span class="emoji" data-emoji="😎">😎</span>
                    <span class="emoji" data-emoji="😜">😜</span>
                    <span class="emoji" data-emoji="😘">😘</span>
                    <span class="emoji" data-emoji="😡">😡</span>
                    <span class="emoji" data-emoji="😴">😴</span>
                </div>
            </form>
        `;

                    const msgList = document.querySelector('.chat-message-list');
                    if (msgList) msgList.scrollTop = msgList.scrollHeight;

                    // Setup fonksiyonları
                    setupEmojiPanel();
                    setupPusherForConversation(data.conversation_id);
                    setupFileUpload();
                    setupMessageForm();
                    setupCallHeaderControls();
                })
                .catch(error => {
                    console.error('Sohbet yüklenemedi:', error);
                    showNotification('Sohbet yüklenemedi', 'danger');
                });
        }

        function setupCallHeaderControls() {
            const headerCallBtn = document.getElementById('callActionBtn');
            if (headerCallBtn) {
                headerCallBtn.onclick = () => { if (lkRoom) { endCall(); } else { startCall(); } };
            }
            const headerMuteBtn = document.getElementById('muteActionBtn');
            if (headerMuteBtn) {
                headerMuteBtn.onclick = () => { toggleMute(); };
            }
        }

        // Emoji Panel Setup
        function setupEmojiPanel() {
            const emojiBtn = document.getElementById('emojiBtn');
            const emojiPanel = document.getElementById('emojiPanel');
            const messageInput = document.getElementById('messageInput');

            if (emojiBtn && emojiPanel && messageInput) {
                emojiBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    emojiPanel.classList.toggle('show');
                    if (emojiPanel.classList.contains('show')) {
                        if (window.innerWidth < 768) {
                            emojiPanel.style.bottom = '80px';
                            emojiPanel.style.right = '0';
                            emojiPanel.style.width = '100%';
                        } else {
                            emojiPanel.style.bottom = '70px';
                            emojiPanel.style.right = '10px';
                            emojiPanel.style.width = '200px';
                        }
                    }
                });

                emojiPanel.querySelectorAll('.emoji').forEach(emoji => {
                    emoji.addEventListener('click', () => {
                        messageInput.value += emoji.getAttribute('data-emoji');
                        emojiPanel.classList.remove('show');
                        messageInput.focus();
                    });
                });

                document.addEventListener('click', (e) => {
                    if (!emojiPanel.contains(e.target) && !emojiBtn.contains(e.target)) {
                        emojiPanel.classList.remove('show');
                    }
                });

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && emojiPanel.classList.contains('show')) {
                        emojiPanel.classList.remove('show');
                        messageInput.focus();
                    }
                });
            }
        }

        // Pusher Setup - Sadeleştirilmiş
        function setupPusherForConversation(conversationId) {
            if (currentChannel) currentChannel.unsubscribe();
            const p = appPusher || new Pusher("{{ env('PUSHER_APP_KEY') }}", { cluster: "{{ env('PUSHER_APP_CLUSTER') }}" });
            currentChannel = p.subscribe('conversation-' + conversationId);

            // Yeni mesajları dinle
            currentChannel.bind('message-sent', function(newMessage) {
                console.log('📨 Yeni mesaj geldi:', newMessage);
                appendMessage(newMessage);
            });
        }

        // Dosya yükleme setup
        function setupFileUpload() {
            const fileInput = document.getElementById('fileInput');
            const fileUploadBtn = document.getElementById('fileUploadBtn');
            if (fileInput && fileUploadBtn) {
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        fileUploadBtn.innerHTML = '<iconify-icon icon="ph:check-circle" class="fs-5"></iconify-icon>';
                        fileUploadBtn.classList.add('text-success');
                        showNotification('Resim yüklendi', 'success');
                    } else {
                        fileUploadBtn.innerHTML = '<iconify-icon icon="solar:gallery-linear" class="fs-5"></iconify-icon>';
                        fileUploadBtn.classList.remove('text-success');
                    }
                });
            }
        }

        // Mesaj gönderme setup
        function setupMessageForm() {
            const chatForm = document.getElementById('chat-form');
            const sendButton = document.getElementById('sendButton');
            if (chatForm && sendButton) {
                chatForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    sendButton.disabled = true;
                    sendButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

                    const content = this.querySelector('input[name="content"]').value.trim();
                    const cid = this.querySelector('input[name="conversation_id"]').value;
                    const file = this.querySelector('input[name="file"]').files[0];
                    const fileUploadBtn = document.getElementById('fileUploadBtn');

                    if (!content && !file) {
                        showNotification('Mesaj veya resim gereklidir', 'danger');
                        sendButton.innerHTML = '<iconify-icon icon="f7:paperplane" style="font-size: 18px;"></iconify-icon>';
                        sendButton.disabled = false;
                        return;
                    }

                    const formData = new FormData();
                    formData.append('contenti', content);
                    formData.append('conversation_id', cid);
                    if (file) formData.append('file', file);

                    try {
                        const response = await fetch(`/katip/mesajlar/${cid}/mesaj-gonder`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.error || 'Mesaj gönderilemedi');
                        }

                        const result = await response.json();
                        this.querySelector('input[name="content"]').value = '';
                        this.querySelector('input[name="file"]').value = '';
                        fileUploadBtn.innerHTML = '<iconify-icon icon="solar:gallery-linear" class="fs-5"></iconify-icon>';
                        fileUploadBtn.classList.remove('text-success');
                        showNotification('Mesaj gönderildi', 'success');
                    } catch (error) {
                        console.error('Hata:', error.message);
                        showNotification(`Mesaj gönderilemedi: ${error.message}`, 'danger');
                    } finally {
                        sendButton.innerHTML = '<iconify-icon icon="f7:paperplane" style="font-size: 18px;"></iconify-icon>';
                        sendButton.disabled = false;
                    }
                });
            }
        }

        function startNewConversation(avukatId) {
            fetch('/katip/mesajlar/yeni-sohbet', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ avukat_id: avukatId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Yeni sohbet başlatılamadı: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        loadConversation(data.conversation_id, '{{ route('katip.chat.show', ':id') }}'.replace(':id', data.conversation_id), document.querySelector(`.chat-sidebar-single[data-user-id="${avukatId}"]`));
                    } else {
                        showNotification('Yeni sohbet başlatılamadı', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Hata:', error);
                    showNotification('Yeni sohbet başlatılamadı', 'danger');
                });
        }

        // appendMessage fonksiyonu - Sadeleştirilmiş
        function appendMessage(msg) {
            const list = document.querySelector('.chat-message-list');
            if (list) {
                // Handle call messages differently
                if (msg.type && msg.type.startsWith('call_')) {
                    const metadata = msg.call_metadata || {};
                    const status = metadata.status || 'initiated';
                    const duration = metadata.duration || 0;
                    
                    const callIcon = status === 'answered' ? '📞' : 
                                   status === 'ended' ? '📞' : 
                                   status === 'missed' ? '📞❌' : '📞';
                    const callText = status === 'answered' ? 'Görüşme tamamlandı' :
                                   status === 'ended' ? 'Görüşme sonlandırıldı' :
                                   status === 'missed' ? 'Cevapsız arama' :
                                   'Arama başlatıldı';
                    
                    const durationText = duration > 0 ? ` · ${Math.floor(duration / 60)}:${String(duration % 60).padStart(2, '0')}` : '';
                    
                    const messageHTML = `
                    <div class="chat-single-message mb-3" data-message-id="${msg.id}" style="justify-content: center;">
                        <div class="chat-message-content" style="max-width: 300px; text-align: center;">
                            <div class="mb-0 system-notification">
                                ${callIcon} ${callText}${durationText}
                            </div>
                            <p class="chat-time mb-0">
                                <span>${msg.created_at || 'Bilinmeyen zaman'}</span>
                            </p>
                        </div>
                    </div>`;

                    list.insertAdjacentHTML('beforeend', messageHTML);
                    list.scrollTop = list.scrollHeight;
                    return;
                }

                // Regular messages
                const cls = msg.sender_type === 'Katip' ? 'right' : 'left';
                const avatar = msg.sender_type === 'Katip' ? (msg.sender_avatar || '{{ asset('upload/no_image.jpg') }}') : (msg.sender_avatar || '{{ asset('upload/no_image.jpg') }}');
                const isHTMLMessage = msg.message && /<[a-z][\s\S]*>/i.test(msg.message);
                const messageContent = isHTMLMessage ? sanitizeHTML(msg.message) : (msg.message ? msg.message.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') : '');

                const messageHTML = `
                <div class="chat-single-message ${cls} mb-3" data-message-id="${msg.id}">
                    ${cls === 'left' ? `<img src="${avatar}" alt="Avatar" class="avatar-lg object-fit-cover rounded-circle">` : ''}
                    <div class="chat-message-content">
                        ${messageContent ? `<div class="mb-0 ${isHTMLMessage ? 'system-notification' : 'emoji'}">${messageContent}</div>` : ''}
                        ${msg.attachments && msg.attachments.length > 0 ? `
                            <div class="attachment-list">
                                ${msg.attachments.map(att => `
                                    <a href="${att.url}" target="_blank">
                                        <iconify-icon icon="ph:file"></iconify-icon>
                                        ${att.file_name} (${att.file_size} KB)
                                    </a>
                                `).join('')}
                            </div>
                        ` : ''}
                        <p class="chat-time mb-0">
                            <span>${msg.created_at || 'Bilinmeyen zaman'}</span>
                        </p>
                    </div>
                    ${cls === 'right' ? `<img src="${avatar}" alt="Avatar" class="avatar-lg object-fit-cover rounded-circle">` : ''}
                </div>
            `;

                list.insertAdjacentHTML('beforeend', messageHTML);
                list.scrollTop = list.scrollHeight;
            }
        }

        // --- LiveKit minimal helpers ---
        // Ringtone state
        let ringtoneCtx = null;
        let ringtoneOsc = null;
        let ringtoneGain = null;
        let ringtoneTimer = null;
        // Call UI state
        let callTimer = null;
        let callSeconds = 0;
        let isMuted = false;
        const callActionBtn = document.getElementById('callActionBtn');
        const callActionIcon = document.getElementById('callActionIcon');
        const remoteAudio = document.getElementById('remoteAudio');
        const ringtoneAudio = document.getElementById('ringtoneAudio');

        if (callActionBtn) callActionBtn.addEventListener('click', () => {
            if (lkRoom) {
                endCall();
            } else {
                startCall();
            }
        });

        // Ensure audio context can play after a user gesture
        document.addEventListener('click', () => {
            try { if (ringtoneCtx && ringtoneCtx.state === 'suspended') ringtoneCtx.resume(); } catch {}
        });

        function startRingtone() {
            if (ringtoneTimer) return; // already ringing
            try {
                if (ringtoneAudio) {
                    ringtoneAudio.currentTime = 0;
                    const p = ringtoneAudio.play();
                    if (p && typeof p.catch === 'function') p.catch(() => {});
                }
                if (!ringtoneCtx) {
                    const AC = window.AudioContext || window.webkitAudioContext;
                    if (!AC) return; // no WebAudio support
                    ringtoneCtx = new AC();
                }
                if (ringtoneCtx.state === 'suspended') ringtoneCtx.resume();

                const stopCurrentTone = () => {
                    try { if (ringtoneOsc) { ringtoneOsc.stop(); } } catch {}
                    try { if (ringtoneOsc) ringtoneOsc.disconnect(); } catch {}
                    try { if (ringtoneGain) ringtoneGain.disconnect(); } catch {}
                    ringtoneOsc = null; ringtoneGain = null;
                };

                const playBeep = () => {
                    stopCurrentTone();
                    ringtoneOsc = ringtoneCtx.createOscillator();
                    ringtoneGain = ringtoneCtx.createGain();
                    ringtoneOsc.type = 'sine';
                    ringtoneOsc.frequency.value = 800;
                    ringtoneGain.gain.value = 0.06;
                    ringtoneOsc.connect(ringtoneGain).connect(ringtoneCtx.destination);
                    ringtoneOsc.start();
                    setTimeout(stopCurrentTone, 700);
                };

                ringtoneTimer = setInterval(playBeep, 1400);
                playBeep();
            } catch {}
        }

        function stopRingtone() {
            try { if (ringtoneTimer) { clearInterval(ringtoneTimer); } } catch {}
            ringtoneTimer = null;
            try { if (ringtoneOsc) { ringtoneOsc.stop(); } } catch {}
            try { if (ringtoneOsc) ringtoneOsc.disconnect(); } catch {}
            try { if (ringtoneGain) ringtoneGain.disconnect(); } catch {}
            ringtoneOsc = null; ringtoneGain = null;
            try { if (ringtoneAudio) { ringtoneAudio.pause(); ringtoneAudio.currentTime = 0; } } catch {}
        }

        function startCallTimer() {
            try { if (callTimer) clearInterval(callTimer); } catch {}
            callSeconds = 0;
            callTimer = setInterval(() => {
                callSeconds += 1;
                const mm = String(Math.floor(callSeconds / 60)).padStart(2, '0');
                const ss = String(callSeconds % 60).padStart(2, '0');
                try { const c = document.getElementById('callCardTimer'); if (c) c.textContent = mm + ':' + ss; } catch {}
            }, 1000);
        }

        function stopCallTimer() {
            try { if (callTimer) clearInterval(callTimer); } catch {}
            callTimer = null;
        }

        function ensureCallCardContainer() {
            const list = document.querySelector('.chat-message-list');
            return list;
        }

        function renderCallCard(state) {
            const list = ensureCallCardContainer();
            if (!list) return;
            const existing = document.getElementById('callCard');
            let html = '';
            if (state === 'incoming') {
                html = `
                <div id="callCard" class="chat-single-message mb-3" style="justify-content: center;">
                    <div class="chat-message-content" style="max-width: 400px; text-align: center; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333; border-radius: 15px; padding: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                            <div class="incoming-dot" style="width: 10px; height: 10px; background: #00d4aa; border-radius: 50%; animation: incoming 2s infinite;"></div>
                            <span style="font-weight: 600; font-size: 16px;">Gelen Çağrı</span>
                        </div>
                        <div class="d-flex gap-3 justify-content-center">
                            <button class="btn btn-sm" onclick="acceptIncomingCall()" style="border-radius: 25px; padding: 10px 20px; min-width: 80px; background: #00d4aa; border: none; color: white; font-weight: 600;">
                                <iconify-icon icon="solar:phone-linear" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                                Kabul Et
                            </button>
                            <button class="btn btn-sm" onclick="endCall()" style="border-radius: 25px; padding: 10px 20px; min-width: 80px; background: #ff4757; border: none; color: white; font-weight: 600;">
                                <iconify-icon icon="solar:phone-end-linear" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                                Reddet
                            </button>
                        </div>
                    </div>
                </div>
                <style>
                    @keyframes incoming {
                        0% { opacity: 1; transform: scale(1); }
                        50% { opacity: 0.7; transform: scale(1.3); }
                        100% { opacity: 1; transform: scale(1); }
                    }
                </style>`;
            } else if (state === 'outgoing') {
                html = `
                <div id="callCard" class="chat-single-message mb-3" style="justify-content: center;">
                    <div class="chat-message-content" style="max-width: 350px; text-align: center; background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%); color: #333; border-radius: 15px; padding: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <div class="ringing-dot" style="width: 8px; height: 8px; background: #ff6b6b; border-radius: 50%; animation: ring 1.5s infinite;"></div>
                            <span style="font-weight: 600; font-size: 14px;">Aranıyor...</span>
                        </div>
                        <div class="d-flex gap-3 justify-content-center">
                            <button class="btn btn-sm btn-danger" onclick="endCall()" style="border-radius: 25px; padding: 8px 15px; min-width: 45px; background: #ff4757; border: none;">
                                <iconify-icon icon="solar:phone-end-linear" style="font-size: 16px;"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
                <style>
                    @keyframes ring {
                        0% { opacity: 1; transform: scale(1); }
                        50% { opacity: 0.3; transform: scale(1.5); }
                        100% { opacity: 1; transform: scale(1); }
                    }
                </style>`;
            } else if (state === 'connected') {
                html = `
                <div id="callCard" class="chat-single-message mb-3" style="justify-content: center;">
                    <div class="chat-message-content" style="max-width: 400px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <div class="pulse-dot" style="width: 8px; height: 8px; background: #ff4757; border-radius: 50%; animation: pulse 1.5s infinite;"></div>
                            <span style="font-weight: 600; font-size: 14px;">Görüşme Aktif</span>
                            <span id="callCardTimer" style="font-weight: 600; font-size: 14px;">00:00</span>
                        </div>
                        <div class="d-flex gap-3 justify-content-center">
                            <button class="btn btn-sm" onclick="toggleMute()" style="background: rgba(255,255,255,0.2); border: none; color: white; border-radius: 25px; padding: 8px 15px; min-width: 45px;">
                                <iconify-icon icon="mdi:microphone" style="font-size: 16px;"></iconify-icon>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="endCall()" style="border-radius: 25px; padding: 8px 15px; min-width: 45px; background: #ff4757; border: none;">
                                <iconify-icon icon="solar:phone-end-linear" style="font-size: 16px;"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
                <style>
                    @keyframes pulse {
                        0% { opacity: 1; transform: scale(1); }
                        50% { opacity: 0.5; transform: scale(1.2); }
                        100% { opacity: 1; transform: scale(1); }
                    }
                </style>`;
                // Start timer when connected
                startCallTimer();
            } else if (state === 'ended') {
                const mm = String(Math.floor((callSeconds||0) / 60)).padStart(2,'0');
                const ss = String((callSeconds||0) % 60).padStart(2,'0');
                html = `
                <div id="callCard" class="chat-single-message mb-3" style="justify-content: center;">
                    <div class="chat-message-content" style="max-width: 350px; text-align: center; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border-radius: 15px; padding: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <div class="ended-dot" style="width: 8px; height: 8px; background: #ffd700; border-radius: 50%; animation: ended 3s infinite;"></div>
                            <span style="font-weight: 600; font-size: 14px;">Görüşme Bitti</span>
                            <span style="font-weight: 600; font-size: 14px;">· ${mm}:${ss}</span>
                        </div>
                    </div>
                </div>
                <style>
                    @keyframes ended {
                        0% { opacity: 1; transform: scale(1); }
                        50% { opacity: 0.5; transform: scale(0.8); }
                        100% { opacity: 1; transform: scale(1); }
                    }
                </style>`;
                // Stop timer when ended
                stopCallTimer();
            }
            if (existing) existing.remove();
            list.insertAdjacentHTML('beforeend', html);
            list.scrollTop = list.scrollHeight;
        }

        async function populateOutputDevices() {
            // Output device selection removed - functionality moved to call card
        }

        async function changeOutputDevice(deviceId) {
            // Output device functionality removed
        }

        async function toggleMute() {
            try {
                isMuted = !isMuted;
                if (lkRoom && lkRoom.localParticipant) {
                    await lkRoom.localParticipant.setMicrophoneEnabled(!isMuted);
                    console.log('Microphone toggled:', !isMuted);
                }
                // Update mute button icon in call card
                const muteBtn = document.querySelector('#callCard button[onclick="toggleMute()"] iconify-icon');
                if (muteBtn) muteBtn.setAttribute('icon', isMuted ? 'mdi:microphone-off' : 'mdi:microphone');
                
                // Show notification
                showNotification(isMuted ? 'Mikrofon kapatıldı' : 'Mikrofon açıldı', 'info');
            } catch (e) {
                console.error('Toggle mute failed:', e);
                showNotification('Mikrofon durumu değiştirilemedi', 'danger');
            }
        }

        async function fetchToken(room) {
            const res = await fetch(`/katip/mesajlar/${currentConversationId}/call/token`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'X-Socket-Id': pusherSocketId || ''
                },
                body: JSON.stringify({ room })
            });
            if (!res.ok) throw new Error('Token alınamadı');
            const data = await res.json();
            try { window.__lastIceServers = data.iceServers || []; } catch {}
            return data;
        }

        async function startCall() {
            try {
                if (!currentConversationId) return;
                renderCallCard('outgoing');
                const inviteRes = await fetch(`/katip/mesajlar/${currentConversationId}/call/invite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'X-Socket-Id': pusherSocketId || ''
                    },
                    body: JSON.stringify({})
                });
                const inviteData = await inviteRes.json();
                lkRoomName = inviteData.room;

                const tokenData = await fetchToken(lkRoomName);
                lkToken = tokenData.token;
                lkWsUrl = tokenData.ws_url;

                // Don't join room immediately - wait for the other party to accept
                // The call will be connected when the other party accepts
                console.log('Call invited, waiting for acceptance...');
            } catch (e) {
                console.error(e);
            }
        }

        async function acceptIncomingCall() {
            try {
                if (!lkRoomName) return;
                if (!currentConversationId && window.__incomingConversationId) {
                    currentConversationId = window.__incomingConversationId;
                }
                if (!currentConversationId) return;
                
                // Clear the timeout to prevent "call not answered" message
                try { 
                    if (window.__incomingTimeout) {
                        clearTimeout(window.__incomingTimeout);
                        window.__incomingTimeout = null;
                    }
                } catch {}
                
                // Close the modal
                try {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('incomingCallModal'));
                    if (modal) {
                        modal.hide();
                    }
                    // Remove backdrop manually
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                    // Remove modal-open class from body
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                } catch (e) {
                    console.warn('Failed to close modal:', e);
                }
                
                stopRingtone();
                await fetch(`/katip/mesajlar/${currentConversationId}/call/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'X-Socket-Id': pusherSocketId || ''
                    },
                    body: JSON.stringify({ room: lkRoomName })
                });
                const tokenData = await fetchToken(lkRoomName);
                lkToken = tokenData.token;
                lkWsUrl = tokenData.ws_url;
                await joinLiveKitRoom();
                if (callActionIcon) callActionIcon.setAttribute('icon', 'solar:phone-end-linear');
                renderCallCard('connected');
            } catch (e) {
                console.error(e);
            }
        }

        function declineIncomingCall() {
            try {
                // Clear the timeout
                if (window.__incomingTimeout) {
                    clearTimeout(window.__incomingTimeout);
                    window.__incomingTimeout = null;
                }
                
                stopRingtone();
                renderCallCard('ended');
                
                // Reset variables
                lkRoomName = null;
                lkToken = null;
                lkWsUrl = null;
                
                // Remove backdrop manually
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                // Remove modal-open class from body
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            } catch (e) {
                console.warn('Error declining call:', e);
            }
        }

        let isEndingCall = false; // Prevent multiple end requests
        
        async function endCall() {
            console.log('endCall function called');
            if (isEndingCall) {
                console.log('Already ending call, returning');
                return; // Already ending call
            }
            isEndingCall = true;
            
            try {
                if (currentConversationId && lkRoomName) {
                    console.log('Ending call for room:', lkRoomName);
                    await fetch(`/katip/mesajlar/${currentConversationId}/call/end`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'X-Socket-Id': pusherSocketId || ''
                        },
                        body: JSON.stringify({ room: lkRoomName })
                    });
                    console.log('End call request sent successfully');
                }
            } catch (e) {
                console.warn('End call request failed:', e);
            }
            
            stopRingtone();
            
            // Clear incoming call timeout
            try { 
                if (window.__incomingTimeout) {
                    clearTimeout(window.__incomingTimeout);
                    window.__incomingTimeout = null;
                }
            } catch {}
            
            // Disconnect LiveKit room
            try { 
                if (lkRoom && typeof lkRoom.disconnect === 'function') {
                    await lkRoom.disconnect();
                    console.log('LiveKit room disconnected');
                }
            } catch (e) {
                console.warn('Failed to disconnect LiveKit room:', e);
            }
            
            // Stop local stream
            try { 
                if (localStream && localStream.getTracks) {
                    localStream.getTracks().forEach(t => t.stop());
                    console.log('Local stream tracks stopped');
                }
            } catch (e) {
                console.warn('Failed to stop local stream:', e);
            }
            
            // Reset variables
            lkRoom = null; 
            lkRoomName = null; 
            lkToken = null;
            lkWsUrl = null;
            
            // Update UI
            if (callActionIcon) callActionIcon.setAttribute('icon', 'mi:call');
            renderCallCard('ended');
            
            console.log('Call ended successfully');
            
            // Reset flag after a delay
            setTimeout(() => {
                isEndingCall = false;
            }, 1000);
        }

        async function ensureLiveKit() {
            if (window.LivekitClient) return window.LivekitClient;
            await new Promise((resolve, reject) => {
                const existing = document.querySelector('script[data-livekit="1"]');
                if (existing) { existing.addEventListener('load', resolve, { once: true }); return; }
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/livekit-client/dist/livekit-client.umd.min.js';
                s.async = true;
                s.defer = true;
                s.dataset.livekit = '1';
                s.onload = resolve;
                s.onerror = reject;
                document.head.appendChild(s);
            });
            if (!window.LivekitClient) throw new Error('LiveKit client could not be loaded');
            return window.LivekitClient;
        }

        async function joinLiveKitRoom() {
            try {
                console.log('Starting LiveKit room connection...');
                const LK = await ensureLiveKit();
                const RoomCtor = LK.Room;
                const RoomEvent = LK.RoomEvent;
                if (!RoomCtor || !RoomEvent) throw new Error('LiveKit Room not available');

                console.log('Creating LiveKit room...');
                lkRoom = new RoomCtor({ adaptiveStream: true, dynacast: true });
                let connectOpts = {};
                try {
                    if (typeof window.__lastIceServers === 'object') {
                        connectOpts = { rtcConfig: { iceServers: window.__lastIceServers } };
                        console.log('Using ICE servers:', window.__lastIceServers);
                    }
                } catch {}
                
                // Ensure microphone permissions before connecting
                console.log('Requesting microphone permissions...');
                try {
                    localStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    console.log('Microphone permission granted');
                } catch (e) {
                    console.warn('Microphone permission denied:', e);
                    showNotification('Mikrofon izni gerekli', 'danger');
                    return;
                }
                
                console.log('Connecting to LiveKit room:', lkWsUrl);
                await lkRoom.connect(lkWsUrl, lkToken, connectOpts);
                console.log('Successfully connected to LiveKit room');
                
                // Enable microphone with retry
                try {
                    await lkRoom.localParticipant.setMicrophoneEnabled(true);
                    console.log('Microphone enabled successfully');
                } catch (e) {
                    console.warn('Failed to enable microphone:', e);
                    // Retry once
                    try {
                        await lkRoom.localParticipant.setMicrophoneEnabled(true);
                        console.log('Microphone enabled on retry');
                    } catch (e2) {
                        console.error('Microphone enable failed after retry:', e2);
                    }
                }

                remoteAudio.muted = false;
                remoteAudio.autoplay = true;
                remoteAudio.playsInline = true;
                remoteAudio.volume = 1.0;

                lkRoom.on(RoomEvent.TrackSubscribed, (...args) => {
                    console.log('TrackSubscribed event received:', args);
                    try {
                        let audioTrack = null;
                        for (const a of args) {
                            if (a && typeof a.attach === 'function' && (a.kind === 'audio' || a.mediaStreamTrack?.kind === 'audio')) {
                                audioTrack = a; break;
                            }
                            if (a && a.track && typeof a.track.attach === 'function' && (a.track.kind === 'audio' || a.track.mediaStreamTrack?.kind === 'audio')) {
                                audioTrack = a.track; break;
                            }
                        }
                        if (audioTrack) {
                            console.log('Audio track attached:', audioTrack);
                            audioTrack.attach(remoteAudio);
                            const p = remoteAudio.play();
                            if (p && typeof p.catch === 'function') p.catch((e) => {
                                console.warn('Audio play failed:', e);
                            });
                        } else {
                            console.warn('No audio track found in TrackSubscribed event');
                        }
                    } catch (e) { console.warn('TrackSubscribed error:', e); }
                });
                
                lkRoom.on(RoomEvent.TrackUnsubscribed, () => {
                    console.log('TrackUnsubscribed event received');
                    try { remoteAudio.srcObject = null; remoteAudio.removeAttribute('src'); } catch {}
                });
                
                lkRoom.on(RoomEvent.Disconnected, () => {
                    console.log('LiveKit room disconnected');
                    // Don't call endCall() here to avoid duplicate requests
                    // Just clean up the UI
                    lkRoom = null; 
                    lkRoomName = null; 
                    lkToken = null;
                    if (callActionIcon) callActionIcon.setAttribute('icon', 'mi:call');
                    renderCallCard('ended');
                });
                
                // Log participant info
                console.log('Local participant:', lkRoom.localParticipant);
                console.log('Remote participants:', lkRoom.participants);
                
                if (callActionIcon) callActionIcon.setAttribute('icon', 'solar:phone-end-linear');
                
                console.log('LiveKit room setup completed successfully');
            } catch (e) {
                console.error('Failed to join LiveKit room:', e);
                throw e;
            }
        }

        function bindCallEvents(channel) {
            channel.bind('call-invited', function (data) {
                console.log('Call invited event received:', data);
                lkRoomName = data.room;
                startRingtone();
                renderCallCard('incoming');
                
                // Show incoming call modal
                try {
                    const modal = new bootstrap.Modal(document.getElementById('incomingCallModal'));
                    modal.show();
                    
                    // Setup accept button
                    const acceptBtn = document.getElementById('acceptCallBtn');
                    if (acceptBtn) {
                        acceptBtn.onclick = async () => {
                            modal.hide();
                            await acceptIncomingCall();
                        };
                    }
                } catch (e) {
                    console.error('Modal show failed:', e);
                }
                
                // Setup timeout
                try { if (window.__incomingTimeout) clearTimeout(window.__incomingTimeout); } catch {}
                window.__incomingTimeout = setTimeout(() => {
                    try { stopRingtone(); } catch {}
                    try { 
                        const modal = bootstrap.Modal.getInstance(document.getElementById('incomingCallModal'));
                        if (modal) modal.hide();
                    } catch {}
                    showNotification('Çağrı cevaplanmadı', 'danger');
                }, 30000);
                
                // Browser notification
                if (Notification && Notification.permission !== 'denied') {
                    if (Notification.permission === 'granted') {
                        new Notification('Gelen çağrı', { body: 'Bir çağrınız var.' });
                    } else {
                        Notification.requestPermission().then(p => { 
                            if (p === 'granted') new Notification('Gelen çağrı', { body: 'Bir çağrınız var.' }); 
                        });
                    }
                }
            });
            channel.bind('call-accepted', function () {
                console.log('Call accepted, joining room...');
                // Join the room when the other party accepts
                joinLiveKitRoom().then(() => {
                    console.log('Successfully joined LiveKit room');
                    if (callActionIcon) callActionIcon.setAttribute('icon', 'solar:phone-end-linear');
                    renderCallCard('connected');
                }).catch(e => {
                    console.error('Failed to join room:', e);
                    showNotification('Bağlantı kurulamadı', 'danger');
                });
            });
            channel.bind('call-ended', function () {
                stopRingtone();
                
                // Clear incoming call timeout
                try { 
                    if (window.__incomingTimeout) {
                        clearTimeout(window.__incomingTimeout);
                        window.__incomingTimeout = null;
                    }
                } catch {}
                
                // Don't call endCall() here to avoid duplicate requests
                // Just clean up the UI
                lkRoom = null; 
                lkRoomName = null; 
                lkToken = null;
                if (callActionIcon) callActionIcon.setAttribute('icon', 'mi:call');
                renderCallCard('ended');
            });
        }

        const originalSetupPusherForConversation = setupPusherForConversation;
        setupPusherForConversation = function (conversationId) {
            const result = originalSetupPusherForConversation(conversationId);
            if (typeof currentChannel !== 'undefined' && currentChannel) {
                bindCallEvents(currentChannel);
            }
            return result;
        }
    </script>
@endsection