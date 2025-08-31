{{-- resources/views/katip/degerlendirme/profil.blade.php --}}
@extends('katip.layout.katip_master')

@section('title')
    <title>Avukat Profili | {{ $avukat->username }}</title>
@endsection

@section('cssler')
    <style>
        /* yıldızlar */
        .star         { color:#ffc107; font-size:1.1rem }
        .star.empty   { color:#e2e2e2 }
        /* baş harfli avatar */
        .avatar-initial{
            display:flex;align-items:center;justify-content:center;
            background:#4A90E2;color:#fff
        }

        /* yorum listesi */
        .cmt-avatar{
            width:44px;height:44px;border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            background:#4A90E2;color:#fff;font-weight:600
        }
        .cmt-card{
            background:#fff;border:1px solid #eaeaea;border-radius:8px;
            padding:12px 16px;box-shadow:0 2px 6px rgba(0,0,0,.04);
            transition:.2s
        }
        .cmt-card:hover{ box-shadow:0 4px 10px rgba(0,0,0,.08) }
        .cmt-stars        { color:#ffc107;font-size:.95rem }
        .cmt-stars .empty { color:#e4e4e4 }

        @media (min-width:768px){
            .cmt-grid{ display:grid;grid-template-columns:1fr 1fr;gap:16px }
        }
    </style>
@endsection


@section('main')
    <div class="row g-4">

        {{-- ------------- SOL SÜTUN (Özet + Bilgiler) ------------- --}}
        <div class="col-lg-3">

            {{-- PROFİL ÖZET KARTI --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <div class="avatar-initial w-100-px h-100-px rounded-circle mx-auto mb-3"
                         style="font-size:2rem;">
                        {{ strtoupper(substr($avukat->username,0,1)) }}
                    </div>

                    <h5 class="fw-bold mb-1">{{ $avukat->username }}</h5>

                    @php $puan = number_format($ortalamaPuan,1); @endphp

                    <div class="d-flex justify-content-center align-items-center my-2">
                    <span class="text-warning-600 line-height-1" style="font-size:19px">
                        <iconify-icon icon="material-symbols:star"></iconify-icon>
                    </span>
                        <span class="fw-semibold" style="color:#FF9F29">{{ $puan }}</span>
                        <span class="text-muted ms-1">({{ $yorumSayisi }})</span>
                    </div>
                </div>
            </div>

            {{-- KİŞİSEL BİLGİLER --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Kişisel Bilgiler</div>
                <div class="card-body py-40">
                    <ul>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 fw-semibold text-primary-light">İl</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $avukat->il ?? '—' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 fw-semibold text-primary-light">Üyelik</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $avukat->created_at }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 fw-semibold text-primary-light">Doğum</span>
                            <span class="w-70 text-secondary-light fw-medium">:
                            {{ optional($avukat->dogum_tarihi)->format('d.m.Y') ?? '—' }}
                        </span>
                        </li>
                        <li class="d-flex align-items-center gap-1">
                            <span class="w-30 fw-semibold text-primary-light">Cinsiyet</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $avukat->cinsiyet ?? '—' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- MESLEKİ BİLGİLER --}}
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Mesleki Bilgiler</div>
                <div class="card-body py-40">
                    <ul>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 fw-semibold text-primary-light">Uzmanlık</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $avukat->uzmanlik_alani ?? '—' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 fw-semibold text-primary-light">Baro</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $avukat->baro ?? '—' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 fw-semibold text-primary-light">Mezuniyet</span>
                            <span class="w-70 text-secondary-light fw-medium">:
                            {{ $avukat->mezuniyet_okulu }}
                                {{ $avukat->mezuniyet_yili ? "({$avukat->mezuniyet_yili})" : '' }}
                        </span>
                        </li>
                        <li class="d-flex align-items-center gap-1">
                            <span class="w-30 fw-semibold text-primary-light">Toplam İş</span>
                            <span class="w-70 text-secondary-light fw-medium">:
                            {{ $avukat->isler->count() }} iş
                        </span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
        {{-- ----------- /SOL SÜTUN ----------- --}}


        {{-- ------------- SAĞ SÜTUN (Detaylar) ------------- --}}
        <div class="col-lg-9">

            {{-- KATİPLERLE TAMAMLANAN İŞLER --}}
            <div class="card mb-20">
                <div class="card-header fw-bold">Tamamlanan Son İşleri</div>
                <div class="card-body p-0">
                    @if($avukat->isler->isEmpty())
                        <p class="text-muted p-3 mb-0">Tamamlanmış iş bulunamadı.</p>
                    @else
                        <div class="table-responsive p-3 table-bordered">
                            <table class="table table-sm mb-0 align-middle">
                                <thead>
                                <tr>
                                    <th>#</th><th>İşlem</th><th>Katip</th>
                                    <th>Adliye</th><th>Bitiş</th><th>Ücret (₺)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($avukat->isler as $is)
                                    <tr>
                                        <td>{{ $is->id }}</td>
                                        <td>{{ $is->islem_tipi }}</td>
                                        <td>{{ $is->katip->username ?? '-' }}</td>
                                        <td>{{ $is->adliye->ad ?? '-' }}</td>
                                        <td>{{ optional($is->is_tamamlandi_at)->format('d.m.Y') }}</td>
                                        <td>{{ number_format($is->ucret,2,',','.') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KATİP YORUMLARI --}}
            <div class="card">
                <div class="card-header fw-bold">Katip Yorumları ({{ $yorumSayisi }})</div>
                <div class="card-body">
                    <div class="cmt-grid">
                        @forelse($tumPuanlar as $p)
                            <div class="cmt-card">
                                <div class="d-flex align-items-start">
                                    @php
                                        $kat = $p->veren_id ? \App\Models\Katip::find($p->veren_id) : null;
                                        $init = strtoupper(substr($kat->username ?? 'K',0,1));
                                    @endphp
                                    <div class="cmt-avatar me-3">{{ $init }}</div>

                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="fw-semibold">{{ $kat->username ?? 'Anonim Katip' }}</span>
                                            <small class="text-muted">{{ $p->created_at->format('d.m.Y H:i') }}</small>
                                        </div>

                                        <div class="cmt-stars mb-1">
                                            @for($i=1;$i<=5;$i++)
                                                <span class="{{ $i <= $p->puan ? '' : 'empty' }}">★</span>
                                            @endfor
                                            <span class="text-muted ms-1">({{ $p->puan }}/5)</span>
                                        </div>

                                        <p class="mb-0 text-secondary">{{ $p->yorum ?: '—' }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Henüz yorum yok.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
        {{-- ----------- /SAĞ SÜTUN ----------- --}}
    </div>
@endsection
