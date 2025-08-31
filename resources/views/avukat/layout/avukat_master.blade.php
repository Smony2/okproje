<!DOCTYPE html>
<html lang="tr">
@php
    $route = Route::current()->getName();
@endphp
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    @yield('title')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('uploads/' . ($settings->faviconyol ?? '')) }}">

    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
    <!-- Main CSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/style1.css') }}">

    @yield('cssler')

    <!-- Pusher JS -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <style>
        /* Mevcut stiller */
        .notification-item a {
            text-decoration: none;
            color: inherit;
        }
        .notification-item a:hover {
            background-color: #f8f9fa;
        }
        .audio-permission-overlay {
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
        .audio-permission-content {
            background: white;
            padding: 24px;
            border-radius: 12px;
            text-align: center;
            max-width: 420px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }
        .audio-permission-content h3 {
            color: #d4a017;
            margin-bottom: 16px;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .audio-permission-content p {
            color: #555;
            margin-bottom: 24px;
            font-size: 1rem;
        }
        .audio-permission-content button {
            background-color: #d4a017;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            margin: 0 8px;
            transition: background-color 0.2s;
        }
        .audio-permission-content button:hover {
            background-color: #b38a14;
        }
        .audio-permission-content button#denyPermissionBtn {
            background-color: #dc3545;
        }
        .audio-permission-content button#denyPermissionBtn:hover {
            background-color: #b02a37;
        }

        /* Alttan çıkan modal için stiller */
        .custom-notification-modal .modal-content {
            border-radius: 12px 12px 0 0;
            border: 0;
            background: linear-gradient(180deg, #2a2a2a 0%, #1c1c1c 100%);
            color: #fff;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
        }
        .custom-notification-modal .modal-header {
            background: transparent;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 1.5rem;
        }
        .custom-notification-modal .modal-title {
            color: #d4a017;
            font-weight: 700;
            font-size: 1.25rem;
        }
        .custom-notification-modal .modal-body {
            font-size: 1rem;
            color: #e0e0e0;
            padding: 1.5rem;
        }
        .custom-notification-modal .modal-footer {
            border-top: 0;
            padding: 1rem 1.5rem;
            background: transparent;
        }
        .custom-notification-modal .btn {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }
        .custom-notification-modal .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .custom-notification-modal .btn-success:hover {
            background-color: #218838;
        }
        .custom-notification-modal .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .custom-notification-modal .btn-danger:hover {
            background-color: #c82333;
        }
        .custom-notification-modal .btn-primary {
            background-color: #d4a017;
            border-color: #d4a017;
        }
        .custom-notification-modal .btn-primary:hover {
            background-color: #b38a14;
        }
        .custom-notification-modal .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .custom-notification-modal .btn-secondary:hover {
            background-color: #5a6268;
        }
        .custom-notification-modal .btn-close {
            filter: invert(80%);
            opacity: 0.8;
        }
        .custom-notification-modal .btn-close:hover {
            opacity: 1;
        }
        .custom-notification-modal .modal-dialog {
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%); /* X ve Y ekseninde merkezleme */
            margin: 0;
            max-width: 100%; /* veya istediğiniz bir genişlik, örn: 500px */
            width: 100%;
            transition: transform 0.3s ease-in-out;
            z-index: 1055;
        }
        .custom-notification-modal.show .modal-dialog {
            transform: translateY(0);
        }
        @media (min-width: 576px) {
            .custom-notification-modal .modal-dialog {
                max-width: 500px;
                left: 50%;
                transform: translateX(-50%) translateY(100%);
                border-radius: 12px 12px 0 0;
            }
            .custom-notification-modal.show .modal-dialog {
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a class="sidebar-logo" href="{{ route('avukat.dashboard') }}">
            <img class="light-logo" src="{{ asset('uploads/' . ($settings->logoresimyol ?? '')) }}" alt="Site Logo">
            <img class="dark-logo" src="{{ asset('uploads/' . ($settings->logoresimyol ?? '')) }}" alt="Site Logo">
            <img class="logo-icon" src="{{ asset('uploads/' . ($settings->logoresimyol ?? '')) }}" alt="Site Logo">
        </a>
    </div>
    <div class="sidebar-menu-area mt-4">

        <ul class="sidebar-menu" id="sidebar-menu">
            <li class="sidebar-menu-group-title">Menü</li>
            <li>
                <a href="{{ route('avukat.dashboard') }}" class="{{ request()->routeIs('avukat.dashboard') ? 'active' : '' }}">
                    <iconify-icon icon="solar:home-2-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('avukat.isler.adliye_sec') }}" class="{{ request()->routeIs('avukat.isler.adliye_sec') ? 'active' : '' }}">
                    <iconify-icon icon="solar:document-add-outline" class="menu-icon"></iconify-icon>
                    <span>Yeni İş Talebi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('avukat.isler.index') }}" class="{{ request()->routeIs('avukat.isler.index') ? 'active' : '' }}">
                    <iconify-icon icon="solar:folder-open-outline" class="menu-icon"></iconify-icon>
                    <span>İşlerim</span>
                </a>
            </li>
            <li>
                <a href="{{ route('avukat.chat.index') }}" class="{{ request()->routeIs('avukat.chat.index') ? 'active' : '' }}">
                    <iconify-icon icon="solar:chat-line-outline" class="menu-icon"></iconify-icon>
                    <span>Mesajlarım</span>
                </a>
            </li>
            <li>
                <a href="{{ route('avukat.favori.katipler') }}" class="{{ request()->routeIs('avukat.favori.katipler') ? 'active' : '' }}">
                    <iconify-icon icon="solar:star-outline" class="menu-icon"></iconify-icon>
                    <span>Favori Katipler</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title mt-20">Finans</li>
            <li>
                <a href="{{ route('avukat.odeme.yukle') }}" class="{{ request()->routeIs('avukat.odeme.yukle') ? 'active' : '' }}">
                    <iconify-icon icon="solar:wallet-outline" class="menu-icon"></iconify-icon>
                    <span>Jeton Yükle</span>
                </a>
            </li>
            <li>
                <a href="{{ route('avukat.odeme.gecmis') }}" class="{{ request()->routeIs('avukat.odeme.gecmis') ? 'active' : '' }}">
                    <iconify-icon icon="solar:card-outline" class="menu-icon"></iconify-icon>
                    <span>Ödeme Geçmişi</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title mt-20">Hesap Yönetimi</li>
            <li>
                <a href="{{ route('avukat.profile.edit') }}" class="{{ request()->routeIs('avukat.profile.edit') ? 'active' : '' }}">
                    <iconify-icon icon="solar:user-outline" class="menu-icon"></iconify-icon>
                    <span>Profilim</span>
                </a>
            </li>
            <li>
                <a href="{{ route('avukat.avatar-sec') }}" class="{{ request()->routeIs('avukat.avatar-sec') ? 'active' : '' }}">
                    <iconify-icon icon="solar:gallery-outline" class="menu-icon"></iconify-icon>
                    <span>Avatar Seç</span>
                </a>
            </li>
            <li>
                <a href="{{ route('avukat.password.change') }}" class="{{ request()->routeIs('avukat.password.change') ? 'active' : '' }}">
                    <iconify-icon icon="solar:lock-password-outline" class="menu-icon"></iconify-icon>
                    <span>Şifre Değiştir</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<main class="dashboard-main">
    <div class="navbar-header">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <button type="button" class="sidebar-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                        <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                    </button>
                    <button type="button" class="sidebar-mobile-toggle">
                        <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                    </button>

                    <form class="navbar-search">
                        <span class="fw-bold text-dark">Toplam Jeton : {{ number_format(auth('avukat')->user()->balance ?? 0, 0, ',', '.') }}</span>
                    </form>

                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

                    <!-- Mesaj Dropdown -->
                    <div class="dropdown">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Mesajlar</h6>
                                </div>
                                <span class="message-badge text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">0</span>
                            </div>
                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4 message-list">
                                <div class="px-24 py-12 text-center text-secondary-light">Mesaj bulunmuyor.</div>
                            </div>
                            <div class="text-center py-12 px-16">
                                <a href="{{ route('avukat.chat.index') }}" class="text-primary-600 fw-semibold text-md">Tüm Mesajları Gör</a>
                            </div>
                        </div>
                    </div>

                    <!-- Bildirim Dropdown -->
                    <div class="dropdown">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center position-relative notification-toggle" type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>

                            @if(auth('avukat')->user()->unreadNotifications()->count() > 0)
                                <span class="notification-badge position-absolute top-0 end-0 w-20-px h-20-px bg-danger text-white fw-semibold text-xs rounded-circle d-flex justify-content-center align-items-center">
                                    {{ auth('avukat')->user()->unreadNotifications()->count() }}
                                </span>
                            @endif
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Bildirimler</h6>
                                </div>

                                @if(auth('avukat')->user()->unreadNotifications()->count() > 0)
                                    <span class="notification-badge text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">
                                        {{ auth('avukat')->user()->unreadNotifications()->count() }}
                                    </span>
                                @endif
                            </div>
                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4 notification-list">
                                @forelse (auth('avukat')->user()->unreadNotifications()->latest()->get() as $notification)
                                    <a href="{{ route('avukat.isler.detay', $notification->is_id ?? '') }}" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between notification-item" data-is-id="{{ $notification->data['is_id'] ?? '' }}" data-type="{{ $notification->type ?? '' }}">
                                        <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                                <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xl"></iconify-icon>
                                            </span>
                                            <div>
                                                <h6 class="text-md fw-semibold mb-4">{{ $notification->type ? str_replace('_', ' ', $notification->type) : 'Yeni İş Talebi' }}</h6>
                                                <p class="mb-0 text-sm text-secondary-light">{{ $notification->message }}</p>
                                            </div>
                                        </div>
                                        <span class="text-sm text-secondary-light flex-shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                                    </a>
                                @empty
                                    <div class="px-24 py-12 text-center text-secondary-light">Bildirim yoktur.</div>
                                @endforelse
                            </div>
                            <div class="text-center py-12 px-16">
                                <a href="{{ route('avukat.isler.index') }}" class="text-primary-600 fw-semibold text-md">Tüm İşleri Gör</a>
                            </div>
                        </div>
                    </div>


                    <!-- Profil Dropdown -->
                    <div class="dropdown">
                        <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                            @if($avukatAvatar)
                                <img src="{{ asset($avukatAvatar->path) }}" class="w-40-px h-40-px object-fit-cover rounded-circle" width="150" alt="Avatar">
                            @else
                                <img src="{{ asset('upload/no_image.jpg') }}" class="w-40-px h-40-px object-fit-cover rounded-circle" width="40" height="40" alt="Varsayılan Avatar">
                            @endif
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm">
                            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ auth('avukat')->user()->name ?? 'Avukat Kullanıcı' }}</h6>
                                    <span class="text-secondary-light fw-medium text-sm">Avukat</span>
                                </div>
                                <button type="button" class="hover-text-danger">
                                    <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                                </button>
                            </div>
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('avukat.profile.edit') }}">
                                        <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> Profilim
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('avukat.password.change') }}">
                                        <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon> Şifre Değiştir
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('avukat.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3" style="cursor: pointer;">
                                            <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Çıkış Yap
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Alttan Çıkan Modal -->
        <div class="modal fade custom-notification-modal" id="customNotificationModal" tabindex="-1" aria-labelledby="customNotificationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customNotificationModalLabel">Yeni Teklif</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="customNotificationModalBody">
                        Bildirim yükleniyor...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-success" id="modalApproveBtn" data-teklif-id="">Onayla</button>
                        <button type="button" class="btn btn-sm btn-danger" id="modalRejectBtn" data-teklif-id="">Reddet</button>
                        <a id="jobDetailLink" href="#" class="btn btn-sm btn-primary">İş Detayına Git</a>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ses Çalma için Audio -->
        <audio id="audio" src="{{ asset('upload/sounds/notification.wav') }}"></audio>

        <!-- Bildirim ve Ses İzni Overlay -->
        <div class="audio-permission-overlay" id="permissionOverlay" style="display: none;">
            <div class="audio-permission-content">
                <h3>Bildirim ve Ses İzinleri</h3>
                <p>Teklif ve mesajlar için anlık bildirim ve sesli uyarı almak ister misiniz?</p>
                <button id="allowPermissionBtn">İzin Ver</button>
                <button id="denyPermissionBtn">Reddet</button>
            </div>
        </div>

        <div class="dashboard-main-body">
            @if (\Session::has('success'))
                <div class="mb-3 alert alert-success bg-success-600 text-white border-success-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                    {!! \Session::get('success') !!}
                    <button class="remove-button text-white text-xxl line-height-1">
                        <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
                    </button>
                </div>
            @endif
            @if (\Session::has('danger'))
                <div class="mb-3 alert alert-danger bg-danger-600 text-white border-danger-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between" role="alert">
                    {!! \Session::get('danger') !!}
                    <button class="remove-button text-white text-xxl line-height-1">
                        <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
                    </button>
                </div>
                <script>
                    if(!!window.performance && window.performance.navigation.type === 2) {
                        window.location.reload();
                    }
                </script>
            @endif
            @yield('main')
        </div>

        <footer class="d-footer">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                    <p class="mb-0">©2025 Tüm Haklar Saklıdır.</p>
                </div>
                <div class="col-auto">
                    <p class="mb-0">Made by <span class="text-primary-600">Panel</span></p>
                </div>
            </div>
        </footer>
</main>

<!-- JavaScript Libraries -->
<script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/prism.js') }}"></script>
<script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
<script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<script>
    // Ses çalma fonksiyonu
    function tiklacalistir() {
        console.log('tiklacalistir called');
        const audio = document.getElementById("audio");
        audio.play().catch(error => console.error('Ses çalma hatası:', error));
    }




    // Pusher ayarları
    // Pusher ayarları (mevcut kod korunuyor, sadece channel.bind kısmı güncelleniyor)
    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
    });

    const userId = '{{ auth("avukat")->id() }}';
    const channel = pusher.subscribe(`notifications-${userId}`);
    channel.bind('notification-sent', function(data) {
        console.log('Bildirim alındı:', JSON.stringify(data, null, 2));

        try {
            // Veri kontrolü
            if (!data || typeof data !== 'object') {
                console.error('Geçersiz bildirim verisi:', data);
                alert('hata çıktı')
                return;
            }

            tiklacalistir();


            let badges = document.querySelectorAll('.notification-badge');
            console.log('Seçilen badge’ler:', badges);

            // Eğer badge DOM’da yoksa, dinamik olarak ekle
            if (badges.length === 0) {
                const buttonBadgeContainer = document.querySelector('.notification-toggle');
                const dropdownBadgeContainer = document.querySelector('.m-16.py-12');

                if (buttonBadgeContainer) {
                    const buttonBadge = document.createElement('span');
                    buttonBadge.className = 'notification-badge position-absolute top-0 end-0 w-20-px h-20-px bg-danger text-white fw-semibold text-xs rounded-circle d-flex justify-content-center align-items-center';
                    buttonBadge.textContent = '0';
                    buttonBadge.style.display = 'none';
                    buttonBadgeContainer.appendChild(buttonBadge);
                }

                if (dropdownBadgeContainer) {
                    const dropdownBadge = document.createElement('span');
                    dropdownBadge.className = 'notification-badge text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center';
                    dropdownBadge.textContent = '0';
                    dropdownBadge.style.display = 'none';
                    dropdownBadgeContainer.appendChild(dropdownBadge);
                }

                badges = document.querySelectorAll('.notification-badge');
                console.log('Yeni badge’ler eklendi:', badges);
            }

            badges.forEach(badge => {
                let count = parseInt(badge.textContent) || 0;
                badge.textContent = count + 1;
                badge.style.display = 'flex';
            });

            // Bildirim başlığı
            const notificationTitle = data.type === 'teklif_onaylandi' ? 'Teklif Onaylandı' : (data.type ? data.type.replace(/_/g, ' ') : 'Yeni İş Talebi');

            // Yeni bildirim HTML’i
            const notificationHtml = `
                <a href="/avukat/is/${data.is_id}/detay" || ''}" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between notification-item" data-is-id="${data.is_id || ''}" data-type="${data.type || ''}">
                    <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                        <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                            <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xl"></iconify-icon>
                        </span>
                        <div>
                            <h6 class="text-md fw-semibold mb-4">${notificationTitle}</h6>
                            <p class="mb-0 text-sm text-secondary-light">${data.message || 'Bildirim mesajı'}</p>
                        </div>
                    </div>
                    <span class="text-sm text-secondary-light flex-shrink-0">${data.created_at || 'Bilinmeyen zaman'}</span>
                </a>
            `;

            // Dropdown’a ekle
            const notificationList = document.querySelector('.notification-list');
            if (notificationList) {
                notificationList.insertAdjacentHTML('afterbegin', notificationHtml);
            } else {
                console.error('notification-list bulunamadı');
                iziToast.show({
                    title: 'Hata',
                    message: 'Bildirim listesi bulunamadı.',
                    color: 'red',
                    position: 'topRight',
                    timeout: 3000,
                });
            }

            // Teklif bildirimi ise modal’ı aç
            if (data.type === 'teklif_verildi') {
                console.log('Teklif bildirimi tespit edildi, modal açılıyor');
                const modalElement = document.getElementById('customNotificationModal');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement, {
                        backdrop: 'static',
                        keyboard: false
                    });
                    const modalBody = document.getElementById('customNotificationModalBody');
                    const approveBtn = document.getElementById('modalApproveBtn');
                    const rejectBtn = document.getElementById('modalRejectBtn');
                    const jobDetailLink = document.getElementById('jobDetailLink');

                    // Modal içeriği
                    modalBody.innerHTML = `
                    <p><strong>Size yeni bir teklif geldi!</strong></p>
                    <p><strong>Kâtip:</strong> ${data.katip_username || 'Bilinmeyen Kâtip'}</p>
                    <p><strong>Teklif:</strong> ${data.jeton || 'N/A'} Jeton</p>
                    ${data.message ? `<p><strong>Mesaj:</strong> <em>${data.message}</em></p>` : ''}
                `;
                    approveBtn.setAttribute('data-teklif-id', data.teklif_id || '');
                    rejectBtn.setAttribute('data-teklif-id', data.teklif_id || '');
                    jobDetailLink.setAttribute('href', data.is_id ? `/avukat/is-detay/${data.is_id}` : '#');
                    modal.show();
                    console.log('Modal açıldı');
                } else {
                    console.error('customNotificationModal bulunamadı. DOM yüklendi mi?');
                }
            }
        } catch (error) {
            console.error('Bildirim işleme hatası:', error);
            iziToast.show({
                title: 'Hata',
                message: 'Bildirim işlenirken bir hata oluştu.',
                color: 'red',
                position: 'topRight',
                timeout: 3000,
            });
        }
    });

    // Dropdown kapatıldığında bildirimleri sıfırla
    document.querySelector('.notification-toggle').parentElement.addEventListener('hide.bs.dropdown', function() {
        fetch('{{ route("avukat.isler.notifications.markAsRead") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.notification-badge').forEach(badge => {
                        badge.textContent = '0';
                        badge.style.display = 'none';
                    });
                    document.querySelector('.notification-list').innerHTML = '<div class="px-24 py-12 text-center text-secondary-light">Bildirim bulunmuyor.</div>';
                } else {
                    iziToast.show({
                        title: 'Hata',
                        message: data.error || 'Bildirimler sıfırlanamadı.',
                        color: 'red',
                        position: 'topRight',
                        timeout: 3000,
                    });
                }
            })
            .catch(error => {
                console.error('Hata:', error);
                iziToast.show({
                    title: 'Hata',
                    message: 'Bir hata oluştu.',
                    color: 'red',
                    position: 'topRight',
                    timeout: 3000,
                });
            });
    });

    // Teklif onaylama ve reddetme için AJAX
    document.addEventListener('click', function(e) {
        if (e.target.id === 'modalApproveBtn' || e.target.id === 'modalRejectBtn' || e.target.classList.contains('accept-teklif') || e.target.classList.contains('reject-teklif')) {
            const teklifId = e.target.dataset.teklifId;
            const action = e.target.id === 'modalApproveBtn' || e.target.classList.contains('accept-teklif') ? 'ajax-teklif-onayla' : 'ajax-teklif-reddet';

            if (!teklifId) {
                iziToast.show({
                    title: 'Hata',
                    message: 'Geçersiz teklif ID’si.',
                    color: 'red',
                    position: 'topRight',
                    timeout: 3000,
                });
                return;
            }

            fetch(`/avukat/is/${teklifId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Bildirimi güncelle
                        const notificationItem = document.querySelector(`.notification-item[data-teklif-id="${teklifId}"]`);
                        if (notificationItem) {
                            notificationItem.querySelector('p').textContent = action === 'ajax-teklif-onayla' ? 'Teklif onaylandı!' : 'Teklif reddedildi!';
                        }
                        // İş detay sayfasındaki badge’i güncelle
                        const teklifBadge = document.querySelector(`.teklif-badge[data-teklif-id="${teklifId}"]`);
                        if (teklifBadge) {
                            teklifBadge.textContent = action === 'ajax-teklif-onayla' ? 'Onaylandı' : 'Reddedildi';
                            teklifBadge.className = `badge bg-${action === 'ajax-teklif-onayla' ? 'success' : 'danger'} teklif-badge`;
                        }
                        // İş detay sayfasındaki butonları kaldır
                        const teklifActions = document.querySelector(`.teklif-actions[data-teklif-id="${teklifId}"]`);
                        if (teklifActions) {
                            teklifActions.remove();
                        }
                        // Bildirim sayısını güncelle
                        const badges = document.querySelectorAll('.notification-badge');
                        badges.forEach(badge => {
                            let count = parseInt(badge.textContent) || 0;
                            if (count > 0) {
                                badge.textContent = count - 1;
                                badge.style.display = count - 1 === 0 ? 'none' : 'flex';
                            }
                        });
                        // Modal’ı kapat
                        const modal = bootstrap.Modal.getInstance(document.getElementById('customNotificationModal'));
                        if (modal) modal.hide();
                        iziToast.show({
                            title: 'Başarılı',
                            message: data.message,
                            color: 'green',
                            position: 'topRight',
                            timeout: 3000,
                        });
                    } else {
                        iziToast.show({
                            title: 'Hata',
                            message: data.error || 'Bir hata oluştu.',
                            color: 'red',
                            position: 'topRight',
                            timeout: 3000,
                        });
                    }
                })
                .catch(error => {
                    console.error('Hata:', error);
                    iziToast.show({
                        title: 'Hata',
                        message: 'Bir hata oluştu.',
                        color: 'red',
                        position: 'topRight',
                        timeout: 3000,
                    });
                });
        }
    });
</script>

<script>
    $('.remove-button').on('click', function() {
        $(this).closest('.alert').addClass('d-none')
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tüm alert-message sınıflı elementleri seç
        const alerts = document.querySelectorAll('.alert-message');
        alerts.forEach(alert => {
            // İlerleme çubuğunu başlat
            const progressBar = alert.querySelector('.progress-bar');
            if (progressBar) {
                setTimeout(() => {
                    progressBar.style.width = '100%';
                }, 10); // Kısa bir gecikme ile animasyon başlatılır
            }

            // 5 saniye sonra alert'i gizle
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500); // Animasyon süresiyle eşleşmeli
            }, 3000); // 5 saniye bekle
        });

        // Kapatma butonuna tıklama olayı
        document.querySelectorAll('.remove-button').forEach(button => {
            button.addEventListener('click', function () {
                const alert = this.closest('.alert-message');
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.remove();
                }, 500);
            });
        });
    });
</script>

@yield('jsler')
@yield('datatables')
</body>
</html>
