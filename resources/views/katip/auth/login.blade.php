<!DOCTYPE html>
<html lang="tr" class="light" id="html-root">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Katip Giriş</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <!-- Remixicon için (mevcut ikonlar için) -->
    <link rel="stylesheet" href="{{asset('assets/css/remixicon.css')}}">
    <style>
        html, body {
            font-family: 'Poppins', sans-serif;
        }

        /* Cam efekti için backdrop-blur desteği */
        .backdrop-blur {
            backdrop-filter: blur(12px);
        }

        /* Pulse animasyonu */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(56, 189, 248, 0.5); }
            70% { box-shadow: 0 0 0 15px rgba(56, 189, 248, 0); }
            100% { box-shadow: 0 0 0 0 rgba(56, 189, 248, 0); }
        }

        .dark .animate-pulse-custom {
            animation: pulse 2s infinite ease-in-out;
            box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.5);
        }

        .animate-pulse-custom {
            animation: pulse 2s infinite ease-in-out;
        }

        /* FadeIn animasyonu */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Tıklama için ripple efekti */
        @keyframes ripple {
            to { transform: translate(-50%, -50%) scale(4); opacity: 0; }
        }

        /* Dönen simge animasyonu */
        @keyframes rotate-360 {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-rotate-360 {
            animation: rotate-360 0.5s ease-in-out;
        }

        /* Gradyanlar için özel sınıflar */
        .bg-gradient-login {
            background: linear-gradient(90deg, #2563EB 0%, #60A5FA 100%);
        }

        .dark .bg-gradient-login {
            background: linear-gradient(90deg, #1E40AF 0%, #2563EB 100%);
        }

        .hover\:bg-gradient-login-hover:hover {
            background: linear-gradient(90deg, #1E40AF 0%, #2563EB 100%);
        }

        .dark .hover\:bg-gradient-login-hover:hover {
            background: linear-gradient(90deg, #1D4ED8 0%, #1E40AF 100%);
        }

        .bg-gradient-background {
            background: linear-gradient(135deg, #F9FAFB 0%, #E5E7EB 100%);
            background-image: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"%3E%3Cg opacity="0.05"%3E%3Cpath d="M0 0L20 20M20 0L0 20" stroke="%23D1D5DB" stroke-width="1"/%3E%3C/g%3E%3C/svg%3E');
        }

        .dark .bg-gradient-background {
            background: linear-gradient(135deg, #1E40AF 0%, #60A5FA 100%, #4C1D95 100%);
        }

        /* Buton parlama efekti */
        .login-btn {
            position: relative;
            overflow: hidden;
            z-index: 1;
            transform: perspective(1px) translateZ(0);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.3), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .login-btn:hover::before {
            opacity: 1;
        }


        /* Gece/Gündüz butonu tasarımı */
        .theme-toggle {
            width: 48px;
            height: 48px;
            background: #93C5FD;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .dark .theme-toggle {
            background: #1E40AF;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .theme-toggle:hover {
            transform: scale(1.1);
        }

        .theme-toggle:active {
            transform: scale(0.95);
        }

        .theme-icon {
            font-size: 24px;
            transition: color 0.3s ease;
        }

        .sun-icon {
            color: #FBBF24;
            display: none;
        }

        .dark .sun-icon {
            display: block;
        }

        .moon-icon {
            color: #1E40AF;
            display: block;
        }

        .dark .moon-icon {
            display: none;
            color: #FBBF24;
        }

        /* Şifre görünürlüğü için göz simgesi */
        .toggle-password {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gradient-background flex items-center justify-center min-h-screen">
<main>
    <div class="bg-white dark:bg-white dark:bg-opacity-20 backdrop-blur border border-gray-200 dark:border-white dark:border-opacity-20 rounded-3xl p-10 sm:p-12 max-w-2xl w-full text-center shadow-2xl dark:shadow-[0_8px_32px_rgba(0,0,0,0.4)] animate-fadeIn">

        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4 tracking-wide drop-shadow-md">Katip Girişi</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm sm:text-base tracking-wide">Sisteme erişim sağlamak için bilgilerinizi girin ve işlemlerinizi hızlıca yönetin.</p>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 dark:bg-opacity-50 text-red-600 dark:text-red-300 rounded-lg">
                <ul class="mb-0 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="p-1" action="{{ route('katip.login.post') }}" method="POST">
            @csrf
            <div class="relative mb-4">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300">
                        <iconify-icon icon="mage:email" class="text-xl"></iconify-icon>
                    </span>
                <input type="email" name="email" class="w-full pl-12 py-3 bg-gray-50 dark:bg-gray-800 dark:bg-opacity-50 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all duration-300" placeholder="E-posta adresiniz" required>
            </div>

            <div class="relative mb-4">
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300">
                        <iconify-icon icon="solar:lock-password-outline" class="text-xl"></iconify-icon>
                    </span>
                <input type="password" name="password" id="your-password" class="w-full pl-12 pr-12 py-3 bg-gray-50 dark:bg-gray-800 dark:bg-opacity-50 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all duration-300" placeholder="Şifreniz" required>
                <span class="toggle-password ri-eye-line cursor-pointer absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-300" data-toggle="#your-password"></span>
            </div>

            <div class="flex justify-between items-center mb-6">
                <label class="flex items-center space-x-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="remember" id="remeber" class="h-4 w-4 text-sky-500 border-gray-300 dark:border-gray-600 rounded focus:ring-sky-500">
                    <span class="text-sm">Beni Hatırla</span>
                </label>
                <a href="#" class="text-sky-500 dark:text-sky-300 hover:text-sky-600 dark:hover:text-sky-200 text-sm">Şifremi Unuttum</a>
            </div>

            <button type="submit" class="login-btn bg-gradient-login text-white w-full block py-4 px-6 text-lg font-semibold rounded-xl border border-gray-200 dark:border-white dark:border-opacity-30 hover:bg-gradient-login-hover hover:transform hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                Giriş Yap
            </button>
        </form>
        <p class="mt-6 text-sm text-sky-500 dark:text-sky-300 tracking-wide font-medium">© 2025 Katip & Avukat Platformu. Tüm hakları saklıdır.</p>
    </div>
</main>

<!-- Gece/Gündüz Modu Butonu (Yuvarlak Tasarım) -->
<button id="theme-toggle" class="theme-toggle fixed bottom-6 right-6">
    <span class="theme-icon sun-icon">☀️</span>
    <span class="theme-icon moon-icon">🌙</span>
</button>

<!-- JavaScript for Theme Toggle and Password Toggle -->
<script>
    // Tema durumunu kontrol et ve uygula
    const htmlRoot = document.getElementById('html-root');
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = themeToggle.querySelectorAll('.theme-icon');

    // localStorage'dan tema tercihini al
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        htmlRoot.classList.add('dark');
    }

    // Tema değiştirme butonuna tıklama olayı
    themeToggle.addEventListener('click', () => {
        htmlRoot.classList.toggle('dark');
        const isDark = htmlRoot.classList.contains('dark');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        // Simgeler için dönme animasyonu
        themeIcon.forEach(icon => {
            icon.classList.remove('animate-rotate-360');
            icon.classList.add('animate-rotate-360');
        });
    });

    // Şifre görünürlüğü için toggle işlevi
    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', () => {
            const targetId = toggle.getAttribute('data-toggle');
            const passwordField = document.querySelector(targetId);
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            toggle.classList.toggle('ri-eye-line');
            toggle.classList.toggle('ri-eye-off-line');
        });
    });
</script>

<!-- Mevcut JS dosyaları -->
<script src="{{asset('assets/js/lib/jquery-3.7.1.min.js')}}"></script>
<script src="{{asset('assets/js/lib/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/lib/dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/lib/iconify-icon.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery-ui.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{asset('assets/js/lib/magnifc-popup.min.js')}}"></script>
<script src="{{asset('assets/js/lib/slick.min.js')}}"></script>
<script src="{{asset('assets/js/lib/prism.js')}}"></script>
<script src="{{asset('assets/js/lib/file-upload.js')}}"></script>
<script src="{{asset('assets/js/lib/audioplayer.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
</body>
</html>
