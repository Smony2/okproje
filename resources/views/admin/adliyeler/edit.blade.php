@extends('admin.yonetim_master')

@section('title')
    <title>Adliye Düzenle | Admin Paneli</title>
@endsection

@section('main')
    <div class="mt-4">
        <div class="row">
            <div class="col-lg-10">
                <div class="card">

                    <div class="d-flex justify-content-between align-items-center mb-0 card-header">
                        <h6>Adliye Düzenle</h6>
                        <a href="{{ route('admin.adliyeler.index') }}" class="btn btn-primary">Geri Dön</a>
                    </div>
                    <div class="card-body mt-0 pt-1">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.adliyeler.update', $adliye->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')


                            @if($adliye->resimyol)
                                <img src="{{ asset($adliye->resimyol) }}" alt="" class="img-thumbnail" style="max-width:150px">
                            @endif
                            <div class="mb-3 mt-2">

                                <label class="form-label">Adliye Görseli(Kare Formatında resim yükle)</label>
                                <input type="file" name="resimyol" class="form-control">

                            </div>

                            <div class="mb-3">
                                <label for="ad" class="form-label">Adliye Adı</label>
                                <input type="text" name="ad" class="form-control" value="{{ $adliye->ad }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="il" class="form-label">İl</label>
                                <input type="text" name="il" class="form-control" value="{{ $adliye->il }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="ilce" class="form-label">İlçe</label>
                                <input type="text" name="ilce" class="form-control" value="{{ $adliye->ilce }}">
                            </div>

                            <div class="mb-3">
                                <label for="adres" class="form-label">Adres</label>
                                <input type="text" name="adres" class="form-control" value="{{ $adliye->adres }}">
                            </div>

                            <div class="mb-3">
                                <label for="telefon" class="form-label">Telefon</label>
                                <input type="text" name="telefon" class="form-control" value="{{ $adliye->telefon }}">
                            </div>

                            <div class="mb-3">
                                <label for="konum_linki" class="form-label">Google Maps Linki</label>
                                <input type="url" name="konum_linki" class="form-control" value="{{ $adliye->konum_linki }}">
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="aktif_mi" name="aktif_mi" value="1" {{ $adliye->aktif_mi ? 'checked' : '' }}>
                                <label class="form-check-label" for="aktif_mi">Aktif mi?</label>
                            </div>

                            <div class="">
                                <button type="submit" class="btn btn-success">Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
