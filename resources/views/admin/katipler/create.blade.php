@extends('admin.yonetim_master')

@section('title')
    <title>Yeni Katip Ekle</title>
@endsection

@section('cssler')
    <style>
        .form-label { font-weight: 500; color: #333; }
        .form-control, .form-select, .form-check-input { border-radius: .5rem; }
    </style>
@endsection

@section('main')
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">Yeni Katip Ekle</h6>
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
                    <form action="{{ route('admin.katipler.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Ad Soyad</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telefon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">T.C. No</label>
                                <input type="text" name="tc_no" class="form-control" value="{{ old('tc_no') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Ünvan</label>
                                <input type="text" name="unvan" class="form-control" value="{{ old('unvan') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Uzmanlık Alanı</label>
                                <input type="text" name="uzmanlik_alani" class="form-control" value="{{ old('uzmanlik_alani') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Doğum Tarihi</label>
                                <input type="date" name="dogum_tarihi" class="form-control" value="{{ old('dogum_tarihi') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Cinsiyet</label>
                                <select name="cinsiyet" class="form-select">
                                    <option value="">Seçiniz</option>
                                    @foreach(['Erkek','Kadın','Diğer'] as $c)
                                        <option value="{{ $c }}" @selected(old('cinsiyet') == $c)>{{ $c }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mezuniyet Üniversitesi</label>
                                <input type="text" name="mezuniyet_universitesi" class="form-control" value="{{ old('mezuniyet_universitesi') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mezuniyet Yılı</label>
                                <input type="number" name="mezuniyet_yili" class="form-control" min="1900" max="{{ date('Y') }}" value="{{ old('mezuniyet_yili') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Adres</label>
                                <textarea name="adres" class="form-control" rows="2">{{ old('adres') }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notlar</label>
                                <textarea name="notlar" class="form-control" rows="2">{{ old('notlar') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Şifre</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Şifre (Tekrar)</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
