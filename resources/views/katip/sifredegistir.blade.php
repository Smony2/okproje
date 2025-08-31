{{-- resources/views/avukat/password-settings.blade.php --}}
@extends('katip.layout.katip_master')

@section('title')
    <title>Şifre Ayarları</title>
@endsection

@section('main')
    <div class="mt-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card m-b-20">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Şifre Ayarı</h4>
                        <p class="text-muted mb-4">Şifre bilgilerinizi buradan güncelleyebilirsiniz.</p>


                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('katip.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="current_password" class="form-label">Mevcut Şifre</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="current_password"
                                        name="current_password"
                                        required
                                    >
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Yeni Şifre</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        required
                                    >
                                </div>
                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Yeni Şifre (Tekrar)</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    Şifreyi Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
