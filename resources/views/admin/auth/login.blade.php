<!DOCTYPE html>
<html lang="tr">
@php
    $route = Route::current()->getName();
@endphp
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>Yönetim Giriş</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('uploads/' . $settings->faviconyol) }}">

    <link rel="stylesheet" href="{{asset('assets/css/remixicon.css')}}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{asset('assets/css/lib/bootstrap.min.css')}}">

    <!-- Text Editor css -->
    <!-- Date picker css -->

    <!-- main css -->
    <link rel="stylesheet" href="{{asset('assets/css/style1.css')}}">


</head>


<body style="background: #f3efef">

<section class="auth  d-flex justify-content-center align-items-center vh-100">

    <div class="border rounded-4 p-3 bg-white" style="max-width: 550px; width: 100%;">
        <div class="mb-1">
            <div class="text-center">
                <h6 class="mb-10 fw-bold">Yönetici Paneli Giriş</h6>
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 dark:bg-opacity-50 text-red-600 dark:text-red-300 rounded-lg">
                        <ul class="mb-0 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif



            </div>


            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <form class="p-3" action="{{ route('admin.login.post') }}" method="POST">
            @csrf

            <div class="icon-field mb-3">
                <span class="icon top-50 translate-middle-y">
                    <iconify-icon icon="mage:email"></iconify-icon>
                </span>
                <input type="email" name="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="E-posta adresiniz" required>
            </div>

            <div class="position-relative mb-3">
                <div class="icon-field">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                    </span>
                    <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Şifreniz" required>
                </div>
                <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-3 text-secondary-light" data-toggle="#your-password"></span>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-20">
                <div class="form-check style-check d-flex align-items-center">
                    <input class="form-check-input border border-neutral-300" type="checkbox" value="" id="remeber">
                    <label class="form-check-label" for="remeber">Beni Hatırla </label>
                </div>
                <a href="#" class="text-primary fw-medium">Şifremi Unuttum</a>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3">Giriş Yap</button>
        </form>
    </div>

</section>





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

<!-- main js -->
<script src="{{asset('assets/js/app.js')}}"></script>



</body>
</html>
