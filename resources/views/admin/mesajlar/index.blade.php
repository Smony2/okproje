@extends('admin.yonetim_master')

@section('title')
    <title>Özel Mesajlar | Admin Paneli</title>
@endsection

@section('cssler')
    <style>
        .chat-container {
            height: 70vh;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .conversation-list {
            height: 100%;
            overflow-y: auto;
            background: #f8f9fa;
        }
        
        .conversation-item {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .conversation-item:hover {
            background-color: #e9ecef;
        }
        
        .conversation-item.active {
            background-color: #007bff;
            color: white;
        }
        
        .conversation-item.active .text-muted {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .conversation-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-right: 12px;
        }
        
        .conversation-info {
            flex: 1;
        }
        
        .conversation-participants {
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .conversation-last-message {
            font-size: 0.9rem;
            color: #6c757d;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 200px;
        }
        
        .conversation-time {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: right;
        }
        
        .messages-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            background: white;
        }
        
        .messages-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            background: #f8f9fa;
            font-weight: 600;
        }
        
        .messages-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .message-item {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }
        
        .message-item.sent {
            flex-direction: row-reverse;
        }
        
        .message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            margin: 0 8px;
        }
        
        .message-content {
            max-width: 70%;
            background: #f1f3f4;
            padding: 10px 15px;
            border-radius: 18px;
            position: relative;
        }
        
        .message-item.sent .message-content {
            background: #007bff;
            color: white;
        }
        
        .message-text {
            margin-bottom: 8px;
            word-wrap: break-word;
        }
        
        .message-time {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .message-item.sent .message-time {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .message-attachment {
            margin-top: 8px;
            padding: 8px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .attachment-icon {
            width: 24px;
            height: 24px;
        }
        
        .attachment-info {
            flex: 1;
        }
        
        .attachment-name {
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .attachment-size {
            font-size: 0.8rem;
            opacity: 0.8;
        }
        
        .no-messages {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
            font-style: italic;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
            text-align: center;
        }
        
        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
        
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #6c757d;
        }
    </style>
@endsection

@section('main')
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card radius-16">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Özel Mesajlar</h6>
                    <small class="text-muted">Avukat ve Katip arasındaki mesajlaşmalar</small>
                </div>
                
                <div class="card-body p-0">
                    <div class="chat-container">
                        <div class="row g-0 h-100">
                            <!-- Sol Panel: Konuşma Listesi -->
                            <div class="col-md-4">
                                <div class="conversation-list">
                                    @if($conversations->count() > 0)
                                        @foreach($conversations as $conversation)
                                            <div class="conversation-item" data-conversation-id="{{ $conversation->id }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="conversation-avatar">
                                                        {{ substr($conversation->avukat->name, 0, 1) }}{{ substr($conversation->katip->name, 0, 1) }}
                                                    </div>
                                                    <div class="conversation-info">
                                                        <div class="conversation-participants">
                                                            {{ $conversation->avukat->name }} ↔ {{ $conversation->katip->name }}
                                                        </div>
                                                        @if($conversation->messages->count() > 0)
                                                            <div class="conversation-last-message">
                                                                {!! Str::limit(strip_tags($conversation->messages->first()->message ?? $conversation->messages->first()->content), 50) !!}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if($conversation->messages->count() > 0)
                                                        <div class="conversation-time">
                                                            {{ $conversation->messages->first()->created_at->format('H:i') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <iconify-icon icon="mdi:message-text-outline"></iconify-icon>
                                            </div>
                                            <h5>Henüz mesaj bulunmuyor</h5>
                                            <p>Sistem henüz hiç mesaj kaydı içermiyor.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Sağ Panel: Mesajlar -->
                            <div class="col-md-8">
                                <div class="messages-container">
                                    <div class="messages-header">
                                        <div id="messages-header-content">
                                            <div class="no-messages">Bir konuşma seçin</div>
                                        </div>
                                    </div>
                                    <div class="messages-body" id="messages-body">
                                        <div class="no-messages">
                                            <iconify-icon icon="mdi:chat-outline" style="font-size: 48px; opacity: 0.3;"></iconify-icon>
                                            <p class="mt-3">Sol panelden bir konuşma seçerek mesajları görüntüleyebilirsiniz.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    const conversationItems = document.querySelectorAll('.conversation-item');
    const messagesHeader = document.getElementById('messages-header-content');
    const messagesBody = document.getElementById('messages-body');
    
    conversationItems.forEach(item => {
        item.addEventListener('click', function() {
            // Aktif konuşmayı işaretle
            conversationItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            const conversationId = this.dataset.conversationId;
            loadMessages(conversationId);
        });
    });
    
    
    function loadMessages(conversationId) {
        // Loading göster
        messagesBody.innerHTML = '<div class="loading"><iconify-icon icon="mdi:loading" style="animation: spin 1s linear infinite;"></iconify-icon> Mesajlar yükleniyor...</div>';
        
        fetch(`/admin/mesajlar/${conversationId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                displayMessages(data);
            })
            .catch(error => {
                console.error('Error loading messages:', error);
                messagesBody.innerHTML = '<div class="no-messages">Mesajlar yüklenirken bir hata oluştu: ' + error.message + '</div>';
            });
    }
    
    function displayMessages(data) {
        const { messages, avukat, katip } = data;
        
        // Header'ı güncelle
        messagesHeader.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="conversation-avatar me-3">
                    ${avukat.name.charAt(0)}${katip.name.charAt(0)}
                </div>
                <div>
                    <h6 class="mb-0">${avukat.name} ↔ ${katip.name}</h6>
                    <small class="text-muted">${messages.length} mesaj</small>
                </div>
            </div>
        `;
        
        // Mesajları göster
        if (messages.length === 0) {
            messagesBody.innerHTML = '<div class="no-messages">Bu konuşmada henüz mesaj bulunmuyor.</div>';
            return;
        }
        
        let messagesHtml = '';
        messages.forEach(message => {
            const isAvukat = message.sender_type === 'Avukat';
            const senderName = isAvukat ? avukat.name : katip.name;
            const senderInitial = senderName.charAt(0);
            
            messagesHtml += `
                <div class="message-item ${isAvukat ? 'sent' : ''}">
                    <div class="message-avatar">${senderInitial}</div>
                    <div class="message-content">
                        <div class="message-text">${decodeHtmlEntities(message.content)}</div>
                        ${message.attachments.length > 0 ? 
                            message.attachments.map(attachment => `
                                <div class="message-attachment">
                                    <iconify-icon icon="mdi:file" class="attachment-icon"></iconify-icon>
                                    <div class="attachment-info">
                                        <div class="attachment-name">${attachment.file_name}</div>
                                        <div class="attachment-size">${formatFileSize(attachment.file_size)}</div>
                                    </div>
                                    <a href="${attachment.download_url}" target="_blank" class="btn btn-sm btn-outline-light">
                                        <iconify-icon icon="mdi:download"></iconify-icon>
                                    </a>
                                </div>
                            `).join('') : ''
                        }
                        <div class="message-time">
                            ${message.created_at} (${message.created_at_human})
                        </div>
                    </div>
                </div>
            `;
        });
        
        messagesBody.innerHTML = messagesHtml;
        
        // Scroll'u en alta al
        messagesBody.scrollTop = messagesBody.scrollHeight;
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function decodeHtmlEntities(text) {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = text;
        return textarea.value;
    }
    
});
</script>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
