@extends('admin.yonetim_master')

@section('title')
    <title>Admin Paneli | Adliye Dijital Takip Sistemi</title>
@endsection

@section('cssler')
    <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">
    <style>
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .status-btn {
            padding: 4px 12px;
            border-radius: 16px;
            font-weight: 500;
            font-size: 0.85rem;
            text-transform: capitalize;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border: none;
            cursor: default;
            display: inline-block;
        }
        .custom-btn {
            background: #3b82f6;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 6px 16px;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: capitalize;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        .custom-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
            color: #ffffff;
        }
        .text-muted {
            color: #6b7280 !important;
            font-size: 0.9rem;
        }
        .header-title {
            font-size: 1.2rem;
            font-weight: 600;
        }
        .card {
            border-radius: 16px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .card-header {
            border-radius: 16px 16px 0 0 !important;
        }
        .card-header .d-flex {
            align-items: center;
            gap: 16px;
        }
        .card-header h6 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
        }
        .card-header a {
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
        }
        .card-body {
            padding: 24px;
        }
        .chart-container {
            position: relative;
            min-height: 300px;
            margin-bottom: 20px;
        }
    </style>
@endsection

@section('main')
    <div class="row">
        <div class="col-xxl-12 col-sm-6">
            <div class="row">
                <!-- 1. Toplam Avukat -->
                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                        <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="mdi:account-multiple-outline" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam Avukat</span>
                                        <h6 class="fw-semibold my-1">{{ $toplamAvukat }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Toplam Katip -->
                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                         <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="mdi:account-group" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam Katip</span>
                                        <h6 class="fw-semibold my-1">{{ $toplamKatip }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Aktif İşler -->
                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                        <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="mdi:briefcase-outline" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Aktif İşler</span>
                                        <h6 class="fw-semibold my-1">{{ $aktifIsler }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Tamamlanan İşler -->
                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                        <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="mdi:check-circle-outline" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Tamamlanan İşler</span>
                                        <h6 class="fw-semibold my-1">{{ $tamamlananIsler }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. İptal Edilen İşler -->
                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                        <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="mdi:close-circle" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">İptal Edilen İşler</span>
                                        <h6 class="fw-semibold my-1">{{ $iptalEdilenIsler }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                     <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam Harcama</span>
                                        <h6 class="fw-semibold my-1">{{ $iptalEdilenIsler }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                     <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam Harcama</span>
                                        <h6 class="fw-semibold my-1">{{ $iptalEdilenIsler }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6 col-sm-6 mt-10">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                     <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                            <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam Harcama</span>
                                        <h6 class="fw-semibold my-1">{{ $iptalEdilenIsler }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-24">
        <div class="row gy-4">
            <div class="col-xl-8">
                <div class="row gy-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-semibold text-lg">Son İşler</h6>
                                    <a href="" class="text-secondary-600 hover-text-primary d-flex align-items-center gap-1">
                                        Tümünü Göster
                                        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">İşlem No</th>
                                            <th scope="col">Katip</th>
                                            <th scope="col">Avukat</th>
                                            <th scope="col">Adliye</th>
                                            <th scope="col" class="">Durum</th>
                                            <th scope="col">Detay</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sonIsler as $is)
                                            <tr>
                                                <td>#{{ $is->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($is->katip)
                                                            <img src="{{ asset($is->katip->avatar->path) }}"
                                                                 alt="{{ $is->katip->username }}"
                                                                 class="avatar">
                                                        @else
                                                            <span></span>
                                                        @endif
                                                        <span class="text-md text-secondary-light fw-semibold flex-grow-1">
                                                                <a href="{{ route('admin.katipler.show', $is->katip->id) }}"
                                                                   class="text-decoration-none text-dark hover-text-primary">
                                                                    {{ $is->katip->username }}
                                                                </a>
                                                            </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($is->avukat && $is->avukat->avatar->path)
                                                            <img src="{{ asset($is->avukat->avatar->path) }}"
                                                                 alt="{{ $is->avukat->username }}"
                                                                 class="avatar">
                                                        @else
                                                            <span></span>
                                                        @endif
                                                        <span class="text-md text-secondary-light fw-semibold flex-grow-1">
                                                                <a href="{{ route('admin.avukatlar.show', $is->avukat->id) }}"
                                                                   class="text-decoration-none text-dark hover-text-primary">
                                                                    {{ $is->avukat->username }}
                                                                </a>
                                                            </span>
                                                    </div>
                                                </td>
                                                <td>{{ $is->adliye->ad ?? '-' }}</td>
                                                <td class="text-left">
                                                    @php
                                                        $durumMap = [
                                                            'bekliyor'      => 'badge bg-primary',
                                                            'devam ediyor'  => 'badge bg-warning-600',
                                                            'tamamlandi'    => 'badge bg-success',
                                                            'iptal'         => 'badge bg-danger',
                                                        ];
                                                        $durumClass = $durumMap[$is->durum] ?? 'status-btn';
                                                    @endphp
                                                    <span class="{{ $durumClass }}">
                                                            {{ ucfirst($is->durum) }}
                                                        </span>
                                                </td>
                                                <td style="text-align: center">
                                                    <a href="{{ route('admin.isler.show', $is->id) }}"
                                                       class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="iconamoon:eye-light" class="icon"></iconify-icon>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">Henüz iş bulunamadı.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">Son Teklifler</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">Katip</th>
                                            <th scope="col">Adliye</th>
                                            <th scope="col">İşlem Türü</th>
                                            <th scope="col">Tarih</th>
                                            <th scope="col">Jeton</th>
                                            <th scope="col">Durum</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sonTeklifler as $teklif)
                                            <tr>
                                                <td>{{ $teklif->katip->username ?? 'Katip Yok' }}</td>
                                                <td>{{ optional($teklif->isleri->adliye)->ad ?? 'Adliye Yok' }}</td>
                                                <td>{{ $teklif->isleri->islem_tipi ?? '-' }}</td>
                                                <td>{{ $teklif->created_at->locale('tr')->translatedFormat('d F Y H:i') }}</td>
                                                <td>{{ $teklif->jeton }}</td>
                                                <td>
                                                    @php
                                                        $durumMap = [
                                                            'bekliyor' => ['text-warning-600', 'bg-warning-100'],
                                                            'kabul' => ['text-success-600', 'bg-success-100'],
                                                            'reddedildi' => ['text-danger-600', 'bg-danger-100'],
                                                        ];
                                                        [$text, $bg] = $durumMap[$teklif->durum] ?? ['text-secondary', 'bg-secondary'];
                                                    @endphp
                                                    <span class="text-xs fw-medium {{ $text }} {{ $bg }} rounded-pill px-3">
                                                            {{ ucfirst($teklif->durum) }}
                                                        </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">Henüz teklif verilmemiş.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">Son Yatırımlar</h6>
                                    <a href="{{ route('admin.odeme.index') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                        Tümünü Gör
                                        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">Avukat</th>
                                            <th scope="col">Tarih</th>
                                            <th scope="col">Miktar</th>
                                            <th scope="col">Durum</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sonYatirimlar as $yatirim)
                                            <tr>
                                                <td>{{ $yatirim->avukat->username ?? '-' }}</td>
                                                <td>{{ $yatirim->created_at->locale('tr')->translatedFormat('d F Y H:i') }}</td>
                                                <td>{{ number_format($yatirim->amount, 2, ',', '.') }}</td>
                                                <td>
                                                    @php
                                                        $statusMap = [
                                                            'completed' => ['text-success-600 bg-success-100', 'Onaylandı'],
                                                            'pending' => ['text-warning-600 bg-warning-100', 'Bekliyor'],
                                                            'rejected' => ['text-danger-600 bg-danger-100', 'Reddedildi'],
                                                        ];
                                                        [$statusClass, $statusLabel] = $statusMap[$yatirim->status] ?? ['text-secondary bg-secondary-100', ucfirst($yatirim->status)];
                                                    @endphp
                                                    <span class="text-xs fw-medium rounded-pill px-3 {{ $statusClass }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">Henüz yatırım yok.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">Son Avukat Değerlendirmeleri</h6>
                                    <a href="" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                        Tümünü Gör
                                        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">Veren</th>
                                            <th scope="col">Alan</th>
                                            <th scope="col">Puan</th>
                                            <th scope="col">Yorum</th>
                                            <th scope="col">Tarih</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sonAvukatPuanlari as $p)
                                            <tr>
                                                <td>{{ $p->veren_adi }}</td>
                                                <td>{{ $p->alan_adi }}</td>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <span class="text-warning">
                                                                <iconify-icon icon="mdi:star{{ $i <= $p->puan ? '' : '-outline' }}" style="font-size:1rem"></iconify-icon>
                                                            </span>
                                                    @endfor
                                                </td>
                                                <td>{{ Str::limit($p->yorum ?: '—', 80) }}</td>
                                                <td>{{ $p->created_at->locale('tr')->translatedFormat('d F Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">Henüz avukat değerlendirmesi bulunmamaktadır.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">Son Katip Değerlendirmeleri</h6>
                                    <a href="" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                        Tümünü Gör
                                        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table bordered-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">Veren</th>
                                            <th scope="col">Alan</th>
                                            <th scope="col">Puan</th>
                                            <th scope="col">Yorum</th>
                                            <th scope="col">Tarih</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sonKatipPuanlari as $p)
                                            <tr>
                                                <td>{{ $p->veren_adi }}</td>
                                                <td>{{ $p->alan_adi }}</td>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <span class="text-warning">
                                                                <iconify-icon icon="mdi:star{{ $i <= $p->puan ? '' : '-outline' }}" style="font-size:1rem"></iconify-icon>
                                                            </span>
                                                    @endfor
                                                </td>
                                                <td>{{ Str::limit($p->yorum ?: '—', 80) }}</td>
                                                <td>{{ $p->created_at->locale('tr')->translatedFormat('d F Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">Henüz katip değerlendirmesi bulunmamaktadır.</td>
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

            <div class="col-xl-4">
                <div class="row gy-4">
                    <div class="col-md-12">
                        <div class="card border">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">Aylık Toplam İş</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="monthlyJobsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card h-100">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">Adliyelere Göre İş Dağılımı</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="adliyeChart" height="180"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsler')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxBar = document.getElementById('monthlyJobsChart').getContext('2d');
        const aylikIsler = @json($aylikDizi);

        const backgroundColors = aylikIsler.map(value => value === 0 ? 'rgba(209, 213, 219, 0.3)' : 'rgba(59, 130, 246, 0.8)');
        const borderColors = aylikIsler.map(value => value === 0 ? 'rgba(209, 213, 219, 0.5)' : 'rgba(59, 130, 246, 1)');

        const isAllZero = aylikIsler.every(value => value === 0);

        const monthlyJobsChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'],
                datasets: [{
                    label: 'İş Sayısı',
                    data: aylikIsler,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                    borderRadius: 10,
                    barThickness: 24,
                    maxBarThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#1f2937',
                            font: {
                                size: 14,
                                family: "'Inter', sans-serif",
                                weight: '500'
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(31, 41, 55, 0.9)',
                        titleFont: { size: 14, weight: '600' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 6,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y === 0 ? 'Henüz iş kaydı yok' : `İş Sayısı: ${context.parsed.y}`;
                            }
                        }
                    },
                    subtitle: {
                        display: isAllZero,
                        text: ['Henüz bu yıl iş kaydı bulunmamaktadır.'],
                        color: '#6b7280',
                        font: {
                            size: 13,
                            family: "'Inter', sans-serif",
                            weight: '400'
                        },
                        padding: {
                            bottom: 16,
                            top: 8
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280',
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        suggestedMin: 0,
                        suggestedMax: Math.max(...aylikIsler, 5) + 1,
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            },
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(209, 213, 219, 0.2)',
                            borderDash: [4, 4]
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart'
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    </script>

    <script>
        const adliyeChart = document.getElementById('adliyeChart').getContext('2d');
        new Chart(adliyeChart, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($adliyeDagilimi)),
                datasets: [{
                    label: 'İş Sayısı',
                    data: @json(array_values($adliyeDagilimi)),
                    backgroundColor: [
                        '#06b6d4', '#facc15', '#10b981', '#3b82f6', '#f43f5e'
                    ],
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#1f2937',
                            font: {
                                size: 14,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(31, 41, 55, 0.9)',
                        titleFont: { size: 14 },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    </script>
@endsection
