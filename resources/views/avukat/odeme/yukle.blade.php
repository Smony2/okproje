@extends('avukat.layout.avukat_master')

@section('title')
    <title>Jeton Yükle | Avukat Paneli</title>
@endsection

@section('cssler')
    <style>
        /* Genel Stil */
        .container-fluid {
            padding: 0 24px;
        }

        /* Kart Stilleri */
        .balance-card,
        .topup-card {
            background: linear-gradient(145deg, #ffffff, #f7f9fc);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .balance-card:hover,
        .topup-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .balance-card .card-header,
        .topup-card .card-header {
            color: #fff;
            border-radius: 16px 16px 0 0;
            padding: 15px 20px;
        }

        /* Bakiye Kartı İçeriği */
        .balance-card .card-body {
            padding: 0;
        }

        .balance-card .balance-content {
            position: relative;
            padding: 40px 20px;
            text-align: center;
            background: url('{{asset("assets/images/home-eleven/bg/bg-orange-gradient.png")}}') no-repeat center/cover;
        }

        .balance-card h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
        }

        .balance-card span {
            font-size: 0.95rem;
            color: #f1f5f9;
        }

        /* Form Elemanları */
        .form-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: #1a3c34;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            font-size: 0.95rem;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #5B86E5;
            box-shadow: 0 0 0 0.2rem rgba(91, 134, 229, 0.25);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: #dc2626;
        }

        .form-note {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 5px;
        }

        /* Alertler */
        .alert-custom.alert-success {
            background: linear-gradient(135deg, #d1fae5, #ecfdf5);
            border: none;
            border-radius: 10px;
            color: #15803d;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .alert-custom.alert-success::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #16a34a, #4ade80);
        }

        .alert-custom.alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fef2f2);
            border: none;
            border-radius: 10px;
            color: #dc2626;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .alert-custom.alert-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #dc2626, #f87171);
        }

        /* Buton */
        .btn-primary {
            background: linear-gradient(90deg, #5B86E5, #36D1DC);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 500;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #36D1DC, #5B86E5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        /* Ödeme Geçmişi Bağlantısı */
        .history-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9rem;
            color: #5B86E5;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .history-link:hover {
            color: #36D1DC;
            text-decoration: underline;
        }

        /* Güvenlik Notu */
        .form-note.security-note {
            font-size: 0.85rem;
            color: #64748b;
            text-align: center;
            margin-top: 20px;
        }

        /* Responsive Tasarım */
        @media (max-width: 768px) {
            .balance-card h3 {
                font-size: 1.5rem;
            }

            .balance-card span {
                font-size: 0.9rem;
            }

            .form-control {
                font-size: 0.9rem;
                padding: 8px 12px;
            }

            .btn-primary {
                font-size: 0.9rem;
                padding: 10px 16px;
            }
        }
    </style>
@endsection

@section('main')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                {{-- Başarı / Hata Mesajları --}}
                @if(session('success'))
                    <div class="alert alert-success alert-custom">
                        🎉 {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-custom">
                        ⚠️ {{ session('error') }}
                    </div>
                @endif

                {{-- Toplam Jeton Kartı --}}
                <div class="card balance-card radius-16 mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-bold text-lg">Toplam Jeton</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="balance-content">
                            <h3>{{ number_format($avukat->balance, 2, ',', '.') }} Jeton</h3>
                            <span>Yeni bir iş talebi oluşturmadan önce jetonlarınızı kontrol edin.</span>
                        </div>
                    </div>
                </div>

                {{-- Jeton Yükle Formu --}}
                <div class="card topup-card mt-10">
                    <div class="card-header">
                        <h6 class="mb-0 fw-bold text-lg">Jeton Yükle</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('avukat.odeme.yukle.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="amount" class="form-label">Yüklemek İstediğiniz Tutar</label>
                                <input
                                    type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    step="0.01"
                                    min="10"
                                    max="50000"
                                    placeholder="100,00"
                                    value="{{ old('amount') }}"
                                    required
                                >
                                @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="form-note mt-1">
                                        Min. 10,00 — Maks. 50.000,00
                                    </div>
                                    @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                Jeton Yükle
                            </button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('avukat.odeme.gecmis') }}" class="history-link">
                            <iconify-icon icon="mdi:history" inline></iconify-icon>
                            Ödeme Geçmişimi Gör
                        </a>
                    </div>
                </div>

                {{-- Footer Güvenlik Notu --}}
                <p class="form-note security-note">
                    🔒 Ödemeleriniz 3D Secure ile korunur. Kart bilgileriniz güvende.
                </p>
            </div>
        </div>
    </div>
@endsection
