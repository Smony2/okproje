@extends('avukat.layout.avukat_master')

@section('title')
    <title>Katipler | {{ $adliye->ad }}</title>
@endsection

@section('cssler')
    <style>
        .section-header {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 24px;
            position: relative;
            display: inline-block;
        }
        .section-header::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            border-radius: 2px;
        }


        /* Genel Kart Stilleri */
        .katip-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }

        .katip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .katip-card .avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4A90E2, #357ABD);
            color: #fff;
            font-weight: 600;
            font-size: 2rem;
            border: 2px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .katip-card .avatar-img {
            border: 2px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .katip-card .username {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2e4b3f;
            transition: color 0.3s ease;
        }

        .katip-card .username:hover {
            color: #d4a017;
        }

        .katip-card .uzmanlik-alani {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .katip-card .stats-container {
            background: linear-gradient(90deg, #f8f9fa, #e9ecef);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        /* Toplam İş ve Ortalama Puan arasına çizgi ekleme */
        .katip-card .stats-container::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 10%;
            bottom: 10%;
            width: 1px;
            background: #d4a017;
            opacity: 0.5;
        }

        .katip-card .stat-item {
            text-align: center;
            flex: 1;
        }

        .katip-card .stat-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2e4b3f;
        }

        .katip-card .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .katip-card .stat-value.star-rating {
            color: #d4a017;
        }

        .katip-card .btn-request {
            background: linear-gradient(90deg, #4A90E2, #357ABD);
            color: #fff;
            font-weight: 500;
            border-radius: 8px;
            padding: 10px 16px;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .katip-card .btn-request:hover {
            background: linear-gradient(90deg, #357ABD, #2A6395);
            transform: translateY(-2px);
        }

        /* Modal Stilleri */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .modal-title {
            font-weight: 600;
            color: #2e4b3f;
        }

        .modal-body label {
            font-weight: 500;
            color: #2e4b3f;
        }

        .modal-body .form-control,
        .modal-body .form-select {
            border-radius: 8px;
            border: 1px solid #ced4da;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: border-color 0.3s ease;
        }

        .modal-body .form-control:focus,
        .modal-body .form-select:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
            outline: none;
        }

        .modal-footer .btn-secondary {
            border-radius: 8px;
            background: #6c757d;
            color: #fff;
            transition: background 0.3s ease;
        }

        .modal-footer .btn-secondary:hover {
            background: #5a6268;
        }

        .modal-footer .btn-primary {
            border-radius: 8px;
            background: linear-gradient(90deg, #4A90E2, #357ABD);
            color: #fff;
            transition: background 0.3s ease;
        }

        .modal-footer .btn-primary:hover {
            background: linear-gradient(90deg, #357ABD, #2A6395);
        }

        /* Hata Mesajı Stili */
        .error-message {
            display: none;
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 8px;
            text-align: center;
        }

        /* Responsive Tasarım */
        @media (max-width: 768px) {
            .katip-card .username {
                font-size: 1.1rem;
            }

            .katip-card .avatar-initial,
            .katip-card .avatar-img {
                width: 80px !important;
                height: 80px !important;
            }

            .katip-card .stats-container {
                flex-direction: column;
                gap: 8px;
            }

            .katip-card .stats-container::before {
                display: none; /* Mobilde çizgiyi gizle */
            }

            .katip-card .stat-item {
                width: 100%;
            }
        }
    </style>
@endsection

@section('main')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-10">
        <h6 class="section-header">Aktif Katip Listesi</h6>
    </div>


    <div class="card h-100 p-0 radius-12">
        <div class="card-body p-48">
            @if($katipler->isEmpty())
                <div class="alert alert-warning text-center">
                    Bu adliyede şu anda aktif kâtip bulunmamaktadır.
                </div>
            @else
                <div class="row gy-4">
                    @foreach($katipler as $katip)
                        <div class="col-xxl-3 col-md-6">
                            <div class="katip-card">
                                <div class="p-16 text-center">
                                    @if($katip->avatar && $katip->avatar->path)
                                        <img src="{{ asset($katip->avatar->path) }}" alt="Avatar" class="w-100-px h-100-px rounded-circle mx-auto mb-3 object-fit-cover avatar-img">
                                    @else
                                        <div class="avatar-initial w-100-px h-100-px rounded-circle mx-auto mb-3">
                                            {{ strtoupper(substr($katip->username, 0, 1)) }}
                                        </div>
                                    @endif
                                    <h6 class="username">
                                        <a href="{{ route('avukat.katip.profil', $katip->id) }}" class="text-decoration-none">
                                            {{ $katip->username }}
                                        </a>
                                    </h6>
                                    <span class="uzmanlik-alani">{{ $katip->uzmanlik_alani ?? 'Uzmanlık Alanı Belirtilmemiş' }}</span>

                                    <div class="stats-container">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $katip->islemsayisi ?? 0 }}</div>
                                            <span class="stat-label">Tamamlanan İş</span>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value star-rating">
                                                @if($katip->ortalama_puan)
                                                    {{ number_format($katip->ortalama_puan, 1) }} ⭐
                                                @else
                                                    0
                                                @endif
                                            </div>
                                            <span class="stat-label">Ortalama Puan</span>
                                        </div>
                                    </div>

                                    <button data-katip-id="{{ $katip->id }}" class="btn-request mt-16 is-talep-et">
                                        İş Talep Et
                                        <iconify-icon icon="solar:alt-arrow-right-linear" class="icon text-xl line-height-1"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- İş Talep Et Modalı -->
    <div class="modal fade" id="isTalepModal" tabindex="-1" aria-labelledby="isTalepModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="isTalepForm" method="POST" action="{{ route('avukat.isler.store') }}">
                @csrf
                <input type="hidden" name="katip_id" id="modalKatipId">
                <input type="hidden" name="adliye_id" value="{{ $adliye->id }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="isTalepModalLabel">İş Talebi Gönder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body">
                        <div id="errorMessage" class="error-message"></div>

                        <div class="mb-3">
                            <label for="islem_tipi" class="form-label">İşlem Türü</label>
                            <select name="islem_tipi" id="islem_tipi" class="form-select" required>
                                <option value="">Lütfen Seçiniz</option>
                                <option value="Evrak Takibi">Evrak Takibi</option>
                                <option value="Duruşma Listesi Alımı">Duruşma Listesi Alımı</option>
                                <option value="Dosya Kontrolü">Dosya Kontrolü</option>
                                <option value="Mahkeme Evrakı Teslimi">Mahkeme Evrakı Teslimi</option>
                                <option value="Diğer">Diğer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="aciliyet" class="form-label">Aciliyet</label>
                            <select name="aciliyet" id="aciliyet" class="form-select" required>
                                <option value="Normal">Normal</option>
                                <option value="Acil">Acil</option>
                                <option value="Çok Acil">Çok Acil</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="aciklama" class="form-label">İş Açıklaması</label>
                            <textarea maxlength="155" name="aciklama" id="aciklama" class="form-control" rows="4" required placeholder="İş detaylarını buraya yazın..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Vazgeç</button>
                        <button id="submitBtn" type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <span class="btn-label">Gönder</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('jsler')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // İş talep et butonlarına tıklama olayını ekle
            const buttons = document.querySelectorAll('.is-talep-et');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const katipId = this.getAttribute('data-katip-id');
                    document.getElementById('modalKatipId').value = katipId;
                    document.getElementById('errorMessage').style.display = 'none'; // Hata mesajını gizle
                    var myModal = new bootstrap.Modal(document.getElementById('isTalepModal'));
                    myModal.show();
                });
            });

            // Form gönderim olayını yakala
            const form = document.getElementById('isTalepForm');
            const submitBtn = document.getElementById('submitBtn');
            const label = submitBtn.querySelector('.btn-label');
            const spinner = submitBtn.querySelector('.spinner-border');
            const errorMessage = document.getElementById('errorMessage');

            form.addEventListener('submit', async function (event) {
                event.preventDefault(); // Varsayılan form gönderimini durdur

                if (form.checkValidity()) {
                    submitBtn.disabled = true;
                    label.classList.add('d-none');
                    spinner.classList.remove('d-none');
                    errorMessage.style.display = 'none';

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });

                        const result = await response.json();

                        if (response.ok) {
                            // Başarılıysa yönlendirme yap
                            window.location.href = result.redirect;
                        } else {
                            // Hata varsa modal içinde göster
                            errorMessage.textContent = result.message || 'Bir hata oluştu.';
                            errorMessage.style.display = 'block';
                            submitBtn.disabled = false;
                            label.classList.remove('d-none');
                            spinner.classList.add('d-none');
                        }
                    } catch (error) {
                        console.error('Hata:', error);
                        errorMessage.textContent = 'Bir hata oluştu, lütfen tekrar deneyin.';
                        errorMessage.style.display = 'block';
                        submitBtn.disabled = false;
                        label.classList.remove('d-none');
                        spinner.classList.add('d-none');
                    }
                } else {
                    form.reportValidity(); // Tarayıcı doğrulama mesajlarını göster
                }
            });
        });
    </script>
@endsection
