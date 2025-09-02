@extends('avukat.layout.avukat_master')

@section('title')
    <title>Mesaj Paneli | Avukat</title>
@endsection
@section('cssler')
    <link rel="stylesheet" href="{{ asset('assets/css/chat12.css') }}">
    <style>
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        
        .advanced-notification {
            transition: all 0.3s ease;
        }
        
        .call-controls button:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
        
        .call-controls button:active {
            transform: scale(0.95);
        }
        
        .connection-quality {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }
        
        .signal-bars {
            display: flex;
            gap: 2px;
            align-items: end;
        }
        
        .signal-bars .bar {
            width: 3px;
            height: 8px;
            background: rgba(255,255,255,0.3);
            border-radius: 1px;
            transition: all 0.3s ease;
        }
        
        .signal-bars .bar.active {
            background: #2ecc71;
        }
        
        /* Enhanced Chat UI - Modern but Compatible */
        .chat-message-list {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
            background: #f8fafc;
            padding: 20px !important;
        }
        
        .chat-message-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .chat-message-list::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        
        .chat-message-list::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        
        .chat-message-list::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Enhanced Message Styling */
        .chat-single-message {
            margin-bottom: 16px;
            animation: messageSlide 0.3s ease-out;
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }
        
        .chat-single-message.right {
            flex-direction: row-reverse;
            justify-content: flex-start;
        }
        
        .chat-single-message.left {
            justify-content: flex-start;
        }
        
        .chat-single-message.right .chat-message-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 5px 20px;
            box-shadow: 0 3px 15px rgba(102, 126, 234, 0.3);
            max-width: 75%;
            word-wrap: break-word;
            padding: 12px 18px;
            position: relative;
        }
        
        .chat-single-message.left .chat-message-content {
            background: white;
            color: #374151;
            border-radius: 20px 20px 20px 5px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
            max-width: 75%;
            word-wrap: break-word;
            padding: 12px 18px;
            position: relative;
        }
        
        /* System Messages Enhancement */
        .chat-single-message.center .chat-message-content {
            background: rgba(102, 126, 234, 0.1) !important;
            color: #667eea !important;
            border: 1px solid rgba(102, 126, 234, 0.2) !important;
            text-align: center;
            font-size: 13px;
            padding: 8px 16px !important;
            border-radius: 15px !important;
            max-width: 300px !important;
            margin: 0 auto;
            box-shadow: none !important;
        }
        
        @keyframes messageSlide {
            from { opacity: 0; transform: translateY(8px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        
        /* Avatar Enhancements */
        .chat-single-message .img {
            flex-shrink: 0;
        }
        
        .chat-single-message .img img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        
        .chat-single-message.right .img img {
            border-color: rgba(102, 126, 234, 0.3);
        }
        
        /* Time Display Enhancement */
        .chat-time {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
            text-align: right;
        }
        
        .chat-single-message.left .chat-time {
            text-align: left;
        }
        
        /* Input Area Enhancement */
        .chat-message-box {
            background: white;
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .chat-message-box input[type="text"] {
            border: 1px solid #e5e7eb;
            transition: all 0.2s ease;
            font-size: 14px;
        }
        
        .chat-message-box input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .chat-message-box button:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
        
        .chat-message-box .btn-primary-600:hover {
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        /* Sidebar Enhancement */
        .chat-sidebar-single {
            transition: all 0.2s ease;
            border-radius: 10px;
            margin: 2px 8px;
        }
        
        .chat-sidebar-single:hover {
            background: #f1f5f9;
            transform: translateX(3px);
        }
        
        .chat-sidebar-single.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: none;
        }

        /* Sidebar readability improvements */
        .chat-sidebar-single .img img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }
        .chat-sidebar-single:not(.active):not(.top-profile) .info h6 {
            color: #1f2937;
            font-weight: 600;
        }
        .chat-sidebar-single:not(.active):not(.top-profile) .info p {
            color: #6b7280;
            font-size: 12px;
        }
        .chat-sidebar-single.active .info h6,
        .chat-sidebar-single.active .info p {
            color: #ffffff !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);

        }
        /* Force all text inside active/top-profile to white */
        .chat-sidebar-single.active .info *,
        .chat-sidebar-single.active.top-profile .info * {
            color: #ffffff !important;


        }
        /* Ensure top-profile (gradient card) is readable */
        .chat-sidebar-single.top-profile .info h6,
        .chat-sidebar-single.top-profile .info p {
            color: #ffffff !important;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
        }
        /* Force Online text (text-success) to white on gradient/active */
        .chat-sidebar-single.top-profile .info p.text-success,
        .chat-sidebar-single.active .info p.text-success {
            color: #ffffff !important;
        }
        .chat-sidebar-single.active .badge {
            background: #ffffff;
            color: #5b6be1;
        }
        
        /* Attachment Styling */
        .attachment-list {
            margin-top: 8px;
        }
        
        .attachment-list a {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 12px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 12px;
            margin: 2px 0;
            transition: all 0.2s ease;
        }
        
        .attachment-list a:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }
        
        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .chat-message-list {
                padding: 12px !important;
                background: #f8fafc;
            }
            
            .chat-single-message.right .chat-message-content,
            .chat-single-message.left .chat-message-content {
                max-width: 85%;
                font-size: 14px;
                padding: 10px 14px;
            }
            
            .chat-single-message .img img {
                width: 28px;
                height: 28px;
            }
            
            .chat-single-message {
                margin-bottom: 12px;
            }
        }
        
        /* Simple Call Modal Styles */
        .simple-call-modal {
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            max-width: 380px;
            margin: 0 auto;
        }
        
        .simple-call-modal .modal-body {
            padding: 2rem !important;
        }
        
        .caller-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f8f9fa;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .caller-name {
            font-weight: 600;
            color: #212529;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .caller-status {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 0.75rem;
        }
        
        .caller-type-badge {
            margin-bottom: 2rem;
        }
        
        .caller-type-badge .badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }
        
        .call-action-btn {
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .call-action-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
        
        .call-actions {
            gap: 3rem !important;
        }
    </style>
@endsection
@section('main')
    <div class="row g-3">
        <!-- Sidebar (visible only on desktop) -->
        <div class="col-lg-3 col-md-5 d-none d-md-block">
            <div class="chat-sidebar card border-0 shadow-sm rounded-3 h-100">
                <div class="chat-sidebar-single active top-profile p-3 text-white">
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
                                <h6 class="mb-0 fw-semibold" style="color:#ffffff !important;">{{ auth('avukat')->user()->username }}</h6>
                                <p class="mb-0 small {{ auth('avukat')->user()->is_active ? 'text-success' : 'text-muted' }}" style="color:#ffffff !important;">
                                    {{ auth('avukat')->user()->is_active ? 'Online' : 'Son Görülme: ' . (auth('avukat')->user()->last_active_at ? \Carbon\Carbon::parse(auth('avukat')->user()->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
                                </p>
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
                        <div class="chat-sidebar-single {{ $katip->conversation && $currentConversation && $katip->conversation->id == $currentConversation->id ? 'active text-white' : ($katip->conversation ? '' : 'new-chat') }}"
                             data-user-id="{{ $katip->id }}"
                             data-user-type="Katip"
                             onclick="window.location.href='{{ $katip->conversation ? route('avukat.chat.show', $katip->conversation->id) : route('avukat.chat.index', ['katip_id' => $katip->id]) }}'">
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
                        <div class="chat-sidebar-single active top-profile p-3 text-white">
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
                                <div class="chat-sidebar-single {{ $katip->conversation && $currentConversation && $katip->conversation->id == $currentConversation->id ? 'active' : ($katip->conversation ? '' : 'new-chat') }}"
                                     data-user-id="{{ $katip->id }}"
                                     data-user-type="Katip"
                                     onclick="window.location.href='{{ $katip->conversation ? route('avukat.chat.show', $katip->conversation->id) : route('avukat.chat.index', ['katip_id' => $katip->id]) }}'">
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
                @if($currentConversation && $currentKatip)
                    <!-- Chat Header -->
                    <div class="chat-sidebar-single-ust p-1">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <button type="button" class="btn btn-link text-muted d-md-none me-2 p-0" data-bs-toggle="modal" data-bs-target="#chatModal">
                                <iconify-icon icon="ph:arrow-left" class="fs-5"></iconify-icon>
                            </button>
                            <div class="d-flex align-items-center gap-2">
                                <div class="flex-shrink-0 img">
                                    @if($currentKatip->avatar)
                                        <img src="{{ asset($currentKatip->avatar->path) }}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <img src="{{ asset('upload/no_image.jpg') }}" alt="Avatar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                    @endif
                                </div>
                                <div class="info">
                                    <h6 class="text-md mb-0">{{ $currentKatip->username }}</h6>
                                    <p class="mb-0 small {{ $currentKatip->is_active ? 'text-success' : 'text-muted' }}">
                                        {{ $currentKatip->is_active ? 'Online' : 'Son Görülme: ' . ($currentKatip->last_active_at ? \Carbon\Carbon::parse($currentKatip->last_active_at)->diffForHumans() : 'Bilinmiyor') }}
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

                    <!-- Chat Messages -->
                    <div class="chat-message-list p-3 flex-grow-1" style="overflow-y: auto; height: calc(100vh - 180px);">
                        @foreach($currentMessages as $message)
                            @php
                                $isAvukat = $message->sender_type === 'Avukat';
                                $cls = $isAvukat ? 'right' : 'left';
                                $avatar = $isAvukat ? 
                                    (auth('avukat')->user()->avatar ? asset(auth('avukat')->user()->avatar->path) : asset('upload/no_image.jpg')) : 
                                    ($currentKatip->avatar ? asset($currentKatip->avatar->path) : asset('upload/no_image.jpg'));
                            @endphp

                            @if($message->type && Str::startsWith($message->type, 'call_'))
                                <!-- Call Message -->
                                <div class="chat-single-message mb-3" data-message-id="{{ $message->id }}" style="justify-content: center;">
                                    <div class="chat-message-content" style="max-width: 300px; text-align: center;">
                                        <div class="mb-0 system-notification">
                                                                                         @php
                                                $metadata = $message->call_metadata ?? [];
                                                $status = $metadata['status'] ?? 'initiated';
                                                $duration = $metadata['duration'] ?? 0;
                                                
                                                $callIcon = ($status === 'answered') ? '📞' : 
                                                           (($status === 'ended') ? '📞' : 
                                                           (($status === 'missed') ? '📞❌' : '📞'));
                                                $callText = ($status === 'answered') ? 'Görüşme tamamlandı' :
                                                           (($status === 'ended') ? 'Görüşme sonlandırıldı' :
                                                           (($status === 'missed') ? 'Cevapsız arama' :
                                                           'Arama başlatıldı'));
                                                
                                                $durationText = $duration > 0 ? ' · ' . floor($duration / 60) . ':' . str_pad($duration % 60, 2, '0', STR_PAD_LEFT) : '';
                                            @endphp
                                            {{ $callIcon }} {{ $callText }}{{ $durationText }}
                                        </div>
                                        <p class="chat-time mb-0">
                                            <span>{{ $message->created_at ? $message->created_at->format('H:i') : 'Bilinmeyen zaman' }}</span>
                                        </p>
                                    </div>
                                </div>
                            @else
                                <!-- Regular Message -->
                                <div class="chat-single-message {{ $cls }} mb-3" data-message-id="{{ $message->id }}">
                                    @if($cls === 'left')
                                        <img src="{{ $avatar }}" alt="Avatar" class="avatar-lg object-fit-cover rounded-circle">
                                    @endif
                                    <div class="chat-message-content">
                                        @if($message->message)
                                            <div class="mb-0 {{ Str::contains($message->message, '<') ? 'system-notification' : 'emoji' }}">
                                                {!! $message->message !!}
                                            </div>
                                        @endif
                                        
                                        @if($message->attachments && $message->attachments->count() > 0)
                                            <div class="attachment-list mt-2">
                                                @foreach($message->attachments as $attachment)
                                                    <a href="{{ asset($attachment->file_path) }}" target="_blank" style="color: {{ $cls === 'right' ? '#bfdbfe' : '#667eea' }}; text-decoration: none;">
                                                        <iconify-icon icon="ph:file"></iconify-icon>
                                                        {{ $attachment->file_name }} ({{ number_format($attachment->file_size / 1024, 2) }} KB)
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <p class="chat-time mb-0">
                                            <span>{{ $message->created_at ? $message->created_at->format('H:i') : 'Bilinmeyen zaman' }}</span>
                                        </p>
                                    </div>
                                    @if($cls === 'right')
                                        <img src="{{ $avatar }}" alt="Avatar" class="avatar-lg object-fit-cover rounded-circle">
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Chat Form -->
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
                        <input type="hidden" name="conversation_id" value="{{ $currentConversation->id }}">
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
                @else
                    <!-- No Conversation Selected -->
                    <div class="d-flex align-items-center justify-content-center h-100">
                        <div class="text-center">
                            <iconify-icon icon="solar:chat-round-dots-linear" style="font-size: 64px; color: #ccc;"></iconify-icon>
                            <h5 class="mt-3 text-muted">Sohbet seçilmedi</h5>
                            <p class="text-muted">Sohbet etmek için sol menüden bir katip seçin</p>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Hidden audio elements for call -->
            <audio id="remoteAudio" autoplay playsinline></audio>
            <audio id="ringtoneAudio" src="/assets/sounds/ringtone.mp3" preload="auto" loop></audio>
                        <!-- Simple Call Modal -->
            <div class="modal fade" id="incomingCallModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content simple-call-modal">
                        <div class="modal-body text-center">
                            <!-- Caller Avatar -->
                            <div class="mb-4">
                                <img id="callerAvatar" src="{{ asset('upload/no_image.jpg') }}" alt="Arayan Kişi" class="caller-avatar">
                        </div>
                            
                            <!-- Caller Info -->
                            <h4 class="caller-name" id="callerName">Arayan Kişi</h4>
                            <p class="caller-status" id="callerStatus">Gelen çağrı...</p>
                            <div class="caller-type-badge">
                                <span class="badge bg-primary" id="callerType">Katip</span>
                        </div>
                            
                            <!-- Call Actions -->
                            <div class="d-flex justify-content-center call-actions">
                                <button type="button" class="btn btn-danger btn-lg rounded-circle call-action-btn" id="declineCallBtn" onclick="declineIncomingCall()">
                                    <iconify-icon icon="solar:phone-down-linear" style="font-size: 26px;"></iconify-icon>
                                </button>
                                <button type="button" class="btn btn-success btn-lg rounded-circle call-action-btn" id="acceptCallBtn">
                                    <iconify-icon icon="solar:phone-linear" style="font-size: 26px;"></iconify-icon>
                                </button>
                            </div>
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
                if (activeStatusElement && data.user_type === 'Katip' && document.querySelector(`.chat-sidebar-single.active[data-user-id="${data.user_id}"]`)) {
                    activeStatusElement.textContent = data.is_active ? 'Online' : `Son Görülme: ${relativeTime(data.last_active_at) || 'Bilinmiyor'}`;
                    activeStatusElement.classList.toggle('text-success', data.is_active);
                    activeStatusElement.classList.toggle('text-muted', !data.is_active);
                }
            });

            // Kişisel kanal: sohbet açık olmasa bile arama çalsın
            const selfUserChannel = appPusher.subscribe('user-Avukat-{{ auth('avukat')->user()->id }}');
            selfUserChannel.bind('call-invited', function (data) {
                console.log('Personal channel: Call invited event received:', data);
                lkRoomName = data.room;
                // gelen davet farklı bir sohbettenden olabilir
                window.__incomingConversationId = data.conversation_id;
                
                // Eğer farklı bir conversation'dan arama geliyorsa, o conversation'a git
                if (data.conversation_id && currentConversationId !== data.conversation_id) {
                    console.log('Redirecting to conversation:', data.conversation_id);
                                    // localStorage'a gelen arama bilgisini kaydet
                localStorage.setItem('incomingCall', JSON.stringify({
                    room: data.room,
                    conversationId: data.conversation_id,
                    timestamp: Date.now(),
                    from_name: data.from_name || 'Bilinmeyen Kullanıcı',
                    from_avatar: data.from_avatar || null,
                    from_type: data.from_type || 'Kullanıcı',
                    from_id: data.from_id || null
                }));
                    window.location.href = '{{ route('avukat.chat.show', ':id') }}'.replace(':id', data.conversation_id);
                    return;
                }
                
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
                clearActiveCall(); // Diğer taraf sonlandırdığında localStorage'ı temizle
            });

            // Gelen arama var mı kontrol et (her zaman kontrol et)
            checkForIncomingCall();
            
            @if($currentConversation)
                // Mevcut sohbet için Pusher'ı setup et
                setupPusherForConversation({{ $currentConversation->id }});
                setupEmojiPanel();
                setupFileUpload();
                setupMessageForm();
                setupCallHeaderControls();
                setCurrentConversation({{ $currentConversation->id }});
                
                // Mesaj listesini en alta scroll et
                const msgList = document.querySelector('.chat-message-list');
                if (msgList) msgList.scrollTop = msgList.scrollHeight;
                
                // Aktif arama var mı kontrol et (sayfa yenilendiyse tekrar bağlan)
                checkForActiveCall();
            @endif
        });

        function setCurrentConversation(conversationId) {
            currentConversationId = conversationId;
        }

        function checkForActiveCall() {
            try {
                const activeCall = localStorage.getItem('activeCall');
                if (activeCall) {
                    const callData = JSON.parse(activeCall);
                    const now = Date.now();
                    
                    // 5 dakikadan eski aramalar geçersiz
                    if (now - callData.timestamp > 5 * 60 * 1000) {
                        localStorage.removeItem('activeCall');
                        return;
                    }
                    
                    // Eğer aynı conversation'daysa ve aktif arama varsa tekrar bağlan
                    if (callData.conversationId == currentConversationId && callData.room) {
                        console.log('Rejoining active call:', callData.room);
                        lkRoomName = callData.room;
                        lkToken = callData.token;
                        lkWsUrl = callData.wsUrl;
                        
                        if (callData.status === 'connected') {
                            joinLiveKitRoom().then(() => {
                                renderCallCard('connected');
                                if (callActionIcon) callActionIcon.setAttribute('icon', 'solar:phone-end-linear');
                                
                                // Arayan kişi bilgilerini modal'da göster
                                if (callData.from_name || callData.from_type) {
                                    console.log('🔄 Updating modal with active call data:', callData);
                                    updateCallModalInfo(callData);
                                }
                            }).catch(e => {
                                console.error('Failed to rejoin call:', e);
                                localStorage.removeItem('activeCall');
                            });
                        }
                    }
                }
            } catch (e) {
                console.error('Error checking active call:', e);
                localStorage.removeItem('activeCall');
            }
        }

        function saveActiveCall(status) {
            try {
                if (lkRoomName && currentConversationId) {
                    // Önce incoming call bilgilerini al
                    const incomingCall = localStorage.getItem('incomingCall');
                    let callerInfo = {};
                    
                    if (incomingCall) {
                        try {
                            const incomingData = JSON.parse(incomingCall);
                            callerInfo = {
                                from_name: incomingData.from_name,
                                from_avatar: incomingData.from_avatar,
                                from_type: incomingData.from_type,
                                from_id: incomingData.from_id
                            };
                        } catch (e) {
                            console.error('Error parsing incoming call data:', e);
                        }
                    }
                    
                    const callData = {
                        conversationId: currentConversationId,
                        room: lkRoomName,
                        token: lkToken,
                        wsUrl: lkWsUrl,
                        status: status,
                        timestamp: Date.now(),
                        ...callerInfo // Arayan kişi bilgilerini ekle
                    };
                    localStorage.setItem('activeCall', JSON.stringify(callData));
                    console.log('✅ Active call saved with caller info:', callData);
                }
            } catch (e) {
                console.error('Error saving active call:', e);
            }
        }

        function clearActiveCall() {
            try {
                localStorage.removeItem('activeCall');
            } catch (e) {
                console.error('Error clearing active call:', e);
            }
        }

        // Call timer variables
        let callStartTime = null;
        let callTimerInterval = null;
        let isMuted = false;
        let callSeconds = 0;

        function startCallTimer() {
            if (callTimerInterval) clearInterval(callTimerInterval);
            
            callStartTime = Date.now();
            callSeconds = 0;
            
            callTimerInterval = setInterval(() => {
                callSeconds = Math.floor((Date.now() - callStartTime) / 1000);
                const minutes = Math.floor(callSeconds / 60);
                const seconds = callSeconds % 60;
                const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Update timer in call card
                const timerElement = document.getElementById('callCardTimer');
                if (timerElement) {
                    timerElement.textContent = timeString;
                }
                
                // Update timer in any other locations
                const callTimer = document.getElementById('callTimer');
                if (callTimer) {
                    callTimer.textContent = timeString;
                }
            }, 1000);
            
            console.log('⏰ Call timer started');
        }

        function stopCallTimer() {
            if (callTimerInterval) {
                clearInterval(callTimerInterval);
                callTimerInterval = null;
                console.log('⏰ Call timer stopped');
            }
        }

        function toggleMute() {
            try {
                if (lkRoom && lkRoom.localParticipant) {
                    const audioTrack = lkRoom.localParticipant.getTrackBySource('microphone');
                    if (audioTrack) {
                        if (isMuted) {
                            audioTrack.unmute();
                            isMuted = false;
                            console.log('🎤 Microphone unmuted');
                            showAdvancedNotification('Mikrofon Açıldı', 'Sesiniz artık duyuluyor', 'success', 2000);
                        } else {
                            audioTrack.mute();
                            isMuted = true;
                            console.log('🔇 Microphone muted');
                            showAdvancedNotification('Mikrofon Kapatıldı', 'Sesiniz artık duyulmuyor', 'info', 2000);
                        }
                        
                        // Update mute button icon
                        const muteBtn = document.querySelector('[onclick="toggleMute()"]');
                        if (muteBtn) {
                            const icon = muteBtn.querySelector('iconify-icon');
                            if (icon) {
                                icon.setAttribute('icon', isMuted ? 'mdi:microphone-off' : 'mdi:microphone');
                            }
                            muteBtn.style.background = isMuted ? 'rgba(255, 71, 87, 0.3)' : 'rgba(255,255,255,0.2)';
                        }
                    }
                }
            } catch (error) {
                console.error('❌ Error toggling mute:', error);
                showAdvancedNotification('Hata', 'Mikrofon durumu değiştirilemedi', 'error', 3000);
            }
        }

        function showAdvancedNotification(title, message, type = 'info', duration = 4000) {
            const notification = document.createElement('div');
            notification.className = `advanced-notification ${type}`;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                max-width: 300px;
                background: ${type === 'success' ? '#2ecc71' : type === 'error' ? '#e74c3c' : type === 'warning' ? '#f39c12' : '#3498db'};
                color: white;
                padding: 15px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                animation: slideInRight 0.3s ease-out;
            `;
            
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <iconify-icon icon="${getNotificationIcon(type)}" style="font-size: 20px;"></iconify-icon>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 2px;">${title}</div>
                        <div style="font-size: 13px; opacity: 0.9;">${message}</div>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; margin-left: auto;">×</button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after duration
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOutRight 0.3s ease-in';
                    setTimeout(() => notification.remove(), 300);
                }
            }, duration);
        }

        function getNotificationIcon(type) {
            switch (type) {
                case 'success': return 'solar:check-circle-linear';
                case 'error': return 'solar:close-circle-linear';
                case 'warning': return 'solar:danger-triangle-linear';
                case 'info': return 'solar:info-circle-linear';
                default: return 'solar:bell-linear';
            }
        }

        function forceCloseIncomingCallModal() {
            console.log('🔧 Force closing incoming call modal...');
            
            try {
                // 1. Bootstrap modal instance'ı kapat
                const modalElement = document.getElementById('incomingCallModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                        modal.dispose(); // Modal instance'ını tamamen temizle
                    }
                }
                
                // 2. Tüm backdrop'ları kaldır
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => {
                    console.log('Removing backdrop:', backdrop);
                    backdrop.remove();
                });
                
                // 3. Body'den modal class'ları temizle
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
                
                // 4. Modal'ı gizle
                if (modalElement) {
                    modalElement.style.display = 'none';
                    modalElement.classList.remove('show');
                    modalElement.setAttribute('aria-hidden', 'true');
                    modalElement.removeAttribute('aria-modal');
                }
                
                // 5. Overlay'leri temizle
                const overlays = document.querySelectorAll('.modal, .fade, [style*="background"]');
                overlays.forEach(overlay => {
                    if (overlay.style.backgroundColor || overlay.classList.contains('modal-backdrop')) {
                        overlay.remove();
                    }
                });
                
                console.log('✅ Modal cleanup completed');
                
            } catch (e) {
                console.error('❌ Error during modal cleanup:', e);
                
                // Son çare: Tüm modal elementlerini zorla temizle
                try {
                    const allBackdrops = document.querySelectorAll('[class*="backdrop"], [style*="background"], .modal-backdrop, .fade');
                    allBackdrops.forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.cssText = '';
                } catch (e2) {
                    console.error('❌ Emergency cleanup failed:', e2);
                }
            }
        }

        // Update call modal with caller information
        function updateCallModalInfo(callData) {
            try {
                console.log('🔄 Updating modal with call data:', callData);
                
                // Update caller avatar
                const callerAvatar = document.getElementById('callerAvatar');
                if (callerAvatar) {
                    if (callData.from_avatar) {
                        callerAvatar.src = callData.from_avatar;
                    } else {
                        callerAvatar.src = '{{ asset("upload/no_image.jpg") }}';
                    }
                }
                
                // Update caller name
                const callerName = document.getElementById('callerName');
                if (callerName) {
                    callerName.textContent = callData.from_name || 'Bilinmeyen Kullanıcı';
                }
                
                // Update caller status
                const callerStatus = document.getElementById('callerStatus');
                if (callerStatus) {
                    callerStatus.textContent = 'Gelen çağrı...';
                }
                
                // Update caller type
                const callerType = document.getElementById('callerType');
                if (callerType) {
                    const type = callData.from_type || 'Kullanıcı';
                    callerType.textContent = type;
                    
                    // Update badge color based on type
                    callerType.className = 'badge';
                    if (type === 'Katip') {
                        callerType.classList.add('bg-success');
                    } else if (type === 'Avukat') {
                        callerType.classList.add('bg-primary');
                    } else {
                        callerType.classList.add('bg-secondary');
                    }
                }
                
                console.log('✅ Call modal info updated successfully');
            } catch (e) {
                console.error('❌ Error updating call modal info:', e);
            }
        }

        function checkForIncomingCall() {
            try {
                const incomingCall = localStorage.getItem('incomingCall');
                console.log('🔍 checkForIncomingCall - localStorage incomingCall:', incomingCall);
                if (incomingCall) {
                    const callData = JSON.parse(incomingCall);
                    const now = Date.now();
                    
                    // 30 saniyeden eski aramalar geçersiz
                    if (now - callData.timestamp > 30 * 1000) {
                        localStorage.removeItem('incomingCall');
                        return;
                    }
                    
                    // Eğer aynı conversation için active call varsa incoming call gösterme
                    const activeCall = localStorage.getItem('activeCall');
                    if (activeCall) {
                        const activeCallData = JSON.parse(activeCall);
                        if (activeCallData.conversationId == callData.conversationId) {
                            console.log('Active call exists for this conversation, removing incoming call');
                            localStorage.removeItem('incomingCall');
                            return;
                        }
                    }
                    
                    // URL'den conversation ID'yi al
                    const urlPath = window.location.pathname;
                    const urlConversationId = urlPath.match(/\/avukat\/mesajlar\/(\d+)/)?.[1];
                    
                    // Eğer doğru conversation'daysa veya currentConversationId varsa gelen aramayı göster
                    if (callData.conversationId == urlConversationId || callData.conversationId == currentConversationId) {
                        // Eğer modal zaten açıksa tekrar açma
                        const existingModal = bootstrap.Modal.getInstance(document.getElementById('incomingCallModal'));
                        if (existingModal && existingModal._isShown) {
                            console.log('Modal already open, skipping...');
                            return;
                        }
                        
                        console.log('Showing incoming call after redirect:', callData.room);
                        lkRoomName = callData.room;
                        window.__incomingConversationId = callData.conversationId;
                        
                        // currentConversationId'yi güncelle
                        if (!currentConversationId) {
                            setCurrentConversation(callData.conversationId);
                        }
                        
                        startRingtone();
                        renderCallCard('incoming');
                        
                        // Show incoming call modal with caller info
                        try {
                            // Update modal with caller information
                            updateCallModalInfo(callData);
                            
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
                        window.__incomingTimeout = setTimeout(() => {
                            try { stopRingtone(); } catch {}
                            try { 
                                const modal = bootstrap.Modal.getInstance(document.getElementById('incomingCallModal'));
                                if (modal) modal.hide();
                            } catch {}
                            localStorage.removeItem('incomingCall');
                            showNotification('Çağrı cevaplanmadı', 'danger');
                        }, 30000);
                        
                        // localStorage'ı hemen temizleme - sadece kabul/reddet edildiğinde temizle
                    }
                }
            } catch (e) {
                console.error('Error checking incoming call:', e);
                localStorage.removeItem('incomingCall');
            }
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

        // --- LiveKit minimal helpers ---
        // Ringtone state
        let ringtoneCtx = null;
        let ringtoneOsc = null;
        let ringtoneGain = null;
        let ringtoneTimer = null;
        // Call UI state
        let callTimer = null;
        // callSeconds already defined above
        // let isMuted already defined above
        const callActionBtn = document.getElementById('callActionBtn');
        const callActionIcon = document.getElementById('callActionIcon');
        const remoteAudio = document.getElementById('remoteAudio');
        const ringtoneAudio = document.getElementById('ringtoneAudio');

        // Header action wiring
        if (callActionBtn) callActionBtn.addEventListener('click', () => {
            // Toggle between start and end depending on state
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
                    ringtoneOsc.frequency.value = 800; // Hz
                    ringtoneGain.gain.value = 0.06; // low volume
                    ringtoneOsc.connect(ringtoneGain).connect(ringtoneCtx.destination);
                    ringtoneOsc.start();
                    setTimeout(stopCurrentTone, 700); // beep length
                };

                ringtoneTimer = setInterval(playBeep, 1400); // on/off pattern
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

        // startCallTimer and stopCallTimer functions moved to above

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

        // toggleMute function moved to above

        async function fetchToken(room) {
            const res = await fetch(`/avukat/mesajlar/${currentConversationId}/call/token`, {
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

        async function fetchTokenForConversation(room, conversationId) {
            const res = await fetch(`/avukat/mesajlar/${conversationId}/call/token`, {
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
                const inviteRes = await fetch(`/avukat/mesajlar/${currentConversationId}/call/invite`, {
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
                console.log('🔵 acceptIncomingCall started');
                
                // localStorage'dan incoming call verisini al
                const storedCall = localStorage.getItem('incomingCall');
                console.log('📦 storedCall:', storedCall);
                if (!storedCall) {
                    console.log('❌ No stored call found');
                    return;
                }
                
                let callData;
                try {
                    callData = JSON.parse(storedCall);
                    console.log('📋 callData:', callData);
                } catch (e) {
                    console.error('Invalid incoming call data:', e);
                    localStorage.removeItem('incomingCall');
                    return;
                }
                
                if (!callData.room || !callData.conversationId) {
                    console.log('❌ Missing room or conversationId:', callData);
                    return;
                }
                
                // Değişkenleri localStorage'dan al
                lkRoomName = callData.room;
                const incomingConversationId = callData.conversationId;
                console.log('🎯 Using room:', lkRoomName, 'conversation:', incomingConversationId);
                
                console.log('Accepting call for conversation:', incomingConversationId);
                
                // Clear the timeout to prevent "call not answered" message
                try { 
                    if (window.__incomingTimeout) {
                        clearTimeout(window.__incomingTimeout);
                        window.__incomingTimeout = null;
                    }
                } catch {}
                
                // Close the modal with comprehensive cleanup
                forceCloseIncomingCallModal();
                
                stopRingtone();
                
                console.log('📞 Calling accept API...');
                const acceptResponse = await fetch(`/avukat/mesajlar/${incomingConversationId}/call/accept`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'X-Socket-Id': pusherSocketId || ''
                    },
                    body: JSON.stringify({ room: lkRoomName })
                });
                console.log('📞 Accept API response:', acceptResponse.status, acceptResponse.statusText);
                
                console.log('🎫 Fetching token...');
                const tokenData = await fetchTokenForConversation(lkRoomName, incomingConversationId);
                console.log('🎫 Token data:', tokenData);
                
                lkToken = tokenData.token;
                lkWsUrl = tokenData.ws_url;
                
                console.log('🚀 Joining LiveKit room...');
                await joinLiveKitRoom();
                console.log('✅ Joined LiveKit room successfully');
                
                if (callActionIcon) callActionIcon.setAttribute('icon', 'solar:phone-end-linear');
                renderCallCard('connected');
                
                // Gelen aramanın conversation ID'sini kaydet
                setCurrentConversation(incomingConversationId);
                saveActiveCall('connected');
                
                // Clear incoming call from localStorage after successful accept
                localStorage.removeItem('incomingCall');
            } catch (e) {
                console.error(e);
                // Clear localStorage on error too
                localStorage.removeItem('incomingCall');
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
                
                // Clear incoming call from localStorage
                localStorage.removeItem('incomingCall');
                
                // Use comprehensive modal cleanup
                forceCloseIncomingCallModal();
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
            
            // Stop call timer
            stopCallTimer();
            
            try {
                if (currentConversationId && lkRoomName) {
                    console.log('Ending call for room:', lkRoomName);
                    await fetch(`/avukat/mesajlar/${currentConversationId}/call/end`, {
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
            
            // reset header icon
            if (callActionIcon) callActionIcon.setAttribute('icon', 'mi:call');
            
            renderCallCard('ended');
            clearActiveCall();
            
            console.log('Call ended successfully');
            
            // Reset flag after a delay
            setTimeout(() => {
                isEndingCall = false;
            }, 1000);
        }

        async function ensureLiveKit() {
            // CDN UMD only: wait for global LivekitClient
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
                // fetch ICE from backend token endpoint already returned iceServers
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
                        // try to find actual track instance in different SDK signatures
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
                
                // set UI icons
                if (callActionIcon) callActionIcon.setAttribute('icon', 'solar:phone-end-linear');
                
                console.log('LiveKit room setup completed successfully');
            } catch (e) {
                console.error('Failed to join LiveKit room:', e);
                throw e;
            }
        }

        // Bind conversation channel for call events
        function bindCallEvents(channel) {
            channel.bind('call-invited', function (data) {
                console.log('Call invited event received:', data);
                
                // Aynı room için zaten işlem yapılmışsa skip et
                const existingCall = localStorage.getItem('incomingCall');
                if (existingCall) {
                    const existingData = JSON.parse(existingCall);
                    if (existingData.room === data.room) {
                        console.log('🔄 Same room call already processed, skipping:', data.room);
                        return;
                    }
                }
                
                lkRoomName = data.room;
                
                // localStorage'a gelen arama bilgisini kaydet
                localStorage.setItem('incomingCall', JSON.stringify({
                    room: data.room,
                    conversationId: data.conversation_id,
                    timestamp: Date.now(),
                    from_name: data.from_name || 'Bilinmeyen Kullanıcı',
                    from_avatar: data.from_avatar || null,
                    from_type: data.from_type || 'Kullanıcı',
                    from_id: data.from_id || null
                }));
                console.log('📦 Incoming call saved to localStorage:', data.room, data.conversation_id);
                
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
                clearActiveCall(); // Diğer taraf sonlandırdığında localStorage'ı temizle
            });
        }

        // Hook into existing Pusher subscription
        const originalSetupPusherForConversation = setupPusherForConversation;
        setupPusherForConversation = function (conversationId) {
            const result = originalSetupPusherForConversation(conversationId);
            // 'currentChannel' is set by original function
            if (typeof currentChannel !== 'undefined' && currentChannel) {
                bindCallEvents(currentChannel);
            }
            return result;
        }
    </script>
@endsection