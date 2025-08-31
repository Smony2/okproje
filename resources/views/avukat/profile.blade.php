@extends('avukat.layout.avukat_master')

@section('title')
    <title>Profil Ayarları | {{ $avukat->name }}</title>
@endsection

@section('cssler')
    <style>
        .avatar-initial {
            width: 100px;
            height: 100px;
            font-size: 2rem;
            background: #4A90E2;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .nav-tabs .nav-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            border: none;
            border-radius: .5rem .5rem 0 0;
            padding: .5rem 1rem;
            transition: background .2s;
        }
        .nav-tabs .nav-link.active {
            color: #0056b3;
            background-color: #e7f4ff;
            border-color: #12181e #0056b3 #fff;
            font-weight: 600;
        }
        .nav-tabs .nav-link:hover {
            background: #f8f9fa;
        }
        .nav-tabs .nav-link iconify-icon {
            font-size: 1.2rem;
        }
        .nav-tabs .nav-link {
            color: #555;
            font-weight: 500;
        }
        .avatar-preview {
            max-width: 100px;
            margin-top: .5rem;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Yorum listesi */
        .border-bottom {
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
        }

        .border-bottom:last-child {
            border-bottom: none;
        }

        .comment-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .comment-author {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
        }

        .comment-type {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .comment-text {
            font-size: 0.9rem;
            color: #374151;
        }

        /* Yıldızlar */
        .star-rating {
            display: flex;
            align-items: center;
            gap: 2px; /* Yıldızlar arasında küçük bir boşluk */
        }

        .star-rating iconify-icon {
            font-size: 1rem;
            color: #f59e0b;
        }

        .star-rating iconify-icon[icon="mdi:star-outline"] {
            color: #d1d5db;
        }

        @media (max-width: 768px) {
            .border-bottom {
                padding: 0.75rem 0;
            }

            .comment-author {
                font-size: 0.9rem;
            }

            .comment-text {
                font-size: 0.85rem;
            }

            .comment-type {
                font-size: 0.7rem;
                padding: 2px 6px;
            }

            .star-rating iconify-icon {
                font-size: 0.9rem;
            }

            .rounded-circle {
                width: 32px !important;
                height: 32px !important;
                font-size: 0.9rem !important;
            }
        }
    </style>
@endsection

@section('main')
    <div class="row g-4">
        {{-- SOL SÜTUN --}}
        <div class="col-lg-3">
            {{-- PROFİL ÖZETİ --}}
            <div class="card shadow-sm mb-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($avatar)
                            <img src="{{ asset($avatar->path) }}" class="object-fit-cover rounded-circle" width="150" alt="Avatar">
                        @else
                            <img src="{{ asset('upload/no_image.jpg') }}" class="object-fit-cover rounded-circle" width="150" alt="Varsayılan Avatar">
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">{{ $avukat->name }}</h5>
                    <div class="text-muted">{{ $avukat->email }}</div>
                    <div class="mt-1">{{ $avukat->username ?? '—' }}</div>
                    <div class="mt-2">
                        @if($avukat->blokeli_mi == 0)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Blokeli</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- İSTATİSTİK --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">İstatistik</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Son giriş:</span><span class="info-value">{{ $avukat->son_giris_at?->format('d.m.Y H:i') ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Giriş sayısı:</span><span class="info-value">{{ $avukat->giris_sayisi ?? 0 }}</span></div>
                    <div class="info-row"><span class="info-label">Bakiye:</span><span class="info-value">{{ number_format($avukat->balance, 2, ',', '.') }}</span></div>
                    <div class="info-row"><span class="info-label">Toplam İş:</span><span class="info-value">{{ $avukat->isler->count() }}</span></div>
                </div>
            </div>

            {{-- KİŞİSEL BİLGİLER --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Kişisel Bilgiler</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Telefon:</span><span class="info-value">{{ $avukat->phone ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">T.C. No:</span><span class="info-value">{{ $avukat->tc_no ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Doğum:</span><span class="info-value">{{ optional($avukat->dogum_tarihi)->format('d.m.Y') ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Cinsiyet:</span><span class="info-value">{{ $avukat->cinsiyet ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Adres:</span><span class="info-value">{{ Str::limit($avukat->adres, 25) ?: '—' }}</span></div>
                </div>
            </div>

            {{-- MESLEKİ BİLGİLER --}}
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Mesleki Bilgiler</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Baro No:</span><span class="info-value">{{ $avukat->baro_no ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Baro Adı:</span><span class="info-value">{{ $avukat->baro_adi ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Ünvan:</span><span class="info-value">{{ $avukat->unvan ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Mezuniyet:</span><span class="info-value">{{ $avukat->mezuniyet_universitesi ?? '—' }} {{ $avukat->mezuniyet_yili ? "({$avukat->mezuniyet_yili})" : '' }}</span></div>
                    <div class="info-row"><span class="info-label">Uzmanlık:</span><span class="info-value">{{ $avukat->uzmanlik_alani ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Puan:</span><span class="info-value">{{ number_format($avukat->puan, 1) ?? '—' }}</span></div>
                </div>
            </div>
        </div>

        {{-- SAĞ SÜTUN --}}
        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                {{-- SEKMELER --}}
                <ul class="nav nav-tabs mb-3" id="avukatTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profil" type="button">
                            <iconify-icon icon="mdi:account-circle-outline"></iconify-icon>
                            Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#guncelle" type="button">
                            <iconify-icon icon="mdi:pencil-outline"></iconify-icon>
                            Güncelle
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sifre" type="button">
                            <iconify-icon icon="mdi:lock-outline"></iconify-icon>
                            Şifre
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#isler" type="button">
                            <iconify-icon icon="mdi:history"></iconify-icon>
                            İş Geçmişi
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#puanlar" type="button">
                            <iconify-icon icon="mdi:star-outline"></iconify-icon>
                            Değerlendirme
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
                        {{-- PROFİL TAB --}}
                        <div class="tab-pane fade show active" id="profil">
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Telefon</label><div class="form-control-plaintext">{{ $avukat->phone ?? '—' }}</div></div>
                                <div class="col-md-6"><label class="form-label">Baro No</label><div class="form-control-plaintext">{{ $avukat->baro_no ?? '—' }}</div></div>
                                <div class="col-md-6"><label class="form-label">Cinsiyet</label><div class="form-control-plaintext">{{ $avukat->cinsiyet ?? '—' }}</div></div>
                                <div class="col-md-6"><label class="form-label">Uzmanlık</label><div class="form-control-plaintext">{{ $avukat->uzmanlik_alani ?? '—' }}</div></div>
                                <div class="col-md-12"><label class="form-label">Adres</label><div class="form-control-plaintext">{{ $avukat->adres ?? '—' }}</div></div>
                                <div class="col-md-12"><label class="form-label">Notlar</label><div class="form-control-plaintext">{{ $avukat->notlar ?? '—' }}</div></div>
                            </div>
                        </div>

                        {{-- GÜNCELLE TAB --}}
                        <div class="tab-pane fade" id="guncelle">
                            <form action="{{ route('avukat.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Ad Soyad</label>
                                        <input name="name" class="form-control" value="{{ old('name', $avukat->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Username</label>
                                        <input name="username" class="form-control" value="{{ old('username', $avukat->username) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input name="email" type="email" class="form-control" value="{{ old('email', $avukat->email) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Telefon</label>
                                        <input name="phone" class="form-control" value="{{ old('phone', $avukat->phone) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">T.C. No</label>
                                        <input name="tc_no" class="form-control" value="{{ old('tc_no', $avukat->tc_no) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Baro No</label>
                                        <input name="baro_no" class="form-control" value="{{ old('baro_no', $avukat->baro_no) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Baro Adı</label>
                                        <input name="baro_adi" class="form-control" value="{{ old('baro_adi', $avukat->baro_adi) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ünvan</label>
                                        <input name="unvan" class="form-control" value="{{ old('unvan', $avukat->unvan) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Uzmanlık</label>
                                        <input name="uzmanlik_alani" class="form-control" value="{{ old('uzmanlik_alani', $avukat->uzmanlik_alani) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Doğum Tarihi</label>
                                        <input name="dogum_tarihi" type="date" class="form-control" value="{{ old('dogum_tarihi', $avukat->dogum_tarihi?->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cinsiyet</label>
                                        <select name="cinsiyet" class="form-select">
                                            <option value="">Seçiniz</option>
                                            @foreach(['Erkek', 'Kadın', 'Diğer'] as $c)
                                                <option value="{{ $c }}" @selected(old('cinsiyet', $avukat->cinsiyet) == $c)>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mezuniyet Üniversitesi</label>
                                        <input name="mezuniyet_universitesi" class="form-control" value="{{ old('mezuniyet_universitesi', $avukat->mezuniyet_universitesi) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mezuniyet Yılı</label>
                                        <input name="mezuniyet_yili" type="number" class="form-control" value="{{ old('mezuniyet_yili', $avukat->mezuniyet_yili) }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Adres</label>
                                        <textarea name="adres" class="form-control" rows="2">{{ old('adres', $avukat->adres) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Notlar</label>
                                        <textarea name="notlar" class="form-control" rows="2">{{ old('notlar', $avukat->notlar) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Avatar</label>
                                        @if($avatar)
                                            <img src="{{ asset($avatar->path) }}" class="avatar-preview rounded-circle" alt="Mevcut Avatar">
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 text-end">
                                    <button class="btn btn-primary">Kaydet</button>
                                </div>
                            </form>
                        </div>

                        {{-- ŞİFRE TAB --}}
                        <div class="tab-pane fade" id="sifre">
                            <form action="{{ route('avukat.profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="update_password_only" value="1">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Yeni Şifre</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tekrar</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                                <div class="mt-4 text-end">
                                    <button class="btn btn-warning">Şifreyi Güncelle</button>
                                </div>
                            </form>
                        </div>

                        {{-- İŞ GEÇMİŞİ TAB --}}
                        <div class="tab-pane fade" id="isler">
                            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                <h6 class="mb-0 fw-bold text-lg">Son İşleriniz</h6>
                                <a href="{{ route('avukat.isler.index') }}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                    Tümünü Göster
                                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                </a>
                            </div>


                            <div class="table-responsive mt-10">
                                <table class="table bordered-table mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">İşlem No</th>
                                        <th scope="col">Katip</th>
                                        <th scope="col">Avukat</th>
                                        <th scope="col">İşlem Türü</th>
                                        <th scope="col">Aciliyet</th>
                                        <th scope="col" class="">Durum</th>
                                        <th scope="col">Detay</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($avukat->isler as $is)
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
                                                    <span class="text-lg text-secondary-light fw-semibold flex-grow-1">
                                                                <a href="{{ route('avukat.katip.profil', $is->katip->id) }}"
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
                                                    <span class="text-lg text-secondary-light fw-semibold flex-grow-1">
                                                                <a href="{{ route('avukat.profile.edit') }}"
                                                                   class="text-decoration-none text-dark hover-text-primary">
                                                                    {{ $is->avukat->username }}
                                                                </a>
                                                            </span>
                                                </div>
                                            </td>
                                            <td>{{ $is->islem_tipi }}</td>
                                            <td>
                                                @php
                                                    $acilMap = [
                                                        'Acil'    => 'status-btn urgent',
                                                        'Normal'  => 'status-btn normal',
                                                        'Çok Acil' => 'status-btn very-urgent',
                                                    ];
                                                    $acilClass = $acilMap[$is->aciliyet] ?? 'status-btn normal';
                                                @endphp
                                                <span class="{{ $acilClass }}">
                                                            {{ $is->aciliyet ?? 'Normal' }}
                                                        </span>
                                            </td>
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
                                            <td style="text-align: center">
                                                <a href="{{ route('avukat.isler.detay', $is->id) }}"
                                                   class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                                    <iconify-icon icon="iconamoon:eye-light" class="icon"></iconify-icon>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">Henüz iş talebi vermediniz.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>


                        </div>

                        {{-- PUANLAR TAB --}}

                        <div class="tab-pane fade" id="puanlar">
                            <ul class="nav nav-tabs mb-3" id="puanTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#size-yapilan" type="button">
                                        <iconify-icon icon="mdi:star-outline"></iconify-icon>
                                        Size Yapılan Yorumlar
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sizin-yaptiklariniz" type="button">
                                        <iconify-icon icon="mdi:comment-outline"></iconify-icon>
                                        Sizin Yaptığınız Yorumlar
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                {{-- SIZE YAPILAN YORUMLAR --}}
                                <div class="tab-pane fade show active" id="size-yapilan">
                                    @forelse($sizeYapilanPuanlar as $p)
                                        <div class="border-bottom py-2">
                                            <div class="d-flex align-items-start gap-3">
                                                @if($p->katip && optional($p->katip->avatar)->path)
                                                    <img src="{{ asset($p->katip->avatar->path) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1rem;">
                                                        {{ strtoupper(substr($p->katip->username ?? '?', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div class="comment-header">
                                                            <span class="comment-author">{{ optional($p->katip)->name ?? 'Bilinmeyen' }}</span>
                                                            <span class="comment-type badge bg-info">Kâtip</span>
                                                        </div>
                                                        <div class="star-rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <iconify-icon icon="{{ $i <= $p->puan ? 'mdi:star' : 'mdi:star-outline' }}"></iconify-icon>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="comment-text mb-1">{{ $p->yorum ?: '—' }}</p>
                                                    <small class="text-muted">{{ $p->created_at->format('d.m.Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">Size yapılmış yorum yok.</p>
                                    @endforelse
                                </div>

                                {{-- SIZIN YAPTIĞINIZ YORUMLAR --}}
                                <div class="tab-pane fade" id="sizin-yaptiklariniz">
                                    @forelse($sizinYaptiginizPuanlar as $p)
                                        <div class="border-bottom py-2">
                                            <div class="d-flex align-items-start gap-3">
                                                @if($avukat && optional($avukat->avatar)->path)
                                                    <img src="{{ asset($avukat->avatar->path) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1rem;">
                                                        {{ strtoupper(substr($avukat->username ?? '?', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div class="comment-header">
                                                            <span class="comment-author">{{ $avukat->name ?? 'Bilinmeyen' }}</span>
                                                            <span class="comment-type badge bg-success">Avukat</span>
                                                        </div>
                                                        <div class="star-rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <iconify-icon icon="{{ $i <= $p->puan ? 'mdi:star' : 'mdi:star-outline' }}"></iconify-icon>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                    <p class="comment-text mb-1">{{ $p->yorum ?: '—' }}</p>
                                                    <small class="text-muted">İş: #{{ $p->islem->id }} - {{ $p->islem->islem_tipi }} | {{ $p->created_at->format('d.m.Y') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-muted">Henüz yorum yapmadınız.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
