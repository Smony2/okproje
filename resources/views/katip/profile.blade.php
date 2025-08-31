@extends('katip.layout.katip_master')

@section('title')
    <title>Profil Ayarları | {{ $katip->name }}</title>
@endsection

@section('cssler')
    <style>
        /* General Styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }

        /* Card Styling */
        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card-header {
            border-radius: 16px 16px 0 0;
            background: linear-gradient(135deg, #f9fafb, #f1f5f9);
            padding: 14px 20px;
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-body {
            padding: 28px;
        }

        /* Avatar Styling */
        .avatar-initial {
            width: 130px;
            height: 130px;
            font-size: 2.8rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto;
            border: 4px solid #ffffff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .avatar-initial:hover {
            transform: scale(1.06);
        }

        .avatar-preview {
            max-width: 130px;
            margin-top: 1rem;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .avatar-preview:hover {
            transform: scale(1.06);
        }

        /* Info Row Styling */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .info-row:hover {
            background: #f8fafc;
        }

        .info-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 1rem;
        }

        .info-value {
            color: #6b7280;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Tabs Styling */
        .nav-tabs {
            border-bottom: 2px solid #e2e8f0;
        }

        .nav-tabs .nav-link {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            border-radius: 0;
            padding: 8px 24px;
            color: #1e293b;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #d1d5db;
            color: #1d4ed8;
        }

        .nav-tabs .nav-link.active {
            color: #1d4ed8;
            background: #dbeafe;
            border-bottom: 3px solid #3b82f6;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        }

        .nav-tabs .nav-link iconify-icon {
            font-size: 1.3rem;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover iconify-icon,
        .nav-tabs .nav-link.active iconify-icon {
            color: #1d4ed8;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            padding: 12px 18px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.25);
            outline: none;
        }

        .form-check {
            margin-bottom: 10px;
            padding-left: 2rem;
        }

        .form-check-input {
            margin-top: 5px;
            margin-right: 10px;
            cursor: pointer;
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .form-check-label {
            font-size: 1rem;
            color: #1e293b;
            font-weight: 500;
            cursor: pointer;
        }

        /* Button Styling */
        .btn-primary {
            background: #3b82f6;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 1rem;
            color: #ffffff;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
        }

        .btn-warning {
            background: #facc15;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 1rem;
            color: #1e293b;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(250, 204, 21, 0.3);
        }

        .btn-warning:hover {
            background: #eab308;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(250, 204, 21, 0.5);
        }

        /* Badge Styling */
        .badge {
            font-size: 0.95rem;
            padding: 8px 16px;
            border-radius: 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .badge.bg-success {
            background: #22c55e;
            color: #ffffff;
        }

        .badge.bg-danger {
            background: #ef4444;
            color: #ffffff;
        }

        /* Alert Styling */
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #f87171;
            border-radius: 10px;
            padding: 18px;
            color: #b91c1c;
            font-size: 0.95rem;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 24px;
        }

        /* List Items */
        .border-bottom {
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 0;
            transition: all 0.3s ease;
        }

        .border-bottom:hover {
            background: #f8fafc;
            transform: translateX(4px);
        }

        .tab-content .tab-pane {
            padding: 20px;
            background: #ffffff;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .text-muted {
            color: #6b7280 !important;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .text-warning {
            color: #f59e0b !important;
            font-size: 1.1rem;
        }

        /* Tab Content Specific */
        #puanlar .nav-tabs {
            background: transparent;
            padding: 0;
        }

        #puanlar .nav-tabs .nav-link {
            background: #f1f5f9;
        }

        #puanlar .nav-tabs .nav-link.active {
            background: #dbeafe;
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
                            <img src="{{ asset($avatar->path) }}" class="object-fit-cover rounded-circle avatar-preview" alt="Avatar">
                        @else
                            <div class="avatar-initial">{{ strtoupper(substr($katip->name, 0, 1)) }}</div>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-1">{{ $katip->name }}</h5>
                    <div class="text-muted">{{ $katip->email }}</div>
                    <div class="mt-1">{{ $katip->username ?? '—' }}</div>
                    <div class="mt-2">
                        @if($katip->blokeli_mi == 0)
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
                    <div class="info-row"><span class="info-label">Bakiye:</span><span class="info-value">{{ number_format($katip->balance, 2, ',', '.') }}</span></div>
                    <div class="info-row"><span class="info-label">Toplam İş:</span><span class="info-value">{{ $katip->isler->count() }}</span></div>
                </div>
            </div>

            {{-- KİŞİSEL BİLGİLER --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Kişisel Bilgiler</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Telefon:</span><span class="info-value">{{ $katip->phone ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">T.C. No:</span><span class="info-value">{{ $katip->tc_no ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Doğum:</span><span class="info-value">{{ optional($katip->dogum_tarihi)->format('d.m.Y') ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Cinsiyet:</span><span class="info-value">{{ $katip->cinsiyet ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Adres:</span><span class="info-value">{{ Str::limit($katip->adres, 25) ?: '—' }}</span></div>
                </div>
            </div>

            {{-- MESLEKİ BİLGİLER --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Mesleki Bilgiler</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Mezuniyet:</span><span class="info-value">{{ $katip->mezuniyet_universitesi ?? '—' }} {{ $katip->mezuniyet_yili ? "({$katip->mezuniyet_yili})" : '' }}</span></div>
                    <div class="info-row"><span class="info-label">Uzmanlık:</span><span class="info-value">{{ $katip->uzmanlik_alani ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Puan:</span><span class="info-value">{{ number_format($katip->puan, 1) ?? '—' }}</span></div>
                </div>
            </div>

            {{-- ÇALIŞTIĞI ADLİYELER --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Çalıştığı Adliyeler</div>
                <div class="card-body">
                    @forelse($katip->adliyeler as $adliye)
                        <div class="info-row">
                            <span class="info-label">{{ $adliye->ad }}</span>
                            <span class="info-value text-muted">{{ $adliye->il }}/{{ $adliye->ilce }}</span>
                        </div>
                    @empty
                        <p class="text-muted">Çalıştığı adliye yok.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- SAĞ SÜTUN --}}
        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                {{-- SEKMELER --}}
                <ul class="nav nav-tabs mb-3" id="katipTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profil" type="button">
                            <iconify-icon icon="solar:user-outline"></iconify-icon>
                            Profil
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#guncelle" type="button">
                            <iconify-icon icon="solar:pencil-outline"></iconify-icon>
                            Güncelle
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sifre" type="button">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                            Şifre
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#isler" type="button">
                            <iconify-icon icon="solar:history-outline"></iconify-icon>
                            İş Geçmişi
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#loglar" type="button">
                            <iconify-icon icon="solar:clipboard-text-outline"></iconify-icon>
                            Loglar
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#puanlar" type="button">
                            <iconify-icon icon="solar:star-outline"></iconify-icon>
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
                                <div class="col-md-6"><label class="form-label">Telefon</label><div class="form-control-plaintext">{{ $katip->phone ?? '—' }}</div></div>
                                <div class="col-md-6"><label class="form-label">Cinsiyet</label><div class="form-control-plaintext">{{ $katip->cinsiyet ?? '—' }}</div></div>
                                <div class="col-md-6"><label class="form-label">Uzmanlık</label><div class="form-control-plaintext">{{ $katip->uzmanlik_alani ?? '—' }}</div></div>
                                <div class="col-md-12"><label class="form-label">Adres</label><div class="form-control-plaintext">{{ $katip->adres ?? '—' }}</div></div>
                                <div class="col-md-12"><label class="form-label">Notlar</label><div class="form-control-plaintext">{{ $katip->notlar ?? '—' }}</div></div>
                            </div>
                        </div>

                        {{-- GÜNCELLE TAB --}}
                        <div class="tab-pane fade" id="guncelle">
                            <form action="{{ route('katip.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Ad Soyad</label>
                                        <input name="name" class="form-control" value="{{ old('name', $katip->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kullanıcı Adı</label>
                                        <input name="username" class="form-control" value="{{ old('username', $katip->username) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input name="email" type="email" class="form-control" value="{{ old('email', $katip->email) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Telefon</label>
                                        <input name="phone" class="form-control" value="{{ old('phone', $katip->phone) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">T.C. No</label>
                                        <input name="tc_no" class="form-control" value="{{ old('tc_no', $katip->tc_no) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Doğum Tarihi</label>
                                        <input name="dogum_tarihi" type="date" class="form-control" value="{{ old('dogum_tarihi', $katip->dogum_tarihi?->format('Y-m-d')) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cinsiyet</label>
                                        <select name="cinsiyet" class="form-select">
                                            <option value="">Seçiniz</option>
                                            @foreach(['Erkek', 'Kadın', 'Diğer'] as $c)
                                                <option value="{{ $c }}" @selected(old('cinsiyet', $katip->cinsiyet) == $c)>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mezuniyet Üniversitesi</label>
                                        <input name="mezuniyet_universitesi" class="form-control" value="{{ old('mezuniyet_universitesi', $katip->mezuniyet_universitesi) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Mezuniyet Yılı</label>
                                        <input name="mezuniyet_yili" type="number" class="form-control" value="{{ old('mezuniyet_yili', $katip->mezuniyet_yili) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Uzmanlık</label>
                                        <input name="uzmanlik_alani" class="form-control" value="{{ old('uzmanlik_alani', $katip->uzmanlik_alani) }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Adres</label>
                                        <textarea name="adres" class="form-control" rows="2">{{ old('adres', $katip->adres) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Notlar</label>
                                        <textarea name="notlar" class="form-control" rows="2">{{ old('notlar', $katip->notlar) }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Çalıştığı Adliyeler</label>
                                        <div class="row">
                                            @foreach(\App\Models\Adliye::orderBy('ad')->get() as $ayd)
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               name="adliyeler[]"
                                                               value="{{ $ayd->id }}"
                                                               id="adliye-{{ $ayd->id }}"
                                                                @checked(in_array($ayd->id, old('adliyeler', $katip->adliyeler->pluck('id')->toArray())))
                                                        >
                                                        <label class="form-check-label" for="adliye-{{ $ayd->id }}">
                                                            {{ $ayd->ad }} <small class="text-muted">({{ $ayd->il }}/{{ $ayd->ilce }})</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 text-end">
                                    <button class="btn btn-primary">Kaydet</button>
                                </div>
                            </form>
                        </div>

                        {{-- ŞİFRE TAB --}}
                        <div class="tab-pane fade" id="sifre">
                            <form action="{{ route('katip.profile.update') }}" method="POST">
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
                            @forelse($katip->isler as $is)
                                <div class="border-bottom py-2 d-flex justify-content-between align-items-center">
                                    <span>#{{ $is->id }} – {{ $is->islem_tipi }} ({{ $is->avukat->name ?? 'Avukat yok' }})</span>
                                    <small class="text-muted">{{ $is->is_tamamlandi_at?->format('d.m.Y') }}</small>
                                </div>
                            @empty
                                <p class="text-muted">Kayıtlı iş yok.</p>
                            @endforelse
                        </div>

                        {{-- PUANLAR TAB --}}
                        <div class="tab-pane fade" id="puanlar">
                            <ul class="nav nav-tabs mb-3" id="puanTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#size-yapilan" type="button">
                                        <iconify-icon icon="solar:star-outline"></iconify-icon>
                                        Size Yapılan Yorumlar
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sizin-yaptiklariniz" type="button">
                                        <iconify-icon icon="solar:chat-line-outline"></iconify-icon>
                                        Sizin Yaptığınız Yorumlar
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                {{-- SIZE YAPILAN YORUMLAR --}}
                                <div class="tab-pane fade show active" id="size-yapilan">
                                    @forelse($sizeYapilanPuanlar as $p)
                                        <div class="border-bottom py-2">
                                            <div>
                                                <span class="text-warning">{{ str_repeat('★', $p->puan) . str_repeat('☆', 5 - $p->puan) }}</span>
                                                <small class="text-muted ms-2">{{ $p->created_at->format('d.m.Y') }}</small>
                                            </div>
                                            <p class="mb-0">{{ $p->yorum ?: '—' }}</p>
                                            <small class="text-muted">
                                                Veren: {{ optional($p->veren)->name ?? 'Bilinmeyen' }}
                                                ({{ $p->veren_tipi == 'avukat' ? 'Avukat' : ($p->veren_tipi == 'katip' ? 'Katip' : 'Bilinmeyen') }})
                                            </small>
                                        </div>
                                    @empty
                                        <p class="text-muted">Size yapılmış yorum yok.</p>
                                    @endforelse
                                </div>

                                {{-- SIZIN YAPTIĞINIZ YORUMLAR --}}
                                <div class="tab-pane fade" id="sizin-yaptiklariniz">
                                    @forelse($sizinYaptiginizPuanlar as $p)
                                        <div class="border-bottom py-2">
                                            <div>
                                                <span class="text-warning">{{ str_repeat('★', $p->puan) . str_repeat('☆', 5 - $p->puan) }}</span>
                                                <small class="text-muted ms-2">{{ $p->created_at->format('d.m.Y') }}</small>
                                            </div>
                                            <p class="mb-0">{{ $p->yorum ?: '—' }}</p>
                                            <small class="text-muted">İş: #{{ $p->islem->id }} - {{ $p->islem->islem_tipi }}</small>
                                        </div>
                                    @empty
                                        <p class="text-muted">Henüz yorum yapmadınız.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- LOGLAR TAB --}}
                        <div class="tab-pane fade" id="loglar">
                            @forelse($logs as $log)
                                <div class="border-bottom py-2">
                                    <strong>{{ $log->description }}</strong>
                                    <br><small class="text-muted">{{ $log->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                            @empty
                                <p class="text-muted">Log kaydı yok.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection