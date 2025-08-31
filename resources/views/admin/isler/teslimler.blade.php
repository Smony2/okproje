@extends('admin.yonetim_master')

@section('title')
    <title>İş Teslimleri</title>
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
        .btn-sm {
            font-size: .8rem;
            padding: .3rem .6rem;
        }
    </style>
@endsection

@section('main')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <h6 class="fw-bold mb-0">İş Teslimleri</h6>
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
                <div class="card-header fw-semibold">Katipler Tarafından Yapılan Teslimatlar</div>
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

                    @forelse($teslimler as $teslim)
                        <div class="review-item">
                            <!-- Katip Bilgileri -->
                            <div class="d-flex align-items-center user-info">
                                @if($teslim->katip && optional($teslim->katip)->avatar)
                                    <img src="{{ asset(optional($teslim->katip->avatar)->path) }}" class="avatar" alt="Katip Avatar">
                                @else
                                    <div class="avatar-initial">{{ substr(optional($teslim->katip)->name ?? 'K', 0, 1) }}</div>
                                @endif
                                <div>
                                    <strong>{{ optional($teslim->katip)->username ?? 'Bilinmeyen Katip' }}</strong>
                                    <small class="text-muted d-block">Katip</small>
                                </div>
                            </div>

                            <!-- Avukat Bilgileri -->
                            <div class="d-flex align-items-center user-info">
                                @if($teslim->isleri && optional($teslim->isleri)->avukat && optional($teslim->isleri->avukat)->avatar)
                                    <img src="{{ asset(optional($teslim->isleri->avukat->avatar)->path) }}" class="avatar" alt="Avukat Avatar">
                                @else
                                    <div class="avatar-initial">{{ substr(optional(optional($teslim->isleri)->avukat)->name ?? 'A', 0, 1) }}</div>
                                @endif
                                <div>
                                    @if($teslim->isleri && optional($teslim->isleri)->avukat)
                                        <strong>{{ optional($teslim->isleri->avukat)->username ?? 'Bilinmeyen Avukat' }}</strong>
                                        <small class="text-muted d-block">Avukat</small>
                                    @else
                                        <strong class="text-muted">Avukat Atanmamış</strong>
                                        <small class="text-muted d-block">Avukat</small>
                                    @endif
                                </div>
                            </div>

                            <!-- İş ve İşlem Türü -->
                            <div style="width: 20%;">
                                <p class="mb-0">
                                    İş: #{{ $teslim->is_id }} - {{ optional($teslim->isleri)->islem_tipi ?? '—' }}
                                </p>
                            </div>

                            <!-- Dosya -->
                            <div style="width: 15%;">
                                @if($teslim->dosya)
                                    <a href="{{ asset($teslim->dosya) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        Görüntüle
                                    </a>
                                @else
                                    <span class="text-muted">Dosya yok</span>
                                @endif
                            </div>

                            <!-- Açıklama -->
                            <div style="width: 20%;">
                                <p class="mb-0">{{ $teslim->aciklama ?: '—' }}</p>
                            </div>

                            <!-- Tarih -->
                            <div style="width: 15%;">
                                <small class="text-muted">{{ $teslim->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Henüz teslimat yapılmamış.</p>
                    @endforelse
                </div>
            </div>

            @if($teslimler->hasPages())
                <div class="mt-3">
                    {{ $teslimler->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
