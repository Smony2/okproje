@extends('admin.yonetim_master')

@section('title')
    <title>İş Detayı - #{{ $is->id }}</title>
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
        .nav-tabs .nav-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            border: none;
            border-radius: .5rem .5rem 0 0;
            padding: .5rem 1rem;
            transition: background .2s;
            color: #555;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #0056b3;
            background-color: #e7f4ff;
            font-weight: 600;
        }
        .nav-tabs .nav-link:hover {
            background: #f8f9fa;
        }
        .nav-tabs .nav-link iconify-icon {
            font-size: 1.2rem;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .badge {
            font-size: .9rem;
            padding: .4rem .8rem;
        }
    </style>
@endsection

@section('main')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <h6 class="fw-semibold mb-0">İş Detayları</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{route('admin.dashboard')}}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>

        </ul>
    </div>

    <div class="row g-4">
        <!-- SOL SÜTUN -->
        <div class="col-lg-3">
            <!-- TEMEL BİLGİLER -->
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Temel Bilgiler</div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Avukat:</span>
                        <span class="info-value">{{ optional($is->avukat)->username ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Katip:</span>
                        <span class="info-value">{{ optional($is->katip)->username ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Adliye:</span>
                        <span class="info-value">{{ optional($is->adliye)->ad ?? '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">İşlem Türü:</span>
                        <span class="info-value">{{ $is->islem_tipi }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Aciliyet:</span>
                        <span class="info-value">{{ $is->aciliyet }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Durum:</span>
                        <span class="info-value">
                                <span class="badge bg-{{ $is->durum === 'tamamlandı' ? 'success' : ($is->durum === 'iptal' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($is->durum) }}
                                </span>
                            </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Oluşturulma:</span>
                        <span class="info-value">{{ $is->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bitiş:</span>
                        <span class="info-value">{{ optional($is->bitis_tarihi)->format('d.m.Y') ?? '—' }}</span>
                    </div>
                </div>
            </div>

            <!-- AVUKAT DETAYI -->
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Avukat Detayı</div>
                <div class="card-body">
                    @if($is->avukat)
                        <div class="info-row">
                            <span class="info-label">Ad Soyad:</span>
                            <span class="info-value">{{ $is->avukat->name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kullanıcı Adı:</span>
                            <span class="info-value">{{ $is->avukat->username ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $is->avukat->email ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Telefon:</span>
                            <span class="info-value">{{ $is->avukat->phone ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Baro No:</span>
                            <span class="info-value">{{ $is->avukat->baro_no ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Puan:</span>
                            <span class="info-value">{{ number_format($is->avukat->puan, 1) ?? '—' }}</span>
                        </div>
                    @else
                        <p class="text-muted">Avukat bilgisi bulunamadı.</p>
                    @endif
                </div>
            </div>

            <!-- KATİP DETAYI -->
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Katip Detayı</div>
                <div class="card-body">
                    @if($is->katip)
                        <div class="info-row">
                            <span class="info-label">Ad Soyad:</span>
                            <span class="info-value">{{ $is->katip->name ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Kullanıcı Adı:</span>
                            <span class="info-value">{{ $is->katip->username ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $is->katip->email ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Telefon:</span>
                            <span class="info-value">{{ $is->katip->phone ?? '—' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Puan:</span>
                            <span class="info-value">{{ number_format($is->katip->puan, 1) ?? '—' }}</span>
                        </div>
                    @else
                        <p class="text-muted">Katip bilgisi bulunamadı.</p>
                    @endif
                </div>
            </div>



        </div>

        <!-- SAĞ SÜTUN -->
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <!-- SEKMELER -->
                <ul class="nav nav-tabs mb-3" id="isTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#aciklama" type="button">
                            <iconify-icon icon="mdi:text-box-outline"></iconify-icon>
                            Açıklama
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#teklifler" type="button">
                            <iconify-icon icon="mdi:offer"></iconify-icon>
                            Teklifler
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dosyalar" type="button">
                            <iconify-icon icon="mdi:file-document-outline"></iconify-icon>
                            Teslim Edilen Dosyalar
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#puanlar" type="button">
                            <iconify-icon icon="mdi:star-outline"></iconify-icon>
                            Puan / Yorum
                        </button>
                    </li>
                </ul>

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

                    <div class="tab-content">
                        <!-- AÇIKLAMA TAB -->
                        <div class="tab-pane fade show active" id="aciklama">
                            <div class="card-body">
                                <p>{{ $is->aciklama ?: 'Açıklama yok.' }}</p>
                            </div>
                        </div>

                        <!-- TEKLİFLER TAB -->
                        <div class="tab-pane fade" id="teklifler">
                            <div class="card-body">
                                @forelse($is->teklifler as $teklif)
                                    <div class="border-bottom py-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ optional($teklif->katip)->username ?? 'Bilinmeyen' }}</strong> - {{ $teklif->jeton }} Jeton
                                            <br>
                                            <small class="text-muted">{{ $teklif->created_at->format('d.m.Y H:i') }}</small>
                                            @if($teklif->mesaj)
                                                <p class="mt-1 mb-0"><em>{{ $teklif->mesaj }}</em></p>
                                            @endif
                                        </div>
                                        <span class="badge bg-{{ $teklif->durum === 'kabul' ? 'success' : ($teklif->durum === 'reddedildi' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($teklif->durum) }}
                                            </span>
                                    </div>
                                @empty
                                    <p class="text-muted">Bu işe henüz teklif verilmemiş.</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- TESLİM EDİLEN DOSYALAR TAB -->
                        <div class="tab-pane fade" id="dosyalar">
                            <div class="card-body">
                                <p class="text-muted">Henüz teslim edilmiş dosya yok.</p>
                                <!-- Dosya yükleme veya listeleme için gerekli kod buraya eklenebilir -->
                            </div>
                        </div>

                        <!-- PUAN / YORUM TAB -->
                        <div class="tab-pane fade" id="puanlar">
                            <div class="card-body">
                                <h6>Avukat Yorumu</h6>
                                @forelse($is->avukatPuanlar as $puan)
                                    <div class="border-bottom py-2">
                                        <div>
                                            {{ str_repeat('★', $puan->puan) . str_repeat('☆', 5 - $puan->puan) }}
                                            <small class="text-muted ms-2">{{ $puan->created_at->format('d.m.Y') }}</small>
                                        </div>
                                        <p class="mb-0">{{ $puan->yorum ?: '—' }}</p>
                                        <small class="text-muted">Veren: {{ $puan->veren_tipi }}</small>
                                    </div>
                                @empty
                                    <p class="text-muted">Henüz değerlendirme yapılmamış.</p>
                                @endforelse
                                <hr>
                                <br>
                                <h6>Katip Yorumu</h6>

                            @forelse($is->katipPuanlar as $puan)
                                    <div class="border-bottom py-2">
                                        <div>
                                            {{ str_repeat('★', $puan->puan) . str_repeat('☆', 5 - $puan->puan) }}
                                            <small class="text-muted ms-2">{{ $puan->created_at->format('d.m.Y') }}</small>
                                        </div>
                                        <p class="mb-0">{{ $puan->yorum ?: '—' }}</p>
                                        <small class="text-muted">Veren: {{ $puan->veren_tipi }}</small>
                                    </div>
                                @empty
                                    <p class="text-muted">Henüz değerlendirme yapılmamış.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
