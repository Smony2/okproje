@extends('avukat.layout.avukat_master')

@section('title')
    <title>Yeni Yönetici Ekle</title>
@endsection

@section('main')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card m-b-20">
                        <div class="card-header">
                            <h4>Yönetici Ekle <a style="float: right" href="{{ route('avukat.yoneticiler.index') }}"
                                                 class="btn btn-ekle waves-effect waves-light">
                                    <i class="fa fa-arrow-left"></i> Geri Dön
                                </a>
                            </h4>
                        </div>
                        <div class="card-body">

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('avukat.yoneticiler.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Ad Soyad</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label>E-posta</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                           required>
                                </div>

                                <div class="form-group">
                                    <label>Şifre</label>
                                    <input type="password" class="form-control" name="password"
                                           autocomplete="new-password" required>
                                </div>

                                <div class="form-group">
                                    <label>Şifre Tekrar</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>

                                <div class="form-group">
                                    <label>Aktiflik Durumu</label><br>
                                    <input type="checkbox" name="is_active" checked> Aktif
                                </div>

                                <div class="form-group">
                                    <label>Yönetici Rolleri</label><br>
                                    @foreach($roles as $role)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="roles[]"
                                                   value="{{ $role->id }}" id="role_{{ $role->id }}">
                                            <label class="form-check-label"
                                                   for="role_{{ $role->id }}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="submit" class="btn btn-primary waves-effect waves-light">Yönetici Ekle
                                </button>
                                <a href="{{ route('avukat.yoneticiler.index') }}"
                                   class="btn btn-secondary waves-effect m-l-5">Geri Dön</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
