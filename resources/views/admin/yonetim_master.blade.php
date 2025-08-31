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

    <link rel="shortcut icon" href="{{ asset('uploads/' . $settings->faviconyol) }}">

    <link rel="stylesheet" href="{{asset('assets/css/remixicon.css')}}">

    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/bootstrap.min.css')}}">

    <!-- Text Editor css -->
    <!-- Date picker css -->

    <!-- main css -->
    <link rel="stylesheet" href="{{asset('assets/css/style1.css')}}">
    @yield('cssler')

    <style>
        .form-control[type=file] {
            line-height: 1.5;
        }

        .iziToast-wrapper{
            z-index: 1000000;
        }
        .alert-success .progress-bar { background-color: #28a745; }
        .alert-danger .progress-bar { background-color: #dc3545; }


        .btn-add {
            background: linear-gradient(90deg, #4a90e2, #63b3ed);
            color: #ffffff;
            border: none;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(74, 144, 226, 0.3);
            transition: all 0.3s ease;
        }
        .btn-add:hover {
            background: linear-gradient(90deg, #357abd, #4da8e8);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(74, 144, 226, 0.4);
            color: white;
        }
        .btn-add iconify-icon {
            font-size: 20px;
        }

        /* Badge genel stil */
        .badge {
            display: inline-block;
            font-size: 12px;
            padding: 4px 10px;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Renkli badge'ler */
        .badge-success {
            background-color: #28a745;
            color: #ffffff;
        }

        .badge-danger {
            background-color: #dc3545;
            color: #ffffff;
        }

        .badge-primary {
            background-color: #007bff;
            color: #ffffff;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }

        .badge-info {
            background-color: #17a2b8;
            color: #ffffff;
        }

        /* Hover efekti */
        .badge-success:hover,
        .badge-danger:hover,
        .badge-primary:hover,
        .badge-warning:hover,
        .badge-info:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Daha küçük badge'ler için opsiyonel */
        .badge-sm {
            padding: 0.2em 0.5em;
            font-size: 0.7rem;
        }

        /* Daha büyük badge'ler için opsiyonel */
        .badge-lg {
            padding: 0.4em 0.8em;
            font-size: 0.9rem;
        }
        .fw-medium {
            font-weight: 400 !important;
            font-size: 15px;
        }
        .striped-table tbody tr td {
            color: var(--text-secondary-light);

        }
    </style>

</head>
<body>

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>

        <div class="">
            <a class="sidebar-logo" href="{{ route('admin.dashboard') }}">
                <img class="light-logo" src="{{ asset('uploads/' . $settings->logoresimyol) }}" alt="Site Logo">
                <img class="dark-logo" src="{{ asset('uploads/' . $settings->logoresimyol) }}" alt="Site Logo">
                <img class="logo-icon" src="{{ asset('uploads/' . $settings->logoresimyol) }}" alt="Site Logo">
            </a>
        </div>



    </div>
    <div class="sidebar-menu-area mt-4">
        <ul class="sidebar-menu" id="sidebar-menu">

            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <iconify-icon icon="mdi:view-dashboard-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.avukatlar.index') }}" class="{{ request()->routeIs('admin.avukatlar.*') ? 'active-page' : '' }}">
                    <iconify-icon icon="mdi:account-tie-outline" class="menu-icon"></iconify-icon>
                    <span>Avukatlar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.katipler.index') }}" class="{{ request()->routeIs('admin.katipler.*') ? 'active-page' : '' }}">
                    <iconify-icon icon="mdi:account-group-outline" class="menu-icon"></iconify-icon>
                    <span>Katipler</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.adliyeler.index') }}">
                    <iconify-icon icon="mdi:home-outline" class="menu-icon"></iconify-icon>
                    <span>Adliye Yönetimi</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title mt-20">İşler</li>

            <li>
                <a href="{{ route('admin.isler.index') }}">
                    <iconify-icon icon="mdi:briefcase-outline" class="menu-icon"></iconify-icon>
                    <span>İş Detayları</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.degerlendirme.avukat') }}">
                    <iconify-icon icon="mdi:star-outline" class="menu-icon"></iconify-icon>
                    <span>Avukat Değerlendirmeleri</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.degerlendirme.katip') }}">
                    <iconify-icon icon="mdi:star-outline" class="menu-icon"></iconify-icon>
                    <span>Katip Değerlendirmeleri</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.teklifler.index') }}">
                    <iconify-icon icon="mdi:handshake-outline" class="menu-icon"></iconify-icon>
                    <span>İş Teklifleri</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.teslimler.index') }}">
                    <iconify-icon icon="mdi:file-upload-outline" class="menu-icon"></iconify-icon>
                    <span>İş Teslimleri</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kazanc.katip') }}">
                    <iconify-icon icon="mdi:wallet-outline" class="menu-icon"></iconify-icon>
                    <span>Katip Kazançlar</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title mt-20">Finans</li>

            <li>
                <a href="{{ route('admin.odeme.index') }}">
                    <iconify-icon icon="mdi:message-text-outline" class="menu-icon"></iconify-icon>
                    <span>Jeton Talepleri</span>
                </a>
            </li>




            <li class="sidebar-menu-group-title mt-20">Avatar Yönetimi</li>

            <li>
                <a href="{{ route('admin.avatarlar.index') }}">
                    <iconify-icon icon="mdi:account-circle-outline" class="menu-icon"></iconify-icon>
                    <span>Avukat Avatar Yönetimi</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.katip-avatarlar.index') }}">
                    <iconify-icon icon="mdi:account-box-outline" class="menu-icon"></iconify-icon>
                    <span>Katip Avatar Yönetimi</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title mt-20">Sistem Ayarları</li>

            <li>
                <a href="{{route('admin.site-settings.index')}}">
                    <iconify-icon icon="mdi:account-edit-outline" class="menu-icon"></iconify-icon>
                    <span>Site Ayarları</span>
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
                        <input type="text" name="search" placeholder="Search">
                        <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    </form>
                </div>
            </div>
            <div class="col-auto">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

                    <div class="dropdown">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Mesajlar</h6>
                                </div>
                                <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">0</span>
                            </div>

                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">



                            </div>
                            <div class="text-center py-12 px-16">
                                <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">Tüm Mesajlar</a>
                            </div>
                        </div>
                    </div><!-- Message dropdown end -->

                    <div class="dropdown">
                        <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Bildirimler</h6>
                                </div>
                                <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">0</span>
                            </div>

                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">



                            </div>


                        </div>
                    </div><!-- Notification dropdown end -->

                    <div class="dropdown">
                        <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                            <img src="{{asset(auth('admin')->user()->avatar)}}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm">
                            <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ auth('admin')->user()->name }}</h6>
                                    <span class="text-secondary-light fw-medium text-sm">Admin</span>
                                </div>
                                <button type="button" class="hover-text-danger">
                                    <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                                </button>
                            </div>
                            <ul class="to-top-list">
                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('admin.profile.edit') }}">
                                        <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> Profilim</a>
                                </li>

                                <li>
                                    <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="{{ route('admin.password.change') }}">
                                        <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon>  Şifre Değiştir</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('admin.logout') }}">
                                        @csrf
                                        <button
                                            type="submit"
                                            class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                                            style="cursor: pointer;"
                                        >
                                            <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>
                                            Çıkış Yap
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div><!-- Profile dropdown end -->
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-main-body">
        @if (\Session::has('success'))
            <div class="mb-3 alert alert-success bg-success-600 text-white border-success-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between alert-message" role="alert">
                {!! \Session::get('success') !!}
                <button class="remove-button text-white text-xxl line-height-1">
                    <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
                </button>
            </div>
        @endif

        @if (\Session::has('danger'))
            <div class="mb-3 alert alert-danger bg-danger-600 text-white border-danger-600 px-24 py-11 mb-0 fw-semibold text-lg radius-8 d-flex align-items-center justify-content-between alert-message" role="alert">
                {!! \Session::get('danger') !!}
                <button class="remove-button text-white text-xxl line-height-1">
                    <iconify-icon icon="iconamoon:sign-times-light" class="icon"></iconify-icon>
                </button>
            </div>

            <script>
                if (!!window.performance && window.performance.navigation.type === 2) {
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







<!-- jQuery library js -->
<script src="{{asset('assets/js/lib/jquery-3.7.1.min.js')}}"></script>
<!-- Bootstrap js -->
<script src="{{asset('assets/js/lib/bootstrap.bundle.min.js')}}"></script>
<!-- Apex Chart js -->
<!-- Data Table js -->
<script src="{{asset('assets/js/lib/dataTables.min.js')}}"></script>
<!-- Iconify Font js -->
<script src="{{asset('assets/js/lib/iconify-icon.min.js')}}"></script>
<!-- jQuery UI js -->
<script src="{{asset('assets/js/lib/jquery-ui.min.js')}}"></script>
<!-- Vector Map js -->
<script src="{{asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- Popup js -->
<script src="{{asset('assets/js/lib/magnifc-popup.min.js')}}"></script>
<!-- Slick Slider js -->
<script src="{{asset('assets/js/lib/slick.min.js')}}"></script>
<!-- prism js -->
<script src="{{asset('assets/js/lib/prism.js')}}"></script>
<!-- file upload js -->
<script src="{{asset('assets/js/lib/file-upload.js')}}"></script>
<!-- audioplayer -->
<script src="{{asset('assets/js/lib/audioplayer.js')}}"></script>

<!-- main js -->
<script src="{{asset('assets/js/app.js')}}"></script>


@yield('jsler')
@yield('datatables')
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

</body>
</html>
