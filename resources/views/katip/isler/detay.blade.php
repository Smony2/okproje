@extends('katip.layout.katip_master')

@section('cssler')
    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 0.75rem 1rem;
        }

        .card-header h6 {
            font-weight: 500;
            font-size: 1rem;
            margin: 0;
            display: flex;
            align-items: center;
            color: #2e4b3f;
        }

        .card-header i {
            margin-right: 0.5rem;
            color: #d4a017;
        }

        .card-body {
            padding: 1rem;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .table th {
            font-weight: 500;
            color: #4a4a4a;
            width: 40%;
            padding: 0.6rem 1rem;
            background: #f0f0f0;
            border-top: none;
        }

        .table td {
            padding: 0.6rem 1rem;
            color: #333;
            border-top: none;
        }

        .alert {
            border-radius: 6px;
            font-size: 0.9rem;
            padding: 0.6rem 1rem;
            margin-bottom: 0.75rem;
            border: none;
        }

        .alert-info {
            background: #e9ecef;
            color: #343a40;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .btn {
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-success {
            background: linear-gradient(90deg, #28a745 0%, #218838 100%);
            color: #fff;
        }

        .btn-success:hover {
            background: linear-gradient(90deg, #218838 0%, #1e7e34 100%);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(90deg, #d4a017 0%, #b38711 100%);
            color: #fff;
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #b38711 0%, #9e760f 100%);
            transform: translateY(-1px);
        }

        .btn-danger {
            background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
            color: #fff;
        }

        .btn-danger:hover {
            background: linear-gradient(90deg, #c82333 0%, #bd2130 100%);
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.85rem;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
            font-size: 0.9rem;
            padding: 0.5rem;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            border-color: #d4a017;
            box-shadow: 0 0 0 0.2rem rgba(212, 160, 23, 0.25);
            outline: none;
        }

        textarea.form-control {
            resize: vertical;
        }

        .list-unstyled {
            margin: 0;
        }

        .list-unstyled li {
            position: relative;
            padding-left: 2rem;
        }

        .list-unstyled .border-start {
            border-color: #d4a017 !important;
            border-width: 2px;
        }

        .list-unstyled li .rounded-circle {
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .list-unstyled li h6 {
            font-size: 0.95rem;
            font-weight: 500;
            color: #2e4b3f;
        }

        .list-unstyled li p {
            font-size: 0.85rem;
            color: #555;
        }

        .list-unstyled li small {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
            cursor: pointer;
        }

        .rating-stars .star {
            font-size: 1.5rem;
            color: #ddd;
            transition: color 0.2s;
        }

        .rating-stars .star.hover,
        .rating-stars .star.selected {
            color: #ffc107;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
        }

        .form-section {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .table th, .table td {
                padding: 0.5rem;
                font-size: 0.85rem;
            }

            .card-header {
                padding: 0.6rem 0.8rem;
            }

            .card-body {
                padding: 0.8rem;
            }

            .btn {
                padding: 0.3rem 0.6rem;
                font-size: 0.85rem;
            }
        }
        /* Yeni iş akışı listesi stilleri */
        .job-flow-list {

            padding-right: 0.5rem;
        }

        .job-flow-item {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius:10px;
            padding: 0.75rem;
            transition: all 0.2s ease;
        }

        .job-flow-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .job-flow-header {
            margin-bottom: 0.25rem;
        }

        .job-flow-header h6 {
            font-size: 0.95rem;
            font-weight: 500;
            color: #2e4b3f;
            margin: 0;
        }

        .job-flow-header .badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
        }

        .job-flow-item p {
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 0.25rem;
        }

        .job-flow-item small {
            font-size: 0.8rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {


            .job-flow-item {
                padding: 0.5rem;
            }

            .job-flow-header h6 {
                font-size: 0.9rem;
            }

            .job-flow-header .badge {
                font-size: 0.7rem;
            }

            .job-flow-item p {
                font-size: 0.8rem;
            }

            .job-flow-item small {
                font-size: 0.75rem;
            }
        }
    </style>
    <style>
        .toggle-desc {
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
        }
        .toggle-desc:hover {
            color: #0056b3;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('title')
    <title>İş Detayı | Kâtip Paneli</title>
@endsection

@section('main')
    <div class="row g-3">
        <!-- Sol Sütun (Avukat Detayı ve İş Detayı) -->
        <div class="col-md-3">

            <div class="card mb-20">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>İş Durum</h6></div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif

                    @if($islem->durum === 'bekliyor')
                        <div class="alert alert-info">İş avukattan geldi, onayınızı bekliyor.</div>
                        <div class="d-flex gap-2">
                            <form action="{{ route('katip.isler.onayla', $islem->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Onayla</button>
                            </form>
                            <form action="{{ route('katip.isler.reddet', $islem->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Reddet</button>
                            </form>
                        </div>
                    @elseif($islem->durum === 'devam ediyor' && !$islem->teklifler->where('katip_id', auth('katip')->id())->first())
                        <div class="alert alert-success">İşi onayladınız, lütfen teklif verin.</div>
                    @elseif($islem->durum === 'devam ediyor' && $islem->teklifler->where('katip_id', auth('katip')->id())->where('durum', 'bekliyor')->first())
                        <div class="alert alert-info">Teklifiniz avukat tarafından onay bekliyor.</div>
                    @elseif($islem->durum === 'devam ediyor' && $islem->teklifler->where('katip_id', auth('katip')->id())->where('durum', 'kabul')->first())
                        <div class="alert alert-success">Teklifiniz avukat tarafından kabul edildi, lütfen teslimat yapın.</div>
                    @elseif($islem->durum === 'tamamlandi' && !$islem->avukat_onay)
                        <div class="alert alert-warning">İşi teslim ettiniz, avukat onayı bekleniyor.</div>
                    @elseif($islem->durum === 'tamamlandi' && $islem->avukat_onay && !\App\Models\KatipPuan::where('is_id', $islem->id)->where('katip_id', auth('katip')->id())->exists())
                        <div class="alert alert-danger">İş tamamlandı, ancak henüz değerlendirme yapmadınız!</div>
                    @elseif($islem->durum === 'tamamlandi' && $islem->avukat_onay && \App\Models\KatipPuan::where('is_id', $islem->id)->where('katip_id', auth('katip')->id())->exists())
                        <div class="alert alert-success">İş tamamlandı ve değerlendirildi.</div>
                    @elseif($islem->durum === 'iptal')
                        <div class="alert alert-danger">İş iptal edildi.</div>
                    @endif
                </div>
            </div>


            <!-- İş Bilgileri -->
            <div class="card mb-20">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>İş Bilgileri</h6></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><th>İşlem #</th><td>#{{ $islem->id }}</td></tr>
                        <tr><th>Tür</th><td>{{ $islem->islem_tipi }}</td></tr>
                        <tr><th>Adliye</th><td>{{ optional($islem->adliye)->ad ?? '—' }}</td></tr>
                        <tr><th>Avukat</th><td>{{ optional($islem->avukat)->username ?? '—' }}</td></tr>
                        <tr><th>Durum</th><td>{{ ucfirst($islem->durum) }}</td></tr>
                        <tr>
                            <th>Açıklama</th>
                            <td>
                                @if (strlen($islem->aciklama) > 80)
                                    <span class="short-desc">{{ Str::limit($islem->aciklama, 80) }}</span>
                                    <span class="full-desc" style="display: none;">{{ $islem->aciklama }}</span>
                                    <a href="#" class="toggle-desc">Daha Fazla</a>
                                @else
                                    {{ $islem->aciklama ?? '—' }}
                                @endif
                            </td>
                        </tr>
                        <tr><th>Oluşturulma</th><td>{{ $islem->created_at->format('d.m.Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- İş Durum -->
        </div>

        <!-- Sağ Sütun (Tüm İçerik) -->
        <div class="col-md-9">
            <div class="row g-3">
                <!-- Sol Taraf (Teklifler, Teslimatlar, Yorumlar) -->
                <div class="col-md-8">


                    <div class="card mb-20">
                        <div class="card-header"><h6 class="mb-0"><i class="bi bi-cash me-2"></i>Teklifler</h6></div>
                        <div class="card-body">
                            @if($islem->durum === 'bekliyor')
                                <div class="alert alert-info">İş avukattan geldi, onayınızı bekliyor. Lütfen işi onaylayın veya reddedin.</div>
                            @elseif($islem->durum === 'devam ediyor')
                                @php
                                    // Kâtibin bekleyen veya kabul edilmiş bir teklifi var mı?
                                    $aktifTeklif = $islem->teklifler->where('katip_id', auth('katip')->id())
                                        ->whereIn('durum', ['bekliyor', 'kabul'])
                                        ->first();
                                @endphp

                                @if(!$aktifTeklif)
                                    <div class="alert alert-success">İşi onayladınız, lütfen teklif verin.</div>
                                    <div class="form-section">
                                        <form action="{{ route('katip.isler.teklif_ver', $islem->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="jeton" class="form-label">Teklif (Jeton)</label>
                                                <input type="number" name="jeton" id="jeton" class="form-control" min="1" required placeholder="Teklifinizi girin...">
                                            </div>
                                            <div class="mb-3">
                                                <label for="mesaj" class="form-label">Mesaj (isteğe bağlı)</label>
                                                <textarea name="mesaj" id="mesaj" class="form-control" rows="3" placeholder="Teklifinizle ilgili bir mesaj yazabilirsiniz..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Teklif Ver</button>
                                        </form>
                                    </div>
                                @else
                                    @if($aktifTeklif->durum === 'bekliyor')
                                        <div class="alert alert-info">Teklifiniz avukat tarafından onay bekliyor.</div>
                                    @elseif($aktifTeklif->durum === 'kabul')
                                        <div class="alert alert-success">Teklifiniz avukat tarafından kabul edildi, lütfen teslimat yapın.</div>
                                    @endif
                                @endif
                            @else
                                <div class="alert alert-info">Teklif verme işlemi bu aşamada mümkün değil.</div>
                            @endif

                            @forelse($islem->teklifler as $teklif)
                                <div class="border-bottom pb-2 mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        @if($teklif->katip->avatar)
                                            <img src="{{ asset($teklif->katip->avatar->path) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                {{ strtoupper(substr($teklif->katip->username ?? '?', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $teklif->katip->username }}</h6>
                                            <small class="text-muted">{{ $teklif->created_at->format('d.m.Y H:i') }}</small>
                                        </div>
                                    </div>
                                    <p class="mb-1">Teklif: {{ $teklif->jeton }} Jeton</p>
                                    @if($teklif->mesaj)
                                        <p class="mb-1"><em>"{{ $teklif->mesaj }}"</em></p>
                                    @endif
                                    <span class="badge bg-{{ $teklif->durum === 'kabul' ? 'success' : ($teklif->durum === 'reddedildi' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($teklif->durum) }}
                </span>
                                </div>
                            @empty
                                <div class="alert alert-info">Henüz teklif yok.</div>
                            @endforelse
                        </div>
                    </div>


                    <!-- Teslimatlar -->
                    @if($islem->durum === 'devam ediyor' || $islem->durum === 'tamamlandi')
                        <div class="card mb-20">
                            <div class="card-header"><h6 class="mb-0"><i class="bi bi-upload me-2"></i>Teslimatlar</h6></div>
                            <div class="card-body">
                                @if($islem->durum === 'devam ediyor' && $islem->teklifler->where('katip_id', auth('katip')->id())->where('durum', 'kabul')->first())
                                    <div class="form-section">
                                        <form action="{{ route('katip.isler.teslimat_yap', $islem->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="teslimat_aciklama" class="form-label">Teslimat Açıklaması</label>
                                                <textarea name="aciklama" id="teslimat_aciklama" class="form-control" rows="3" placeholder="Teslimat detaylarını buraya yazın..." required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="dosya" class="form-label">Dosya Yükle (isteğe bağlı)</label>
                                                <input type="file" name="dosya" id="dosya" class="form-control">
                                            </div>
                                            <button type="submit" class="btn btn-success"><i class="bi bi-upload me-2"></i>Teslimat Yap</button>
                                        </form>
                                    </div>
                                @endif

                                @forelse($islem->teslimatlar as $teslimat)
                                    <div class="border-bottom pb-2 mb-2">
                                        <div class="d-flex align-items-center mb-1">
                                            @if($teslimat->katip->avatar)
                                                <img src="{{ asset($teslimat->katip->avatar->path) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                    {{ strtoupper(substr($teslimat->katip->username ?? '?', 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $teslimat->katip->username }}</h6>
                                                <small class="text-muted">{{ $teslimat->created_at->format('d.m.Y H:i') }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-1">{{ $teslimat->aciklama ?? 'Açıklama yok.' }}</p>
                                        @if($teslimat->dosya_yolu)
                                            <a href="{{ asset($teslimat->dosya_yolu) }}" class="btn btn-primary btn-sm" target="_blank">Dosyayı Görüntüle</a>
                                        @endif
                                    </div>
                                @empty
                                    <div class="alert alert-info">Henüz teslimat yok.</div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                    <!-- Yorumlar -->
                    @if($islem->durum === 'tamamlandi')
                        <div class="card mb-20">
                            <div class="card-header"><h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Yorumlar</h6></div>
                            <div class="card-body">
                                @if($islem->durum !== 'tamamlandi')
                                    <div class="alert alert-warning text-center">
                                        Lütfen işin tamamlanmasını bekleyiniz. İş tamamlandıktan sonra değerlendirme yapabilirsiniz.
                                    </div>
                                @elseif(!$islem->avukat_onay)
                                    <div class="alert alert-warning text-center">
                                        İş teslim edildi, ancak avukat onayı bekleniyor. Onaydan sonra değerlendirme yapabilirsiniz.
                                    </div>
                                @else
                                    <!-- Avukat Yorumları -->
                                    @forelse($islem->avukatPuanlar as $yorum)
                                        <div class="pb-2 mb-2">
                                            <div class="d-flex align-items-center mb-1">
                                                @if($yorum->avukat && optional($yorum->avukat)->avatar)
                                                    <img src="{{ asset($yorum->avukat->avatar->path) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                        {{ strtoupper(substr(optional($yorum->avukat)->username ?? '?', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ optional($yorum->avukat)->username ?? 'Bilinmeyen Avukat' }} <span class="badge bg-success">Avukat</span></h6>
                                                    <small class="text-muted">{{ $yorum->created_at->format('d.m.Y H:i') }}</small>
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star-fill" style="color: {{ $i <= $yorum->puan ? '#ffc107' : '#ddd' }};"></i>
                                                @endfor
                                            </div>
                                            <p class="mb-1">{{ $yorum->yorum ?? 'Yorum yok.' }}</p>
                                        </div>
                                    @empty
                                        <div class="alert alert-info">Henüz avukat yorumu yok.</div>
                                    @endforelse

                                   <hr>
                                    <br/>
                                    @forelse($islem->katipPuanlar as $yorum)
                                        <div class="border-bottom pb-2 mb-2">
                                            <div class="d-flex align-items-center mb-1">
                                                @if($yorum->katip && optional($yorum->katip)->avatar)
                                                    <img src="{{ asset($yorum->katip->avatar->path) }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                        {{ strtoupper(substr(optional($yorum->katip)->username ?? '?', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ optional($yorum->katip)->username ?? 'Bilinmeyen Kâtip' }} <span class="badge bg-info">Kâtip</span></h6>
                                                    <small class="text-muted">{{ $yorum->created_at->format('d.m.Y H:i') }}</small>
                                                </div>
                                            </div>
                                            <div class="mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi bi-star-fill" style="color: {{ $i <= $yorum->puan ? '#ffc107' : '#ddd' }};"></i>
                                                @endfor
                                            </div>
                                            <p class="mb-1">{{ $yorum->yorum ?? 'Yorum yok.' }}</p>
                                        </div>
                                    @empty
                                        <div class="alert alert-info">Henüz kâtip yorumu yok.</div>
                                    @endforelse

                                    <!-- Yorum Yapma Formu -->
                                    @if($islem->durum === 'tamamlandi' && $islem->avukat_onay && !\App\Models\KatipPuan::where('is_id', $islem->id)->where('katip_id', auth('katip')->id())->exists())
                                        <div class="mt-3 form-section">
                                            <h6 class="mb-3"><i class="bi bi-star me-2"></i>İşi Değerlendir</h6>
                                            <form action="{{ route('katip.isler.puanla', $islem->id) }}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label">Puan</label>
                                                    <div class="rating-stars">
                                                        <i class="bi bi-star star" data-value="1"></i>
                                                        <i class="bi bi-star star" data-value="2"></i>
                                                        <i class="bi bi-star star" data-value="3"></i>
                                                        <i class="bi bi-star star" data-value="4"></i>
                                                        <i class="bi bi-star star" data-value="5"></i>
                                                    </div>
                                                    <input type="hidden" name="puan" id="puanInput" value="0">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="yorum" class="form-label">Yorum (isteğe bağlı)</label>
                                                    <textarea name="yorum" id="yorum" class="form-control" rows="3" placeholder="Yorumunuzu buraya yazın..."></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Yorum Yap</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div style="" class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>İş Akışı</h6>
                        </div>
                        <div class="card-body">
                            @if($islem->events->isEmpty())
                                <div class="alert alert-info text-center">Henüz iş akışı kaydı yok.</div>
                            @else
                                <div class="job-flow-list">
                                    @foreach($islem->events->sortBy('created_at') as $event)
                                        <div class="job-flow-item mb-3">
                                            <div class="job-flow-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($event->creator_type && $event->creator->avatar)
                                                        <img src="{{ asset($event->creator->avatar->path) }}" class="rounded-circle" alt="Avatar" style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                            {{ strtoupper(substr($event->creator->username ?? '?', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <h6 class="mb-0">{{ __($event->event_type) }}</h6>
                                                </div>

                                            </div>
                                            @if($event->description)
                                                <p class="text-muted small mb-1 mt-1">{{ $event->description }}</p>
                                            @endif
                                            <small class="text-secondary">Oluşturan: {{ $event->creator->username ?? 'Bilinmeyen' }}  |   {{ $event->created_at->format('d.m.Y H:i') }} </small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection

@section('jsler')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.rating-stars .star');
            const puanInput = document.getElementById('puanInput');

            stars.forEach((star, index) => {
                star.addEventListener('mouseover', () => {
                    stars.forEach((s, i) => {
                        s.classList.toggle('hover', i <= index);
                    });
                });

                star.addEventListener('mouseout', () => {
                    stars.forEach(s => s.classList.remove('hover'));
                });

                star.addEventListener('click', () => {
                    puanInput.value = star.getAttribute('data-value');
                    stars.forEach((s, i) => {
                        s.classList.toggle('selected', i <= index);
                    });
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-desc').forEach(function (toggle) {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    const shortDesc = toggle.previousElementSibling.previousElementSibling;
                    const fullDesc = toggle.previousElementSibling;

                    if (fullDesc.style.display === 'none') {
                        fullDesc.style.display = 'inline';
                        shortDesc.style.display = 'none';
                        toggle.textContent = 'Daha Az';
                    } else {
                        fullDesc.style.display = 'none';
                        shortDesc.style.display = 'inline';
                        toggle.textContent = 'Daha Fazla';
                    }
                });
            });
        });
    </script>
@endsection
