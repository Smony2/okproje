@extends('avukat.layout.avukat_master')

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
        .kisa-metin {
            display: inline;
        }
        .tam-metin {
            display: none;
        }
        .devamini-goster, .gizle {
            color: #007bff;
            cursor: pointer;
            text-decoration: underline;
            margin-left: 5px;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endsection

@section('title')
    <title>İş Detayı | Avukat Paneli</title>
@endsection

@section('main')
    <div class="row g-3">
        <!-- Sol Sütun (Kâtip Detayı ve İş Detayı) -->
        <div class="col-md-3">
            <!-- İş Bilgileri -->


            <!-- İş Durum -->
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

                    @if($is->durum === 'bekliyor' && !$is->katip_onay)
                        <div class="alert alert-info">İş kâtibe gönderildi, kâtip onayı bekleniyor.</div>
                    @elseif($is->durum === 'devam ediyor' && !$is->teklifler->where('katip_id', $is->katip_id)->isNotEmpty())
                        <div class="alert alert-warning">Kâtip işi onayladı, teklif bekleniyor.</div>
                    @elseif($is->durum === 'devam ediyor' && $is->teklifler->where('katip_id', $is->katip_id)->where('durum', 'bekliyor')->isNotEmpty())
                        <div class="alert alert-info">Kâtip bir teklif verdi, onayınızı bekliyor.</div>
                        <div class="d-flex gap-2">
                            <!-- Teklifi Onayla -->
                            <form action="{{ route('avukat.isler.teklifKabul', ['is_id' => $is->id, 'teklif_id' => $is->teklifler->where('katip_id', $is->katip_id)->where('durum', 'bekliyor')->first()->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Bu teklifi onaylamak istediğinize emin misiniz?')">Teklifi Onayla</button>
                            </form>
                            <!-- Teklifi Reddet -->
                            <form action="{{ route('avukat.isler.teklifReddet', ['is_id' => $is->id, 'teklif_id' => $is->teklifler->where('katip_id', $is->katip_id)->where('durum', 'bekliyor')->first()->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Bu teklifi reddetmek istediğinize emin misiniz?')">Teklifi Reddet</button>
                            </form>
                        </div>
                    @elseif($is->durum === 'devam ediyor' && $is->teklifler->where('katip_id', $is->katip_id)->where('durum', 'kabul')->isNotEmpty())
                        <div class="alert alert-success">Teklifi onayladınız, kâtip teslimat yapmayı bekliyor.</div>
                    @elseif($is->durum === 'tamamlandi' && !$is->avukat_onay)
                        <div class="alert alert-warning">Kâtip işi teslim etti, lütfen inceleyip onaylayın.</div>
                        <form action="{{ route('avukat.isler.onayla', $is->id) }}" method="POST" class="d-grid mb-3">
                            @csrf
                            <button class="btn btn-success"><i class="bi bi-check-circle me-2"></i>İşi Onayla</button>
                        </form>
                    @elseif($is->durum === 'tamamlandi' && $is->avukat_onay && !\App\Models\AvukatPuan::where('is_id', $is->id)->where('avukat_id', auth('avukat')->id())->exists())
                        <div class="alert alert-danger">İşi onayladınız, ancak henüz değerlendirme yapmadınız!</div>
                    @elseif($is->durum === 'tamamlandi' && $is->avukat_onay && \App\Models\AvukatPuan::where('is_id', $is->id)->where('avukat_id', auth('avukat')->id())->exists())
                        <div class="alert alert-success">İş tamamlandı ve değerlendirildi.</div>
                    @elseif($is->durum === 'reddedildi')
                        <div class="alert alert-danger">İş kâtip tarafından reddedildi.</div>
                    @endif
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>İş Bilgileri</h6></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><th>İşlem #</th><td>#{{ $is->id }}</td></tr>
                        <tr><th>Tür</th><td>{{ $is->islem_tipi }}</td></tr>
                        <tr><th>Adliye</th><td>{{ optional($is->adliye)->ad ?? '—' }}</td></tr>
                        <tr><th>Kâtip</th><td>{{ optional($is->katip)->username ?? '—' }}</td></tr>
                        <tr><th>Durum</th><td>{{ ucfirst($is->durum) }}</td></tr>
                        <tr>
                            <th>Açıklama</th>
                            <td>
                                @php
                                    $aciklama = $is->aciklama ?? '—';
                                    $kelimeSayisi = 8;
                                    $kelimeler = str_word_count($aciklama, 1);
                                    if (count($kelimeler) > $kelimeSayisi) {
                                        $kisaMetin = implode(' ', array_slice($kelimeler, 0, $kelimeSayisi));
                                        $tamMetin = $aciklama;
                                @endphp
                                <span class="kisa-metin">{!! $kisaMetin !!}...</span>
                                <a href="#" class="devamini-goster">Devamını Göster</a>
                                <span class="tam-metin" style="display: none;">{!! $tamMetin !!}</span>
                                <a href="#" class="gizle" style="display: none;">Gizle</a>
                                @php
                                    } else {
                                        echo $aciklama;
                                    }
                                @endphp
                            </td>
                        </tr>
                        <tr><th>Oluşturulma</th><td>{{ $is->created_at->format('d.m.Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>

        </div>

        <!-- Sağ Sütun (Tüm İçerik) -->
        <div class="col-md-9">
            <div class="row g-3">
                <!-- Sol Taraf (Teklifler, Teslimatlar, Yorumlar) -->
                <div class="col-md-8">
                    <!-- Teklifler -->
                    <div class="card mb-20">
                        <div class="card-header"><h6 class="mb-0"><i class="bi bi-cash me-2"></i>Teklifler</h6></div>
                        <div class="card-body">
                            @forelse($is->teklifler as $teklif)
                                <div class="border-bottom pb-2 mb-2">
                                    <div class="d-flex align-items-center mb-1">
                                        @if($teklif->katip && optional($teklif->katip->avatar)->path)
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
                    @if($is->durum === 'devam ediyor' || $is->durum === 'tamamlandi')
                        <div class="card mb-20">
                            <div class="card-header"><h6 class="mb-0"><i class="bi bi-upload me-2"></i>Teslimatlar</h6></div>
                            <div class="card-body">
                                @forelse($is->teslimatlar as $teslimat)
                                    <div class="border-bottom pb-2 mb-2">
                                        <div class="d-flex align-items-center mb-1">
                                            @if($teslimat->katip && optional($teslimat->katip->avatar)->path)
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
                    @if($is->durum === 'tamamlandi')
                        <div class="card mb-4">
                            <div class="card-header"><h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Yorumlar</h6></div>
                            <div class="card-body">
                                @if($is->durum !== 'tamamlandi')
                                    <div class="alert alert-warning text-center">
                                        Lütfen işin tamamlanmasını bekleyiniz. İş tamamlandıktan sonra değerlendirme yapabilirsiniz.
                                    </div>
                                @elseif(!$is->avukat_onay)
                                    <div class="alert alert-warning text-center">
                                        İş teslim edildi, ancak onayınızı bekliyor. Onaydan sonra değerlendirme yapabilirsiniz.
                                    </div>
                                @else
                                    <!-- Avukat Yorumları -->
                                    @forelse($is->avukatPuanlar as $yorum)
                                        <div class="border-bottom pb-2 mb-2">
                                            <div class="d-flex align-items-center mb-1">
                                                @if($yorum->avukat && optional($yorum->avukat->avatar)->path)
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
                                        <div class="alert alert-info">Henüz yorum eklemediniz.</div>
                                    @endforelse

                                    <!-- Kâtip Yorumları -->
                                    <br/>
                                    @forelse($is->katipPuanlar as $yorum)
                                        <div class="border-bottom pb-2 mb-2">
                                            <div class="d-flex align-items-center mb-1">
                                                @if($yorum->katip && optional($yorum->katip->avatar)->path)
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
                                        <div class="alert alert-info">Henüz kâtip yorum eklemedi.</div>
                                    @endforelse

                                    <!-- Yorum Yapma Formu -->
                                    @if($is->durum === 'tamamlandi' && $is->avukat_onay && !\App\Models\AvukatPuan::where('is_id', $is->id)->where('avukat_id', auth('avukat')->id())->exists())
                                        <div class="mt-3 form-section">
                                            <h6 class="mb-3"><i class="bi bi-star me-2"></i>İşi Değerlendir</h6>
                                            <form action="{{ route('avukat.isler.puanla', $is->id) }}" method="POST">
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

                <!-- İş Akışı -->
                <div class="col-md-4">
                    <div style="" class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>İş Akışı</h6>
                        </div>
                        <div class="card-body">
                            @if($is->events->isEmpty())
                                <div class="alert alert-info text-center">Henüz iş akışı kaydı yok.</div>
                            @else
                                <div class="job-flow-list">
                                    @foreach($is->events->sortBy('created_at') as $event)
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
        $(document).ready(function() {
            $('.devamini-goster').on('click', function(e) {
                e.preventDefault();
                $(this).siblings('.kisa-metin').hide();
                $(this).siblings('.tam-metin').show();
                $(this).hide();
                $(this).siblings('.gizle').show();
            });

            $('.gizle').on('click', function(e) {
                e.preventDefault();
                $(this).siblings('.kisa-metin').show();
                $(this).siblings('.tam-metin').hide();
                $(this).hide();
                $(this).siblings('.devamini-goster').show();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
