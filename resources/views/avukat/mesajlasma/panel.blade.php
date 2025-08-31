@extends('avukat.layout.avukat_master')

@section('title')
    <title>Mesaj Paneli | Avukat</title>
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
                                @if(auth('avukat')->user()->avatar)
                                    <img src="{{ asset(auth('avukat')->user()->avatar->path) }}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('upload/no_image.jpg') }}" alt="Varsayılan Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                @endif
                            </div>
                            <div class="info">
                                <h6 class="mb-0 fw-semibold">{{ auth('avukat')->user()->username }}</h6>
                                <p class="mb-0 small {{ auth('avukat')->user()->is_active ? 'text-success' : 'text-muted' }}">
                                    {{ auth('avukat')->user()->is_active ? 'Online' : 'Son Görülme: ' . (auth('avukat')->user()->last_active_at ? \Carbon\Carbon::parse(auth('avukat')->user()->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
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
                                        <a href="{{ route('avukat.profile.edit') }}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2">
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
                    @foreach($katiplerWithConversations as $katip)
                        <div class="chat-sidebar-single {{ $katip->conversation ? ($loop->first ? 'active' : '') : 'new-chat' }}"
                             data-user-id="{{ $katip->id }}"
                             data-user-type="Katip"
                             onclick="{{ $katip->conversation ? 'loadConversation(' . $katip->conversation->id . ', \'' . route('avukat.chat.show', $katip->conversation->id) . '\', this)' : 'startNewConversation(' . $katip->id . ')' }}">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-shrink-0 img">
                                        @if($katip->avatar)
                                            <img src="{{ asset($katip->avatar->path) }}" alt="{{ $katip->username }} Avatar">
                                        @else
                                            <img src="{{ asset('upload/no_image.jpg') }}" alt="Varsayılan Avatar">
                                        @endif
                                    </div>
                                    <div class="info">
                                        <h6 class="text-sm mb-1">{{ $katip->username }}</h6>
                                        <p class="mb-0 text-xs {{ $katip->is_active ? 'text-success' : '' }}">
                                            {{ $katip->is_active ? 'Online' : 'Son Görülme: ' . ($katip->last_active_at ? \Carbon\Carbon::parse($katip->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="action text-end">
                                    @if(!$katip->conversation)
                                        <span class="badge bg-primary text-white">Yeni</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mobile Sidebar Modal -->
        <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content rounded-3 border-0 shadow-sm">
                    <div class="modal-header border-bottom">
                        <h5 class="modal-title fw-semibold" id="chatModalLabel">Sohbetler</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="chat-sidebar-single active top-profile p-3">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="flex-shrink-0 img">
                                        @if(auth('avukat')->user()->avatar)
                                            <img src="{{ asset(auth('avukat')->user()->avatar->path) }}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('upload/no_image.jpg') }}" alt="Varsayılan Avatar" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="info">
                                        <h6 class="mb-0 fw-semibold">{{ auth('avukat')->user()->username }}</h6>
                                        <p class="mb-0 small {{ auth('avukat')->user()->is_active ? 'text-success' : 'text-muted' }}">
                                            {{ auth('avukat')->user()->is_active ? 'Online' : 'Son Görülme: ' . (auth('avukat')->user()->last_active_at ? \Carbon\Carbon::parse(auth('avukat')->user()->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-search">
                            <span class="icon">
                                <iconify-icon icon="iconoir:search"></iconify-icon>
                            </span>
                            <input type="text" name="#0" autocomplete="off" class="form-control ps-5 rounded-3" placeholder="Ara..." oninput="filterChats(this.value)">
                        </div>
                        <div class="chat-all-list" data-simplebar style="height: calc(100vh - 220px);">
                            @foreach($katiplerWithConversations as $katip)
                                <div class="chat-sidebar-single {{ $katip->conversation ? ($loop->first ? 'active' : '') : 'new-chat' }}"
                                     data-user-id="{{ $katip->id }}"
                                     data-user-type="Katip"
                                     onclick="{{ $katip->conversation ? 'loadConversation(' . $katip->conversation->id . ', \'' . route('avukat.chat.show', $katip->conversation->id) . '\', this)' : 'startNewConversation(' . $katip->id . ')' }}; if(window.innerWidth < 768) { bootstrap.Modal.getInstance(document.getElementById('chatModal')).hide(); }">
                                    <div class="d-flex justify-content-between align-items-center w-100">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="flex-shrink-0 img">
                                                @if($katip->avatar)
                                                    <img src="{{ asset($katip->avatar->path) }}" alt="{{ $katip->username }} Avatar">
                                                @else
                                                    <img src="{{ asset('upload/no_image.jpg') }}" alt="Varsayılan Avatar">
                                                @endif
                                            </div>
                                            <div class="info">
                                                <h6 class="text-sm mb-1">{{ $katip->username }}</h6>
                                                <p class="mb-0 text-xs {{ $katip->is_active ? 'text-success' : '' }}">
                                                    {{ $katip->is_active ? 'Online' : 'Son Görülme: ' . ($katip->last_active_at ? \Carbon\Carbon::parse($katip->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="action text-end">
                                            @if(!$katip->conversation)
                                                <span class="badge bg-primary text-white">Yeni</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="col-lg-9 col-md-7">
            <div class="chat-main card border-0 shadow-sm rounded-3 h-100 d-flex flex-column">
                <div id="chat-area-content" class="flex-grow-1"></div>
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
            fetch('/avukat/offline', {
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
            const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", { cluster: "{{ env('PUSHER_APP_CLUSTER') }}" });
            const statusChannel = pusher.subscribe('user-status');
            statusChannel.bind('user-status-updated', function(data) {
                const statusElement = document.querySelector(`.chat-sidebar-single[data-user-id="${data.user_id}"][data-user-type="${data.user_type}"] .info p`);
                if (statusElement) {
                    statusElement.textContent = data.is_active ? 'Online' : `Son Görülme: ${relativeTime(data.last_active_at) || 'Bilinmiyor'}`;
                    statusElement.classList.toggle('text-success', data.is_active);
                    statusElement.classList.toggle('text-muted', !data.is_active);
                }
                const activeStatusElement = document.querySelector('#chat-area-content .info p');
                if (activeStatusElement && data.user_type === 'Katip' && document.querySelector(`.chat-sidebar-single.active[data-user-id="${data.user_id}"]`)) {
                    activeStatusElement.textContent = data.is_active ? 'Online' : `Son Görülme: ${relativeTime(data.last_active_at) || 'Bilinmiyor'}`;
                    activeStatusElement.classList.toggle('text-success', data.is_active);
                    activeStatusElement.classList.toggle('text-muted', !data.is_active);
                }
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
                            <img src="${data.katip_avatar || '{{ asset('upload/no_image.jpg') }}'}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                        </div>
                        <div class="info">
                            <h6 class="text-md mb-0">${data.katip_name || 'Bilinmeyen Kullanıcı'}</h6>
                            <p class="mb-0 small ${data.katip_is_active ? 'text-success' : 'text-muted'}">
                                ${data.katip_is_active ? 'Online' : `Son Görülme: ${data.katip_last_active_at ? relativeTime(data.katip_last_active_at) : 'Bilinmiyor'}`}
                            </p>
                        </div>
                    </div>
                    <div class="action d-inline-flex align-items-center gap-3">
                        <button type="button" class="text-xl text-primary-light">
                            <iconify-icon icon="mi:call"></iconify-icon>
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
                        const cls = msg.sender_type === 'Avukat' ? 'right' : 'left';
                        const avatar = msg.sender_type === 'Avukat' ? (data.avukat_avatar || '{{ asset('upload/no_image.jpg') }}') : (data.katip_avatar || '{{ asset('upload/no_image.jpg') }}');
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
                })
                .catch(error => {
                    console.error('Sohbet yüklenemedi:', error);
                    showNotification('Sohbet yüklenemedi', 'danger');
                });
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
            const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", { cluster: "{{ env('PUSHER_APP_CLUSTER') }}" });
            currentChannel = pusher.subscribe('conversation-' + conversationId);

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
                        const response = await fetch(`/avukat/mesajlar/${cid}/mesaj-gonder`, {
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

        function startNewConversation(katipId) {
            fetch('/avukat/mesajlar/yeni-sohbet', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ katip_id: katipId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Yeni sohbet başlatılamadı: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        loadConversation(data.conversation_id, '{{ route('avukat.chat.show', ':id') }}'.replace(':id', data.conversation_id), document.querySelector(`.chat-sidebar-single[data-user-id="${katipId}"]`));
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
                const cls = msg.sender_type === 'Avukat' ? 'right' : 'left';
                const avatar = msg.sender_type === 'Avukat' ? (msg.sender_avatar || '{{ asset('upload/no_image.jpg') }}') : (msg.sender_avatar || '{{ asset('upload/no_image.jpg') }}');
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
    </script>
@endsection