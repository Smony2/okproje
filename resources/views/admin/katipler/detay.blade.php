@extends('admin.yonetim_master')

@section('title')
    <title>Katip Profili | {{ $katip->name }}</title>
@endsection

@section('cssler')
    <style>
        .avatar-initial{width:100px;height:100px;font-size:2rem;background:#4A90E2;color:#fff;display:flex;align-items:center;justify-content:center;border-radius:50%;margin:0 auto;}
        .info-row{display:flex;justify-content:space-between;margin-bottom:8px;}

        .nav-tabs .nav-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            border: none;
            border-radius: .5rem .5rem 0 0;
            padding: .5rem 1rem;
            transition: background .2s;
        }
        /* Aktif sekme vurgusu */
        .nav-tabs .nav-link.active {
            color: #0056b3;
            background-color: #e7f4ff;
            border-color: #12181e #0056b3 #fff;
            font-weight: 600;
        }

        /* Hover efekti */
        .nav-tabs .nav-link:hover {
            background: #f8f9fa;
        }
        /* İkon boyutu */
        .nav-tabs .nav-link iconify-icon {
            font-size: 1.2rem;
        }

        .nav-tabs .nav-link {
            color: #555;
            font-weight: 500;
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
                    <h5 class="fw-bold mb-1">{{ $katip->name }}</h5>
                    <div class="text-muted">{{ $katip->email }}</div>
                    <div class="mt-1">{{ $katip->username ?? '—' }}</div>
                    <div class="mt-2">
                        @if($katip->blokeli_mi==0)
                            <span class="badge bg-success">
                                Aktif
                            </span>
                        @else
                            <span class="badge bg-danger ms-1">Blokeli</span>
                        @endif

                    </div>

                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">İstatistik</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Son giriş:</span><span class="info-value">{{ $katip->last_login_at?->format('d.m.Y H:i') ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Giriş sayısı::</span><span class="info-value">{{ $katip->giris_sayisi ?? 0 }}</span></div>
                    <div class="info-row"><span class="info-label">Bakiye:</span><span class="info-value">{{ number_format($katip->balance,2,',','.') }}</span></div>
                    <div class="info-row"><span class="info-label">Toplam İş:</span><span class="info-value">0</span></div>

                </div>
            </div>

            {{-- Çalıştığı Adliyeler --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Çalıştığı Adliyeler</div>
                <div class="card-body">
                    @if($katip->adliyeler->isEmpty())
                        <p class="text-muted">Henüz adliye atanmamış.</p>
                    @else
                        @foreach($katip->adliyeler as $adliye)
                            <span class="badge bg-primary me-1 mb-1">{{ $adliye->ad }}</span>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Kişisel Bilgiler</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Telefon:</span><span class="info-value">{{ $katip->phone ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">T.C. No:</span><span class="info-value">{{ $katip->tc_no ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Doğum:</span><span class="info-value">{{ optional($katip->dogum_tarihi)->format('d.m.Y') ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Cinsiyet:</span><span class="info-value">{{ $katip->cinsiyet ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Adres:</span><span class="info-value">{{ Str::limit($katip->adres,25) ?: '—' }}</span></div>
                </div>
            </div>

            {{-- Mesleki Bilgiler --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header fw-semibold">Mesleki Bilgiler</div>
                <div class="card-body">
                    <div class="info-row"><span class="info-label">Ünvan:</span><span class="info-value">{{ $katip->unvan ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Mezuniyet:</span><span class="info-value">{{ $katip->mezuniyet_universitesi ?? '—' }} {{ $katip->mezuniyet_yili ? "({$katip->mezuniyet_yili})" : '' }}</span></div>
                    <div class="info-row"><span class="info-label">Uzmanlık:</span><span class="info-value">{{ $katip->uzmanlik_alani ?? '—' }}</span></div>
                    <div class="info-row"><span class="info-label">Puan:</span><span class="info-value">{{ number_format($katip->puan,1) ?? '—' }}</span></div>
                </div>
            </div>


        </div>



        {{-- SAĞ SÜTUN --}}
        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                {{-- Sekmeler --}}
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
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#loglar" type="button">
                            <iconify-icon icon="mdi:clipboard-text"></iconify-icon>
                            Loglar
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#puanlar" type="button">
                            <iconify-icon icon="mdi:star-outline"></iconify-icon>
                            Değerlendirme
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ban" type="button">
                            <iconify-icon icon="mdi:block-helper"></iconify-icon>
                            Banla / Sil
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#adliyeler" type="button">
                            <iconify-icon icon="mdi:office-building-outline"></iconify-icon>
                            Adliyeler
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
                            <form action="{{ route('admin.katipler.update',$katip->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="row g-3">
                                    <div class="col-md-6"><label class="form-label">Ad Soyad</label><input name="name" class="form-control" value="{{ old('name',$katip->name) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Username</label><input name="username" class="form-control" value="{{ old('username',$katip->username) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control" value="{{ old('email',$katip->email) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Telefon</label><input name="phone" class="form-control" value="{{ old('phone',$katip->phone) }}"></div>
                                    <div class="col-md-6"><label class="form-label">T.C. No</label><input name="tc_no" class="form-control" value="{{ old('tc_no',$katip->tc_no) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Ünvan</label><input name="unvan" class="form-control" value="{{ old('unvan',$katip->unvan) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Uzmanlık</label><input name="uzmanlik_alani" class="form-control" value="{{ old('uzmanlik_alani',$katip->uzmanlik_alani) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Doğum Tarihi</label><input name="dogum_tarihi" type="date" class="form-control" value="{{ old('dogum_tarihi',$katip->dogum_tarihi?->format('Y-m-d')) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Cinsiyet</label>
                                        <select name="cinsiyet" class="form-select">
                                            <option value="">Seçiniz</option>
                                            @foreach(['Erkek','Kadın','Diğer'] as $c)
                                                <option value="{{ $c }}" @selected(old('cinsiyet',$katip->cinsiyet)==$c)>{{ $c }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6"><label class="form-label">Mezuniyet Üniversitesi</label><input name="mezuniyet_universitesi" class="form-control" value="{{ old('mezuniyet_universitesi',$katip->mezuniyet_universitesi) }}"></div>
                                    <div class="col-md-6"><label class="form-label">Mezuniyet Yılı</label><input name="mezuniyet_yili" type="number" class="form-control" value="{{ old('mezuniyet_yili',$katip->mezuniyet_yili) }}"></div>
                                    <div class="col-md-12"><label class="form-label">Adres</label><textarea name="adres" class="form-control" rows="2">{{ old('adres',$katip->adres) }}</textarea></div>
                                    <div class="col-md-12"><label class="form-label">Notlar</label><textarea name="notlar" class="form-control" rows="2">{{ old('notlar',$katip->notlar) }}</textarea></div>

                                </div>

                                <div class="mt-4 text-end">
                                    <button class="btn btn-primary">Kaydet</button>
                                </div>
                            </form>
                        </div>

                        {{-- ŞİFRE TAB --}}
                        <div class="tab-pane fade" id="sifre">
                            <form action="{{ route('admin.avukatlar.update',$katip->id) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="hidden" name="update_password_only" value="1">
                                <div class="row g-3">
                                    <div class="col-md-6"><label class="form-label">Yeni Şifre</label><input type="password" name="password" class="form-control" required></div>
                                    <div class="col-md-6"><label class="form-label">Tekrar</label><input type="password" name="password_confirmation" class="form-control" required></div>
                                </div>
                                <div class="mt-4 text-end">
                                    <button class="btn btn-warning">Şifreyi Güncelle</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="isler">
                            @forelse($katip->isler as $is)
                                <div class="border-bottom py-2 d-flex justify-content-between">
                                    <span>#{{ $is->id }} – {{ $is->islem_tipi }} ({{ $is->katip->username ?? 'Katip yok' }})</span>
                                    <small class="text-muted">{{ $is->is_tamamlandi_at?->format('d.m.Y') }}</small>
                                </div>
                            @empty
                                <p class="text-muted">Kayıtlı iş yok.</p>
                            @endforelse
                        </div>

                        <div class="tab-pane fade" id="ban">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                @if(!$katip->blokeli_mi)
                                    <form action="{{ route('admin.katipler.ban',$katip->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-warning" onclick="return confirm('Bloke edeceksiniz, devam?')">Banla</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.katipler.unban',$katip->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-success" onclick="return confirm('Bloke kaldırılacak, devam?')">Bloke Kaldır</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.katipler.destroy',$katip->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger" onclick="return confirm('Kalıcı olarak silmek istediğinize emin misiniz?')">Sil</button>
                                </form>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="puanlar">
                            @forelse($puanlar as $p)
                                <div class="border-bottom py-2">
                                    {{ str_repeat('★',$p->puan).str_repeat('☆',5-$p->puan) }}
                                    <small class="text-muted ms-2">{{ $p->created_at->format('d.m.Y') }}</small>
                                    <p class="mb-0">{{ $p->yorum ?: '—' }}</p>
                                </div>
                            @empty
                                <p class="text-muted">Henüz puan yok.</p>
                            @endforelse
                        </div>

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

                        <div class="tab-pane fade" id="adliyeler" role="tabpanel">
                            <form action="{{ route('admin.katipler.syncadliyeler', $katip->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label">Çalıştığı Adliyeler</label>
                                    <div class="row">
                                        @foreach($tumAdliyeler as $ad)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input style="margin-top: 3px"
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        name="adliyeler[]"
                                                        id="adliye_{{ $ad->id }}"
                                                        value="{{ $ad->id }}"
                                                        {{ $katip->adliyeler->contains('id',$ad->id) ? 'checked' : '' }}
                                                    >
                                                    <label style="margin-left: 5px" class="form-check-label" for="adliye_{{ $ad->id }}">
                                                        {{ $ad->ad }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Güncelle</button>
                            </form>
                        </div>
                    </div> {{-- tab-content --}}
                </div>
            </div>
        </div>
    </div>
@endsection
