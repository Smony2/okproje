@extends('admin.yonetim_master')

@section('title')
    <title>Katip Değerlendirmeleri</title>
@endsection

@section('cssler')
    <style>
        .card {
            border: none;
            border-radius: .5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e7f4ff;
            font-weight: 600;
            color: #333;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        .avatar-initial {
            width: 40px;
            height: 40px;
            font-size: 1rem;
            background: #4A90E2;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 10px;
        }
        .review-item {
            border-bottom: 1px solid #e7f4ff;
            padding: 15px 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .review-item:last-child {
            border-bottom: none;
        }
        .stars {
            color: #FFD700;
            font-size: 1rem;
        }
        .text-muted {
            color: #6c757d !important;
        }
        .badge {
            font-size: .9rem;
            padding: .4rem .8rem;
        }
        .user-info {
            min-width: 200px;
        }
    </style>
@endsection

@section('main')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <h6 class="fw-bold mb-0">Katip Değerlendirmeleri</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
        </ul>
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Son Katip Değerlendirmeleri</div>
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

                    @forelse($puanlar as $puan)
                        <div class="review-item">
                            <!-- Değerlendiren (Yapan Avukat) -->
                            <div class="d-flex align-items-center user-info">
                                @if($puan->islem->avukat && $puan->islem->avukat->avatar)
                                    <img src="{{ asset($puan->islem->avukat->avatar->path) }}" class="avatar" alt="Avukat Avatar">
                                @else
                                    <div class="avatar-initial">{{ substr(optional($puan->islem->avukat)->name ?? 'A', 0, 1) }}</div>
                                @endif
                                <div>
                                    <strong>{{ optional($puan->islem->avukat)->username ?? 'Bilinmeyen Avukat' }}</strong>
                                    <small class="text-muted d-block">Değerlendiren Avukat</small>
                                </div>
                            </div>

                            <!-- Değerlendirilen (Yapılan Katip) -->
                            <div class="d-flex align-items-center user-info">
                                @if($puan->islem->katip && $puan->islem->katip->avatar)
                                    <img src="{{ asset($puan->islem->katip->avatar->path) }}" class="avatar" alt="Katip Avatar">
                                @else
                                    <div class="avatar-initial">{{ substr(optional($puan->islem->katip)->name ?? 'K', 0, 1) }}</div>
                                @endif
                                <div>
                                    <strong>{{ optional($puan->islem->katip)->username ?? 'Bilinmeyen Katip' }}</strong>
                                    <small class="text-muted d-block">Değerlendirilen Katip</small>
                                </div>
                            </div>

                            <!-- Puan -->
                            <div style="width: 15%;">
                                <span class="stars">{{ str_repeat('★', $puan->puan) . str_repeat('☆', 5 - $puan->puan) }}</span>
                            </div>

                            <!-- Yorum -->
                            <div style="width: 30%;">
                                <p class="mb-0">{{ $puan->yorum ?: '—' }}</p>
                                <small class="text-muted">İş: #{{ $puan->islem->id }} - {{ $puan->islem->islem_tipi }}</small>
                            </div>

                            <!-- Tarih -->
                            <div style="width: 15%;">
                                <small class="text-muted">{{ $puan->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Henüz değerlendirme bulunamadı.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
