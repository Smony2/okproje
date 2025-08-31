@extends('admin.yonetim_master')

@section('title')
    <title>Katip Kazançları</title>
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
        <h6 class="fw-bold mb-0">Katip Kazançları</h6>
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
                <div class="card-header fw-semibold">Son Katip Kazançları</div>
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

                    @forelse($kazanc as $item)
                        <div class="review-item">
                            <!-- Katip Bilgileri -->
                            <div class="d-flex align-items-center user-info">
                                @if($item->katip && optional($item->katip)->avatar)
                                    <img src="{{ asset(optional($item->katip->avatar)->path) }}" class="avatar" alt="Katip Avatar">
                                @else
                                    <div class="avatar-initial">{{ substr(optional($item->katip)->name ?? 'K', 0, 1) }}</div>
                                @endif
                                <div>
                                    <strong>{{ optional($item->katip)->username ?? 'Bilinmeyen Katip' }}</strong>
                                    <small class="text-muted d-block">Katip</small>
                                </div>
                            </div>

                            <!-- Tutar -->
                            <div style="width: 20%;">
                                <span>{{ number_format($item->amount, 2, ',', '.') }} ₺</span>
                            </div>

                            <!-- Açıklama -->
                            <div style="width: 40%;">
                                <p class="mb-0">{{ $item->description ?: '—' }}</p>
                            </div>

                            <!-- Tarih -->
                            <div style="width: 20%;">
                                <small class="text-muted">{{ $item->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Henüz kazanç kaydı bulunamadı.</p>
                    @endforelse
                </div>
            </div>

            @if($kazanc->hasPages())
                <div class="mt-3">
                    {{ $kazanc->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
