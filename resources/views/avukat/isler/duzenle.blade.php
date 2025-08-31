@extends('avukat.layout.avukat_master')

@section('title')
    <title>İşi Düzenle | Avukat Paneli</title>
@endsection

@section('main')
    <div class="container mt-4">
        <h4 class="mb-4">İşi Düzenle</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('avukat.isler.guncelle', $is->id) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">İşlem Açıklaması</label>
                        <textarea name="aciklama" class="form-control" rows="5" required>{{ old('aciklama', $is->aciklama) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('avukat.isler.detay', $is->id) }}" class="btn btn-outline-secondary">← Geri Dön</a>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
