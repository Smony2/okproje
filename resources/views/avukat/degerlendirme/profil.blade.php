@extends('avukat.layout.avukat_master')

@section('title')
    <title>Kâtip Profili | {{ $katip->username }}</title>
@endsection

@section('cssler')
    <style>
        .star{color:#ffc107;font-size:1.1rem}
        .star.empty{color:#e2e2e2}
        .avatar-initial{display:flex;align-items:center;justify-content:center;background:#4A90E2;color:#fff}
    </style>
    <style>
        /* avatar dairesi */
        .cmt-avatar{
            width:44px;height:44px;display:flex;align-items:center;justify-content:center;
            border-radius:50%;font-weight:600;color:#fff;background:#4A90E2;
        }
        /* yorum kartı */
        .cmt-card{
            background:#fff;border:1px solid #eaeaea;border-radius:8px;padding:12px 16px;
            box-shadow:0 2px 6px rgba(0,0,0,.04);transition:.2s;
        }
        .cmt-card:hover{box-shadow:0 4px 10px rgba(0,0,0,.08)}
        .cmt-stars{color:#ffc107;font-size:.95rem}
        .cmt-stars .empty{color:#e4e4e4}
        @media (min-width:768px){
            .cmt-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        }
    </style>
@endsection

@section('main')
    <div class="row g-4">
        {{-- PROFİL ÖZETİ --}}
        {{-- === SOL SÜTUN – 3 KARTLI YAPI === --}}
        <div class="col-lg-3">
            {{-- 1. PROFİL ÖZETİ --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <div class="avatar-initial w-100-px h-100-px rounded-circle mx-auto mb-3"
                         style="font-size:2rem;">
                        {{ strtoupper(substr($katip->username, 0, 1)) }}
                    </div>

                    <h5 style="" class="fw-bold mb-1">{{ $katip->username }}</h5>

                    @php
                        $puan = number_format($avukatOrtalamaPuan, 1);   // Avukatların ortalama puanı
                    @endphp

                    <div class="d-flex justify-content-center align-items-center my-2">
                        <span style="font-size: 19px" class="text-warning-600 line-height-1"><iconify-icon icon="material-symbols:star"></iconify-icon></span>
                        <span style="color: #FF9F29" class="fw-semibold">{{ $puan }}</span>
                        <span class="text-muted ms-1">({{ $avukatYorumSayisi }})</span>
                    </div>
                </div>
            </div>

            {{-- 2. KİŞİSEL & İLETİŞİM --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Kişisel Bilgiler</div>
                <div class="card-body py-40">
                    <ul class="">
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">İl</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $katip->il ?? '—' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Üyelik tarihi</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $katip->created_at->format('d.m.Y') }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Doğum</span>
                            <span class="w-70 text-secondary-light fw-medium">:
                                {{ optional($katip->dogum_tarihi)->format('d.m.Y') ?? '—' }}
                            </span>
                        </li>
                        <li class="d-flex align-items-center gap-1">
                            <span class="w-30 text-md fw-semibold text-primary-light">Cinsiyet</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $katip->cinsiyet ?? '—' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- 3. MESLEKİ BİLGİLER --}}
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Mesleki Bilgiler</div>
                <div class="card-body py-40">
                    <ul>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Uzmanlık</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $katip->uzmanlik_alani ?? '—' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Unvan</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $katip->unvan ?? '—' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Mezuniyet</span>
                            <span class="w-70 text-secondary-light fw-medium">
                                {{ $katip->mezuniyet_okulu }}
                                {{ $katip->mezuniyet_yili ? "({$katip->mezuniyet_yili})" : '' }}
                            </span>
                        </li>
                        <li class="d-flex align-items-center gap-1">
                            <span class="w-30 text-md fw-semibold text-primary-light">Toplam İş</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $katip->isler->count() }} iş</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            {{-- GÖREV ALDIĞI ADLİYELER --}}
            <div class="card mb-20">
                <div class="card-header fw-bold">Görev Aldığı Adliyeler</div>
                <div class="card-body">
                    @forelse($katip->adliyeler as $ad)
                        <span class="badge bg-primary-400 me-1 mb-1">{{ $ad->ad }}</span>
                    @empty
                        <p class="text-muted mb-0">Kayıtlı adliye yok.</p>
                    @endforelse
                </div>
            </div>

            {{-- TAMAMLANAN İŞLER --}}
            <div class="card mb-20">
                <div class="card-header fw-bold">Tamamlanan Son İşleri</div>
                <div class="card-body p-0">
                    @if($katip->isler->isEmpty())
                        <p class="text-muted p-3 mb-0">Tamamlanmış iş bulunamadı.</p>
                    @else
                        <div class="table-responsive p-3 table-bordered">
                            <table class="table bordered-table mb-0">
                                <thead class="">
                                <tr>
                                    <th>İd</th><th>İşlem</th><th>Adliye</th>
                                    <th>Durum</th>
                                    <th>Detay</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($katip->isler as $is)
                                    <tr>
                                        <td>{{ $is->id }}</td>
                                        <td>{{ $is->islem_tipi }}</td>
                                        <td>{{ $is->adliye->ad ?? '-' }}</td>
                                        <td class="">
                                            @php
                                                $durumMap = [
                                                    'bekliyor'      => 'status-btn waiting',
                                                    'devam ediyor'  => 'status-btn ongoing',
                                                    'tamamlandi'    => 'status-btn completed',
                                                    'iptal'         => 'status-btn cancelled',
                                                ];
                                                $durumClass = $durumMap[$is->durum] ?? 'status-btn';
                                            @endphp
                                            <span class="{{ $durumClass }}">
                                                            {{ ucfirst($is->durum) }}
                                                        </span>
                                        </td>
                                        <td style="">
                                            <a href="{{ route('avukat.isler.detay', $is->id) }}"
                                               class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <iconify-icon icon="iconamoon:eye-light" class="icon"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- AVUKAT YORUMLARI --}}
            <div class="card mb-20">
                <div class="card-header fw-bold">Avukat Yorumları ({{ $avukatYorumSayisi }})</div>
                <div class="card-body">
                    <div class="cmt-grid">
                        @forelse($avukatPuanlar as $p)
                            <div class="cmt-card">
                                <div class="d-flex align-items-start">
                                    @php
                                        $init = strtoupper(substr($p->avukat->username ?? 'A', 0, 1));
                                    @endphp
                                    <div class="cmt-avatar me-3">{{ $init }}</div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-semibold">{{ $p->avukat->username ?? 'Anonim Avukat' }}</span>
                                            <small class="text-muted">{{ $p->created_at->format('d.m.Y H:i') }}</small>
                                        </div>

                                        <div class="cmt-stars mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $p->puan ? '' : 'empty' }}">★</span>
                                            @endfor
                                            <span class="text-muted ms-1">({{ $p->puan }}/5)</span>
                                        </div>

                                        <p class="mb-0 text-secondary">
                                            {{ $p->yorum ?: '—' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Henüz avukat yorumu yok.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KÂTİP YORUMLARI --}}
            <div class="card">
                <div class="card-header fw-bold">Kâtip Yorumları ({{ $katipYorumSayisi }})</div>
                <div class="card-body">
                    <div class="cmt-grid">
                        @forelse($katipPuanlar as $p)
                            <div class="cmt-card">
                                <div class="d-flex align-items-start">
                                    @php
                                        $init = strtoupper(substr($p->katip->username ?? 'K', 0, 1));
                                    @endphp
                                    <div class="cmt-avatar me-3">{{ $init }}</div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-semibold">{{ $p->katip->username ?? 'Anonim Kâtip' }}</span>
                                            <small class="text-muted">{{ $p->created_at->format('d.m.Y H:i') }}</small>
                                        </div>

                                        <div class="cmt-stars mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="{{ $i <= $p->puan ? '' : 'empty' }}">★</span>
                                            @endfor
                                            <span class="text-muted ms-1">({{ $p->puan }}/5)</span>
                                        </div>

                                        <p class="mb-0 text-secondary">
                                            {{ $p->yorum ?: '—' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Henüz kâtip yorumu yok.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
