@extends('admin.yonetim_master')

@section('title')
    <title>Yeni Subscription Paketi</title>
@endsection

@section('main')
    <div class="page-container mt-2">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center mb-0 card-header">
                <h6>Yeni Paket Oluştur</h6>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">Geri Dön</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.subscriptions.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ad</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Fiyat (₺)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Aylık Fiyat (₺)</label>
                            <input type="number" step="0.01" name="monthly_price" class="form-control" value="{{ old('monthly_price') }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Süre (Gün)</label>
                            <input type="number" name="duration_days" class="form-control" required min="1" value="{{ old('duration_days', 30) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sıralama</label>
                            <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', 0) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Maksimum İş</label>
                            <input type="number" name="max_jobs" class="form-control" min="1" placeholder="Sınırsız için boş bırakın" value="{{ old('max_jobs') }}">
                        </div>
                        

                        <div class="col-12">
                            <label class="form-label">Açıklama</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="priority_support" value="1" {{ old('priority_support') ? 'checked' : '' }}>
                                <label class="form-check-label">Öncelikli Destek</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="advanced_analytics" value="1" {{ old('advanced_analytics') ? 'checked' : '' }}>
                                <label class="form-check-label">Gelişmiş Analitik</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="custom_branding" value="1" {{ old('custom_branding') ? 'checked' : '' }}>
                                <label class="form-check-label">Özel Markalama</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


