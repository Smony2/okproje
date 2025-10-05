@extends('admin.yonetim_master')

@section('title')
    <title>Subscription Paketleri | Admin Paneli</title>
@endsection

@section('main')
    <div class="page-container mt-2">
        <div class="card">
            <div class="d-flex justify-content-between align-items-center mb-0 card-header">
                <h6>Subscription Paketleri</h6>
                <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">Yeni Paket Ekle</a>
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

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad</th>
                            <th>Fiyat</th>
                            <th>Süre (gün)</th>
                            <th>Max İş</th>
                            
                            <th>Aktif</th>
                            <th>Sıra</th>
                            <th>İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($subscriptions as $subscription)
                            <tr>
                                <td>{{ $subscription->id }}</td>
                                <td>{{ $subscription->name }}</td>
                                <td>{{ $subscription->formatted_price }}</td>
                                <td>{{ $subscription->duration_days }}</td>
                                <td>{{ $subscription->max_jobs ?? 'Sınırsız' }}</td>
                                
                                <td>
                                    <span class="badge {{ $subscription->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $subscription->is_active ? 'Aktif' : 'Pasif' }}
                                    </span>
                                </td>
                                <td>{{ $subscription->sort_order }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn btn-sm btn-info">Göster</a>
                                    <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="btn btn-sm btn-warning">Düzenle</a>

                                    <form action="{{ route('admin.subscriptions.toggle', $subscription) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm {{ $subscription->is_active ? 'btn-secondary' : 'btn-success' }}">
                                            {{ $subscription->is_active ? 'Pasifleştir' : 'Aktifleştir' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Henüz paket bulunmuyor.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $subscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


