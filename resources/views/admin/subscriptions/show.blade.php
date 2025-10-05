@extends('admin.yonetim_master')

@section('title')
    <title>Paket Detayı</title>
@endsection

@section('main')
    <div class="page-container mt-2">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center mb-0 card-header">
                <h6>Paket Detayı</h6>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">Geri Dön</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p><strong>Ad:</strong> {{ $subscription->name }}</p>
                        <p><strong>Fiyat:</strong> {{ $subscription->formatted_price }}</p>
                        <p><strong>Süre:</strong> {{ $subscription->duration_days }} gün</p>
                        <p><strong>Max İş:</strong> {{ $subscription->max_jobs ?? 'Sınırsız' }}</p>
                        
                        <p><strong>Durum:</strong>
                            <span class="badge {{ $subscription->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $subscription->is_active ? 'Aktif' : 'Pasif' }}
                            </span>
                        </p>
                        <p><strong>Özellikler:</strong></p>
                        <ul>
                            <li>Öncelikli Destek: {{ $subscription->priority_support ? 'Var' : 'Yok' }}</li>
                            <li>Gelişmiş Analitik: {{ $subscription->advanced_analytics ? 'Var' : 'Yok' }}</li>
                            <li>Özel Markalama: {{ $subscription->custom_branding ? 'Var' : 'Yok' }}</li>
                        </ul>
                        <p><strong>Sıra:</strong> {{ $subscription->sort_order }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Bu Paketi Kullanan Avukatlar</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ad</th>
                                    <th>Durum</th>
                                    <th>Bitiş</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($subscription->avukats as $avukat)
                                    <tr>
                                        <td>{{ $avukat->id }}</td>
                                        <td>{{ $avukat->name }}</td>
                                        <td>
                                            <span class="badge {{ $avukat->subscription_is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $avukat->subscription_status }}
                                            </span>
                                        </td>
                                        <td>{{ optional($avukat->subscription_end_date)->format('d.m.Y H:i') ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted text-center">Bu paketi kullanan avukat yok.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


