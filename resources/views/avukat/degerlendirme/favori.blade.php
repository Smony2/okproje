@extends('avukat.layout.avukat_master')

@section('title')
    <title>Favori Kâtipler | Avukat Paneli</title>
@endsection

@section('cssler')
    <style>
        /* Genel Stil */
        .container-fluid {
            padding: 0 24px;
        }

        /* Kart Stilleri */
        .katip-card {
            background: linear-gradient(145deg, #ffffff, #f7f9fc);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .katip-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .katip-card .avatar-initial {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #5B86E5, #36D1DC);
            color: #fff;
            font-weight: 700;
            font-size: 2.2rem;
            border: 3px solid #fff;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .katip-card:hover .avatar-initial {
            transform: scale(1.05);
        }

        .katip-card .username {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1a3c34;
            transition: color 0.3s ease;
        }

        .katip-card .username:hover {
            color: #e67e22;
        }

        .katip-card .stats-container {
            background: linear-gradient(90deg, #f1f5f9, #e2e8f0);
            border-radius: 10px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            position: relative;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .katip-card .stats-container::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 15%;
            bottom: 15%;
            width: 1px;
            background: #e67e22;
            opacity: 0.4;
        }

        .katip-card .stat-item {
            text-align: center;
            flex: 1;
        }

        .katip-card .stat-value {
            font-size: 1.15rem;
            font-weight: 600;
            color: #1a3c34;
        }

        .katip-card .stat-label {
            font-size: 0.9rem;
            color: #64748b;
            font-weight: 500;
        }

        .katip-card .online-status {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #fff;
            background: #34c759;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .katip-card .online-status.offline {
            background: #dc3545;
        }

        .katip-card .btn-message {
            background: linear-gradient(90deg, #5B86E5, #36D1DC);
            color: #fff;
            font-weight: 500;
            border-radius: 10px;
            padding: 12px 20px;
            transition: all 0.3s ease;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            font-size: 1rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .katip-card .btn-message:hover {
            background: linear-gradient(90deg, #36D1DC, #5B86E5);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        /* Boş Durum Mesajı */
        .alert-info {
            border: none;
            background: linear-gradient(135deg, #e6f0fa, #f0f9ff);
            color: #1e3a8a;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .alert-info::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #5B86E5, #36D1DC);
        }

        .alert-info i {
            font-size: 2rem;
            margin-bottom: 15px;
            color: #5B86E5;
        }

        /* Responsive Tasarım */
        @media (max-width: 768px) {
            .katip-card .username {
                font-size: 1.15rem;
            }

            .katip-card .avatar-initial {
                width: 80px !important;
                height: 80px !important;
                font-size: 1.8rem !important;
            }

            .katip-card .stats-container {
                flex-direction: column;
                gap: 10px;
            }

            .katip-card .stats-container::before {
                display: none;
            }

            .katip-card .stat-item {
                width: 100%;
            }

            .katip-card .btn-message {
                padding: 10px 16px;
                font-size: 0.95rem;
            }

            .alert-info {
                font-size: 1rem;
                padding: 20px;
            }
        }
        .avatar-img {
            border: 2px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('main')
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Favori Kâtipler</h6>
            <ul class="d-flex align-items-center gap-2">
                <li class="fw-medium">
                    <a href="index.html" class="d-flex align-items-center gap-1 hover-text-primary">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                        Dashboard
                    </a>
                </li>
                <li>-</li>
                <li class="fw-medium">Users List</li>
            </ul>
        </div>

        <div class="card-body p-0">
            @if($katipler->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle d-block"></i>
                    Henüz tamamlanmış işiniz bulunmamaktadır. Tamamlanmış işlerinizden sonra favori kâtipleriniz burada listelenecektir.
                </div>
            @else
                <div class="row gy-4">
                    @foreach($katipler as $katip)
                        <div class="col-xxl-3 col-md-6">
                            <div class="katip-card">
                                <div class="p-20 text-center">
                                    @if($katip->avatar && $katip->avatar->path)
                                        <img src="{{ asset($katip->avatar->path) }}" alt="Avatar" class="w-100-px h-100-px rounded-circle mx-auto mb-3 object-fit-cover avatar-img">
                                    @else
                                        <div class="avatar-initial w-100-px h-100-px rounded-circle mx-auto mb-3">
                                            {{ strtoupper(substr($katip->username, 0, 1)) }}
                                        </div>
                                    @endif
                                    <h6 class="username">
                                        <a href="#" class="text-decoration-none">{{ $katip->username }}</a>
                                    </h6>

                                    <div class="stats-container mt-3">
                                        <div class="stat-item">
                                            <div class="stat-value">{{ $katip->is_sayisi }}</div>
                                            <span class="stat-label">Toplam İş</span>
                                        </div>
                                        <div class="stat-item">
                                            <div style="font-size: 14px" class="stat-value">
                                                {{ \Carbon\Carbon::parse($katip->son_is_tarihi)->format('d.m.Y H:i') }}
                                            </div>
                                            <span class="stat-label">Son İş</span>
                                        </div>
                                    </div>

                                    <a href="{{ route('avukat.chat.index') }}" class="btn-message mt-20">
                                        Mesaj Gönder
                                        <iconify-icon icon="solar:chat-line-linear" class="icon text-xl line-height-1"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
