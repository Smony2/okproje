@extends('admin.yonetim_master')

@section('title')
    <title>Yeni Adliye Ekle | Admin Paneli</title>
@endsection

@section('main')
    <div class="page-container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Yeni Adliye Ekle</h6>
                        <a href="{{ route('admin.adliyeler.index') }}" class="btn btn-sm btn-secondary">Geri Dön</a>
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

                        <form action="{{ route('admin.adliyeler.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3 mt-2">
                                <label class="form-label">Adliye Görseli(Kare Formatında resim yükle)</label>
                                <input type="file" name="resimyol" class="form-control">

                            </div>

                            <div class="mb-3">
                                <label for="ad" class="form-label">Adliye Adı</label>
                                <input type="text" name="ad" id="ad" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="il" class="form-label">İl</label>
                                <input type="text" name="il" id="il" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="ilce" class="form-label">İlçe</label>
                                <input type="text" name="ilce" id="ilce" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="adres" class="form-label">Adres</label>
                                <textarea name="adres" id="adres" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="telefon" class="form-label">Telefon</label>
                                <input type="text" name="telefon" id="telefon" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="konum_linki" class="form-label">Google Maps Linki</label>
                                <input type="" name="konum_linki" id="konum_linki" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="aktif_mi" class="form-label">Durum</label>
                                <select name="aktif_mi" id="aktif_mi" class="form-select">
                                    <option value="1">Aktif</option>
                                    <option value="0">Pasif</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Kaydet</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
