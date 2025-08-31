@extends('admin.yonetim_master')

@section('title')
    <title>Yönetici Düzenle</title>
@endsection

@section('main')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Yönetici Düzenle     <a style="float: right" href="{{ route('admin.yoneticiler.index') }}" class="btn btn-ekle waves-effect waves-light">
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

                            <form action="{{ route('admin.yoneticiler.update', $admin->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label>Ad Soyad</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $admin->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>E-posta</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email', $admin->email) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Yeni Şifre (İsterseniz değiştirin)</label>
                                    <input type="password" class="form-control" name="password" autocomplete="new-password">
                                </div>

                                <div class="form-group">
                                    <label>Yeni Şifre Tekrar</label>
                                    <input type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                </div>

                                <div class="form-group">
                                    <label>Aktiflik Durumu</label><br>
                                    <input type="checkbox" name="is_active" {{ $admin->is_active ? 'checked' : '' }}> Aktif
                                </div>

                                <div class="form-group">
                                    <label>Yönetici Rolleri</label><br>
                                    @foreach($roles as $role)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                                {{ $admin->roles->contains($role->id) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="submit" class="btn btn-primary waves-effect waves-light">Yöneticiyi Güncelle</button>
                                <a href="{{ route('admin.yoneticiler.index') }}" class="btn btn-secondary waves-effect m-l-5">Geri Dön</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
