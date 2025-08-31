@extends('katip.layout.katip_master')

@section('title')
    <title>Katip Paneli | Adliye Dijital Takip Sistemi</title>
@endsection

@section('cssler')
    <style>
        /* Mevcut stiller korundu */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Modern Status Buttons */
        .status-btn {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: capitalize;
            transition: all 0.3s ease;
            border: none;
            cursor: default;
            display: inline-block;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .status-btn.urgent { background: linear-gradient(135deg, #f86767, #e53e3e); color: #ffffff; }
        .status-btn.very-urgent { background: linear-gradient(135deg, #e63131, #c53030); color: #ffffff; }
        .status-btn.completed { background: linear-gradient(135deg, #48bb78, #38a169); color: #ffffff; }
        .status-btn.normal { background: linear-gradient(135deg, #e2e8f0, #cbd5e0); color: #4a5568; }
        .status-btn.waiting { background: linear-gradient(135deg, #fbd38d, #f6ad55); color: #744210; }
        .status-btn.ongoing { background: linear-gradient(135deg, #63b3ed, #4299e1); color: #ffffff; }
        .status-btn.cancelled { background: linear-gradient(135deg, #bc1826, #9c1626); color: #ffffff; }

        /* Modern Cards */
        .card {
            border-radius: 20px !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            padding: 20px 28px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-bottom: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 20px 20px 0 0 !important;
        }

        .card-body { padding: 28px; }

        /* Modern Table Styles */
        .modern-table {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .modern-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .modern-table thead th {
            color: black;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 16px 20px;
            border: none;
            text-transform: uppercase;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.03) 0%, rgba(59, 130, 246, 0.01) 100%);
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            font-size: 0.9rem;
            border: none;
        }

        .modern-table tbody tr:last-child {
            border-bottom: none;
        }

        /* Chart Containers */
        .modern-chart-card {
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .modern-chart-card:hover {
            transform: translateY(-3px);
        }

        .modern-chart-container {
            position: relative;
            min-height: 350px;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 16px;
        }

        .modern-chart-container.small {
            min-height: 250px;
        }

        /* Chart Legends */
        .chart-legend-modern {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .legend-item {
            position: relative;
            padding-left: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #6b7280;
        }

        .legend-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }

        .legend-item.toplam::before { background: #3b82f6; }
        .legend-item.tamamlanan::before { background: #10b981; }
        .legend-item.bekleyen::before { background: #f59e0b; }

        /* Chart Legend List */
        .chart-legend-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 150px;
            overflow-y: auto;
        }

        .legend-list-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 12px;
            font-size: 0.85rem;
            transition: transform 0.2s ease;
        }

        .legend-list-item:hover {
            transform: translateX(4px);
        }

        .legend-color {
            width: 14px;
            height: 14px;
            border-radius: 4px;
            flex-shrink: 0;
        }

        .legend-text {
            flex-grow: 1;
            font-weight: 500;
            color: #374151;
        }

        .legend-value {
            font-weight: 700;
            color: #1f2937;
        }

        /* Rating System */
        .rating-summary {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avg-rating {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f59e0b;
        }

        .rating-stars {
            display: flex;
            gap: 4px;
        }

        .star-filled {
            color: #f59e0b;
            font-size: 1.2rem;
        }

        .star-empty {
            color: #d1d5db;
            font-size: 1.2rem;
        }

        .rating-breakdown {
            padding: 20px 0;
        }

        .rating-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .rating-label {
            min-width: 80px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #4b5563;
        }

        .rating-bar {
            flex: 1;
            height: 10px;
            background: #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .rating-fill {
            height: 100%;
            background: linear-gradient(90deg, #f59e0b, #d97706);
            border-radius: 6px;
            transition: width 0.8s ease;
        }

        .rating-count {
            min-width: 40px;
            text-align: right;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1f2937;
        }

        /* Trend Components */
        .trend-badge {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .trend-badge.positive {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }

        .trend-badge.negative {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        /* Activity Summary */
        .activity-summary {
            display: flex;
            justify-content: space-around;
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-radius: 16px;
            margin-top: 16px;
        }

        .activity-stat {
            text-align: center;
        }

        .stat-label {
            display: block;
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 6px;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Earnings Summary */
        .earnings-summary .earning-total {
            font-size: 1.1rem;
            font-weight: 600;
            color: #059669;
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            padding: 8px 16px;
            border-radius: 12px;
        }

        /* Icon fixes */
        .card-header h6 {
            display: flex !important;
            align-items: center;
            gap: 8px;
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .card-header h6 iconify-icon {
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        iconify-icon {
            display: inline-block !important;
            vertical-align: middle;
        }

        /* Notification Improvements */
        .notification-container {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(59, 130, 246, 0.3) transparent;
        }

        .notification-container::-webkit-scrollbar {
            width: 4px;
        }

        .notification-container::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 2px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-table {
                display: block;
                width: 100%;
            }
            .modern-table thead {
                display: none;
            }
            .modern-table tbody,
            .modern-table tr,
            .modern-table td {
                display: block;
                width: 100%;
                border: none;
            }
            .modern-table tr {
                margin-bottom: 16px;
                padding: 16px;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                background: white;
            }
            .modern-table td {
                padding: 8px 0 !important;
                position: relative;
                text-align: left;
            }

            .card-body {
                padding: 20px;
            }
            .card-header {
                padding: 16px 20px;
            }

            .modern-chart-container {
                min-height: 280px;
                padding: 16px;
            }

            .chart-legend-modern {
                gap: 12px;
            }

            .rating-summary {
                flex-direction: column;
                gap: 8px;
            }

            .activity-summary {
                flex-direction: column;
                gap: 16px;
            }
        }

        /* Modern Bildirim Stilleri */
        .modern-notification-item {
            transition: all 0.3s ease;
            position: relative;
            background: #ffffff;
        }

        .modern-notification-item:hover {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.03) 0%, rgba(59, 130, 246, 0.01) 100%);
            transform: translateX(2px);
        }

        .modern-notification-item.unread {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.05) 0%, rgba(59, 130, 246, 0.02) 100%);
            border-left: 4px solid #3b82f6;
        }

        .modern-notification-item.read {
            opacity: 0.85;
        }

        .modern-notification-item:last-child {
            border-bottom: none !important;
        }

        /* Bildirim İkon Wrapper */
        .notification-icon-wrapper {
            flex-shrink: 0;
        }

        .modern-notification-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .modern-notification-icon.text-primary {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #3b82f6;
        }

        .modern-notification-icon.text-success {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #10b981;
        }

        .modern-notification-icon.text-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #ef4444;
        }

        .modern-notification-icon.text-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #f59e0b;
        }

        .modern-notification-icon.text-info {
            background: linear-gradient(135deg, #e0f2fe, #b3e5fc);
            color: #0ea5e9;
        }

        /* Bildirim İçeriği */
        .notification-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .notification-message {
            font-size: 0.9rem;
            color: #4b5563;
            line-height: 1.5;
            margin: 0;
        }

        .notification-footer {
            margin: 0;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #6b7280;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .notification-action {
            font-size: 0.8rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            transition: color 0.2s ease;
        }

        .notification-action:hover {
            color: #2563eb;
            text-decoration: none;
        }

        .notification-dot {
            width: 10px;
            height: 10px;
            background: #3b82f6;
            border-radius: 50%;
            flex-shrink: 0;
            animation: pulse 2s infinite;
        }

        /* Boş Durum */
        .empty-notifications .empty-icon {
            font-size: 4rem;
            opacity: 0.4;
        }

        /* Pulse Animasyonu */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Scroll Bar */
        .notification-container::-webkit-scrollbar {
            width: 4px;
        }

        .notification-container::-webkit-scrollbar-track {
            background: transparent;
        }

        .notification-container::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.3);
            border-radius: 2px;
        }

        /* Mobil Uyumluluk */
        @media (max-width: 768px) {
            .modern-notification-item {
                padding: 16px 20px !important;
            }

            .modern-notification-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .notification-title {
                font-size: 0.95rem;
            }

            .notification-message {
                font-size: 0.85rem;
            }

            .notification-footer {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 12px !important;
            }

            .notification-container {
                max-height: 300px !important;
            }
    </style>
@endsection

@section('main')
    <!-- İstatistik Kartları -->
    <div class="row">
        <div class="col-xxl-12 col-sm-6">
            <div class="row">
                <!-- 1. Toplam İş -->
                <div class="col-xxl-4 col-sm-6 mt-3">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                    <span class="mb-0 w-40-px h-40-px bg-warning-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                        <iconify-icon icon="solar:checklist-linear" class="icon"></iconify-icon>
                                    </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam İş</span>
                                        <h6 class="fw-semibold my-1">{{ $toplamIs }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 2. Bekleyen İşler -->
                <div class="col-xxl-4 col-sm-6 mt-3">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                    <span class="mb-0 w-40-px h-40-px bg-danger-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                        <iconify-icon icon="solar:clock-circle-linear" class="icon"></iconify-icon>
                                    </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Bekleyen İşler</span>
                                        <h6 class="fw-semibold my-1">{{ $bekleyenIs }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 3. Toplam Kazanç -->
                <div class="col-xxl-4 col-sm-6 mt-3">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                    <span class="mb-0 w-40-px h-40-px bg-success-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                        <iconify-icon icon="streamline:bag-dollar-solid" class="icon"></iconify-icon>
                                    </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam Kazanç</span>
                                        <h6 class="fw-semibold my-1">{{ number_format($toplamKazanc, 2, ',', '.') }} ₺</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 4. Bekleyen Teklif -->
                <div class="col-xxl-4 col-sm-6 mt-3">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                    <span class="mb-0 w-40-px h-40-px bg-info-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                        <iconify-icon icon="fluent:mail-unread-16-filled" class="icon"></iconify-icon>
                                    </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Bekleyen Teklif</span>
                                        <h6 class="fw-semibold my-1">{{ $performansMetrikleri['aktif_is_sayisi'] ?? 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 5. Yorum Yapılan Avukat -->
                <div class="col-xxl-4 col-sm-6 mt-3">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                    <span class="mb-0 w-40-px h-40-px bg-primary-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                        <iconify-icon icon="mdi:comment-check" class="icon"></iconify-icon>
                                    </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Yorum Yapılan Avukat</span>
                                        <h6 class="fw-semibold my-1">{{ $yorumYapilanAvukat }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 6. Toplam Jeton -->
                <div class="col-xxl-4 col-sm-6 mt-3">
                    <div class="card px-24 py-16 shadow-none border h-100">
                        <div class="card-body p-0">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="w-64-px h-64-px radius-16 bg-base-50 d-flex justify-content-center align-items-center me-20">
                                    <span class="mb-0 w-40-px h-40-px bg-cyan-600 flex-shrink-0 text-white d-flex justify-content-center align-items-center radius-8 h6 mb-0">
                                        <iconify-icon icon="solar:wallet-bold" class="icon"></iconify-icon>
                                    </span>
                                    </div>
                                    <div>
                                        <span class="mb-2 fw-medium text-secondary-light text-md">Toplam Jeton</span>
                                        <h6 class="fw-semibold my-1">{{ number_format(auth('katip')->user()->balance ?? 0, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ana Dashboard İçeriği -->
    <div class="mt-24">
        <div class="row gy-4">
            <!-- Sol Kolon - Tablolar -->
            <div class="col-xl-8">
                <div class="row gy-4">
                    <!-- Son İşler -->
                    <div class="col-md-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:list-check-bold" class="me-2 text-primary"></iconify-icon>
                                        Son İşler
                                    </h6>
                                    <a href="{{route('katip.isler.tumu')}}" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                        Tümünü Göster <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table modern-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">İşlem No</th>
                                            <th scope="col">Avukat</th>
                                            <th scope="col">Kâtip</th>
                                            <th scope="col">İşlem Türü</th>
                                            <th scope="col">Aciliyet</th>
                                            <th scope="col">Durum</th>
                                            <th scope="col">Detay</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($islerim as $is)
                                            <tr>
                                                <td data-label="İşlem No">
                                                    <span class="fw-bold text-primary">#{{ $is->id }}</span>
                                                </td>
                                                <td data-label="Avukat">
                                                    <div class="d-flex align-items-center">
                                                        @if($is->avukat && $is->avukat->avatar)
                                                            <img src="{{ asset($is->avukat->avatar->path) }}"
                                                                 alt="{{ $is->avukat->username }}"
                                                                 class="avatar">
                                                        @else
                                                            <div class="avatar bg-primary text-white d-flex align-items-center justify-content-center">
                                                                {{ strtoupper(substr($is->avukat->username ?? '?', 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <span class="text-lg text-secondary-light fw-semibold flex-grow-1">
                                                            <a href="{{ route('katip.avukatlar.profil', $is->avukat->id) }}"
                                                               class="text-decoration-none text-dark hover-text-primary">
                                                                {{ $is->avukat->username }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td data-label="Kâtip">
                                                    <div class="d-flex align-items-center">
                                                        @if($is->katip && $is->katip->avatar)
                                                            <img src="{{ asset($is->katip->avatar->path) }}"
                                                                 alt="{{ $is->katip->username }}"
                                                                 class="avatar">
                                                        @else
                                                            <div class="avatar bg-primary text-white d-flex align-items-center justify-content-center">
                                                                {{ strtoupper(substr($is->katip->username ?? '?', 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <span class="text-lg text-secondary-light fw-semibold flex-grow-1">
                                                            {{ $is->katip->username }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td data-label="İşlem Türü">
                                                    <span class="fw-medium">{{ $is->islem_tipi }}</span>
                                                </td>
                                                <td data-label="Aciliyet">
                                                    @php
                                                        $acilMap = [
                                                            'Acil' => 'status-btn urgent',
                                                            'Normal' => 'status-btn normal',
                                                            'Çok Acil' => 'status-btn very-urgent',
                                                            'Düşük' => 'status-btn waiting',
                                                        ];
                                                        $acilClass = $acilMap[$is->aciliyet] ?? 'status-btn normal';
                                                    @endphp
                                                    <span class="{{ $acilClass }}">{{ $is->aciliyet ?? 'Normal' }}</span>
                                                </td>
                                                <td data-label="Durum">
                                                    @php
                                                        $durumMap = [
                                                            'bekliyor' => 'status-btn waiting',
                                                            'devam ediyor' => 'status-btn ongoing',
                                                            'tamamlandi' => 'status-btn completed',
                                                            'reddedildi' => 'status-btn cancelled',
                                                        ];
                                                        $durumClass = $durumMap[$is->durum] ?? 'status-btn';
                                                    @endphp
                                                    <span class="{{ $durumClass }}">{{ ucfirst($is->durum) }}</span>
                                                </td>
                                                <td data-label="Detay">
                                                    <a href="{{ route('katip.isler.detay', $is->id) }}" class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                                        <iconify-icon icon="iconamoon:eye-light" class="icon"></iconify-icon>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <iconify-icon icon="solar:file-text-linear" class="text-4xl mb-2 opacity-50"></iconify-icon>
                                                    <br>Henüz iş talebi vermediniz.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2 Sütunlu Grafik Düzeni -->
                    <div class="col-lg-6">
                        <!-- İş Türü Dağılımı -->
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:pie-chart-2-bold" class="me-2 text-success"></iconify-icon>
                                        İş Türü Dağılımı
                                    </h6>
                                    <div class="chart-info">
                                        <span class="badge bg-light text-dark">{{ $isTuruDagilimi->sum('adet') }} Toplam</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="modern-chart-container small">
                                    <canvas id="isTuruChart"></canvas>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Aylık İş Performansı -->
                    <div class="col-lg-6">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:chart-2-bold" class="me-2 text-primary"></iconify-icon>
                                        Aylık İş Performansı
                                    </h6>
                                    <div class="chart-legend-modern">
                                        <span class="legend-item toplam">Toplam</span>
                                        <span class="legend-item tamamlanan">Tamamlanan</span>
                                        <span class="legend-item bekleyen">Bekleyen</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="modern-chart-container">
                                    <canvas id="modernMonthlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Adliye Dağılımı - Full Width -->
                    <div class="col-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <h6 class="mb-0 fw-bold text-lg">
                                    <iconify-icon icon="solar:buildings-2-bold" class="me-2 text-info"></iconify-icon>
                                    Adliye Bazında İş Dağılımı
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="modern-chart-container small">
                                    <canvas id="adliyeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Son Teklifler -->
                    <div class="col-md-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:document-add-bold" class="me-2 text-purple"></iconify-icon>
                                        Son Teklifler
                                    </h6>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table modern-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">Katip</th>
                                            <th scope="col">Adliye</th>
                                            <th scope="col">Tarih</th>
                                            <th scope="col">Jeton</th>
                                            <th scope="col">Durum</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sonTeklifler as $teklif)
                                            @php
                                                $durumMap = [
                                                    'bekliyor' => ['text-warning-600', 'bg-warning-100', 'status-btn waiting'],
                                                    'kabul' => ['text-success-600', 'bg-success-100', 'status-btn completed'],
                                                    'reddedildi' => ['text-danger-600', 'bg-danger-100', 'status-btn cancelled'],
                                                ];
                                                [$text, $bg, $statusClass] = $durumMap[$teklif->durum] ?? ['text-secondary', 'bg-secondary', 'status-btn'];
                                            @endphp
                                            <tr>
                                                <td data-label="Katip">
                                                    <span class="fw-medium">{{ $teklif->katip->username }}</span>
                                                </td>
                                                <td data-label="Adliye">{{ optional($teklif->isleri->adliye)->ad ?? '-' }}</td>
                                                <td data-label="Tarih">{{ $teklif->created_at->locale('tr')->translatedFormat('d F Y H:i') }}</td>
                                                <td data-label="Jeton">
                                                    <span class="fw-bold text-primary">{{ $teklif->jeton }} Jeton</span>
                                                </td>
                                                <td data-label="Durum">
                                                    <span class="{{ $statusClass }}">
                                                        {{ ucfirst($teklif->durum) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <iconify-icon icon="solar:document-linear" class="text-4xl mb-2 opacity-50"></iconify-icon>
                                                    <br>Henüz teklif yok.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Son Kazançlar -->
                    <div class="col-md-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:dollar-minimalistic-bold" class="me-2 text-success"></iconify-icon>
                                        Son Kazançlar
                                    </h6>
                                    <a href="#" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                        Tümünü Gör <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table modern-table mb-0">
                                        <thead>
                                        <tr>
                                            <th scope="col">Açıklama</th>
                                            <th scope="col">Tarih</th>
                                            <th scope="col">Miktar</th>
                                            <th scope="col">Durum</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($sonKazanc as $item)
                                            <tr>
                                                <td data-label="Açıklama">
                                                    <span class="fw-medium">{{ $item->description ?? 'Kazanç' }}</span>
                                                </td>
                                                <td data-label="Tarih">{{ $item->created_at->locale('tr')->translatedFormat('d F Y H:i') }}</td>
                                                <td data-label="Miktar">
                                                    <span class="fw-bold text-success">+{{ number_format($item->amount, 2, ',', '.') }} ₺</span>
                                                </td>
                                                <td data-label="Durum">
                                                    <span class="status-btn completed">Onaylandı</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    <iconify-icon icon="solar:wallet-linear" class="text-4xl mb-2 opacity-50"></iconify-icon>
                                                    <br>Henüz kazanç yok.
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sağ Kolon - Grafikler ve Bildirimler -->
            <div class="col-xl-4">
                <div class="row gy-4">
                    <!-- Bildirimler -->
                    <div class="col-md-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:bell-linear" class="me-2 text-primary"></iconify-icon>
                                        Son Bildirimler
                                    </h6>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="notification-container">
                                    @forelse($bildirimler as $bildirim)
                                        <div class="modern-notification-item {{ is_null($bildirim->read_at) ? 'unread' : 'read' }} p-3 border-bottom">
                                            <div class="d-flex align-items-start gap-3">
                                                <!-- Bildirim İkonu -->
                                                <div class="notification-icon-wrapper">
                                                    @php
                                                        $iconMap = [
                                                            'is_olusturuldu' => ['solar:file-add-bold', 'text-warning'],
                                                            'teklif_onay' => ['solar:check-circle-bold', 'text-success'],
                                                            'teklif_red' => ['solar:close-circle-bold', 'text-danger'],
                                                            'is_reddedildi' => ['solar:wallet-money-bold', 'text-warning'],
                                                            'mesaj' => ['solar:chat-round-dots-bold', 'text-info'],
                                                            'default' => ['solar:bell-bold', 'text-danger']
                                                        ];
                                                        [$icon, $color] = $iconMap[$bildirim->type] ?? $iconMap['default'];
                                                    @endphp
                                                    <div class="modern-notification-icon text-danger">
                                                        <iconify-icon icon="{{ $icon }}"></iconify-icon>
                                                    </div>
                                                </div>

                                                <!-- Bildirim İçeriği -->
                                                <div class="flex-grow-1">
                                                    <!-- Bildirim Başlığı -->
                                                    <h6 class="notification-title mb-2">
                                                        @switch($bildirim->type)
                                                            @case('is_olusturuldu')
                                                                <iconify-icon icon="solar:file-add-linear" class="me-1"></iconify-icon>
                                                                Yeni İş Talebi
                                                                @break
                                                            @case('is_reddedildi')
                                                                <iconify-icon icon="solar:file-add-linear" class="me-1"></iconify-icon>
                                                                İş Reddedildi
                                                                @break
                                                            @case('teklif_onaylandi')
                                                                <iconify-icon icon="solar:check-circle-linear" class="me-1"></iconify-icon>
                                                                Teklif Onaylandı
                                                                @break
                                                            @case('is_onaylandi')
                                                                <iconify-icon icon="solar:check-circle-linear" class="me-1"></iconify-icon>
                                                                İş Onaylandı
                                                                @break
                                                            @case('teklif_reddedildi')
                                                                <iconify-icon icon="solar:close-circle-linear" class="me-1"></iconify-icon>
                                                                Teklif Reddedildi
                                                                @break
                                                            @case('teklif_verildi')
                                                                <iconify-icon icon="solar:wallet-money-linear" class="me-1"></iconify-icon>
                                                                Teklif Verildi
                                                                @break
                                                            @case('mesaj')
                                                                <iconify-icon icon="solar:chat-round-dots-linear" class="me-1"></iconify-icon>
                                                                Yeni Mesaj
                                                                @break
                                                            @default
                                                                <iconify-icon icon="solar:bell-linear" class="me-1"></iconify-icon>
                                                                Bildirim
                                                        @endswitch
                                                    </h6>

                                                    <!-- Bildirim Mesajı -->
                                                    <p class="notification-message mb-1">
                                                        {{ Str::limit($bildirim->message, 100) }}
                                                    </p>

                                                    <!-- Alt Bilgiler -->
                                                    <div class="notification-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <!-- Tarih -->
                                                            <span class="notification-time">
                                            <iconify-icon icon="solar:clock-circle-linear" class="me-1"></iconify-icon>
                                            {{ $bildirim->created_at->diffForHumans() }}
                                        </span>

                                                            <!-- İş Detayı Linki -->
                                                            @if($bildirim->is_id)
                                                                <a href="{{ route(auth('katip')->check() ? 'katip.isler.detay' : 'avukat.isler.detay', $bildirim->is_id) }}"
                                                                   class="notification-action">
                                                                    <iconify-icon icon="solar:eye-linear" class="me-1"></iconify-icon>
                                                                    İş Detayı
                                                                </a>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <!-- Boş Durum -->
                                        <div class="empty-notifications text-center py-5">
                                            <div class="empty-icon mb-3">
                                                <iconify-icon icon="solar:bell-off-linear" class="text-muted"></iconify-icon>
                                            </div>
                                            <h6 class="text-muted mb-2">Henüz bildirim yok</h6>
                                            <p class="text-muted small mb-0">Yeni bildirimler burada görünecek</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Saatlik Aktivite -->
                    <div class="col-md-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <h6 class="mb-0 fw-bold text-lg">
                                    <iconify-icon icon="solar:clock-circle-bold" class="me-2 text-purple"></iconify-icon>
                                    Saatlik Aktivite
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="modern-chart-container small">
                                    <canvas id="saatlikChart"></canvas>
                                </div>
                                <div class="activity-summary">
                                    <div class="activity-stat">
                                        <span class="stat-label">En Aktif Saat:</span>
                                        <span class="stat-value">{{ array_keys($saatlikVeri, max($saatlikVeri))[0] ?? 0 }}:00</span>
                                    </div>
                                    <div class="activity-stat">
                                        <span class="stat-label">Toplam İş:</span>
                                        <span class="stat-value">{{ array_sum($saatlikVeri) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <!-- Haftalık Trend -->
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:graph-up-bold" class="me-2 text-warning"></iconify-icon>
                                        Haftalık Trend
                                    </h6>
                                    <div class="trend-summary">
                                        @php
                                            $sonHafta = end($haftalikTrend)['toplam'];
                                            $oncekiHafta = count($haftalikTrend) > 1 ? $haftalikTrend[count($haftalikTrend)-2]['toplam'] : 0;
                                            $trend = $oncekiHafta > 0 ? (($sonHafta - $oncekiHafta) / $oncekiHafta) * 100 : 0;
                                        @endphp
                                        <span class="trend-badge {{ $trend >= 0 ? 'positive' : 'negative' }}">
                                            <iconify-icon icon="solar:{{ $trend >= 0 ? 'arrow-up' : 'arrow-down' }}-bold"></iconify-icon>
                                            {{ abs(round($trend, 1)) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="modern-chart-container small">
                                    <canvas id="haftalikTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Puan Analizi -->
                    <div class="col-md-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:star-bold" class="me-2 text-warning"></iconify-icon>
                                        Puan Analizi
                                    </h6>
                                    <div class="rating-summary">
                                        <span class="avg-rating">{{ $puanDagilimi['ortalama'] }}/5</span>
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <iconify-icon icon="solar:star-bold"
                                                              class="{{ $i <= round($puanDagilimi['ortalama']) ? 'star-filled' : 'star-empty' }}">
                                                </iconify-icon>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-7">
                                        <div class="modern-chart-container small">
                                            <canvas id="modernRatingChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="rating-breakdown">
                                            @for($i = 5; $i >= 1; $i--)
                                                <div class="rating-row">
                                                    <span class="rating-label">{{ $i }} Yıldız</span>
                                                    <div class="rating-bar">
                                                        <div class="rating-fill" style="width: {{ $puanDagilimi['yuzdelik'][$i] }}%"></div>
                                                    </div>
                                                    <span class="rating-count">{{ $puanDagilimi['veriler'][$i] }}</span>
                                                </div>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kazanç Trendi -->
                    <div class="col-md-12">
                        <div class="card modern-chart-card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0 fw-bold text-lg">
                                        <iconify-icon icon="solar:dollar-minimalistic-bold" class="me-2 text-success"></iconify-icon>
                                        Kazanç Trendi
                                    </h6>
                                    <div class="earnings-summary">
                                        <span class="earning-total">
                                            Toplam: {{ number_format(collect($kazancTrendi)->sum('kazanc'), 2) }} ₺
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="modern-chart-container">
                                    <canvas id="kazancTrendiChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('jsler')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. İş Türü Pie Chart
        const isTuruCtx = document.getElementById('isTuruChart').getContext('2d');
        const isTuruData = {!! json_encode($isTuruDagilimi->pluck('adet')->toArray()) !!};
        const isTuruLabels = {!! json_encode($isTuruDagilimi->pluck('islem_tipi')->toArray()) !!};

        new Chart(isTuruCtx, {
            type: 'doughnut',
            data: {
                labels: isTuruLabels,
                datasets: [{
                    data: isTuruData,
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 16,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${context.parsed} iş (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutCubic'
                }
            }
        });

        // 2. Haftalık Trend Chart
        const haftalikCtx = document.getElementById('haftalikTrendChart').getContext('2d');
        const haftalikData = {!! json_encode($haftalikTrend) !!};

        new Chart(haftalikCtx, {
            type: 'line',
            data: {
                labels: haftalikData.map(h => h.hafta),
                datasets: [{
                    label: 'Toplam İş',
                    data: haftalikData.map(h => h.toplam),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3
                }, {
                    label: 'Tamamlanan',
                    data: haftalikData.map(h => h.tamamlanan),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 16
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(209, 213, 219, 0.2)' },
                        ticks: { color: '#6b7280', precision: 0 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280' }
                    }
                },
                animation: { duration: 1000, easing: 'easeOutQuart' }
            }
        });

        // 3. Adliye Horizontal Bar Chart
        const adliyeCtx = document.getElementById('adliyeChart').getContext('2d');
        const adliyeData = {!! json_encode($adliyeDagilimi->pluck('is_sayisi')->toArray()) !!};
        const adliyeLabels = {!! json_encode($adliyeDagilimi->pluck('adliye_adi')->toArray()) !!};

        new Chart(adliyeCtx, {
            type: 'bar',
            data: {
                labels: adliyeLabels,
                datasets: [{
                    data: adliyeData,
                    backgroundColor: '#06b6d4',
                    borderRadius: 8,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 16
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(209, 213, 219, 0.2)' },
                        ticks: { color: '#6b7280', precision: 0 }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { color: '#6b7280' }
                    }
                },
                animation: { duration: 1000, easing: 'easeOutQuart' }
            }
        });

        // 4. Saatlik Aktivite Chart
        const saatlikCtx = document.getElementById('saatlikChart').getContext('2d');
        const saatLabels = Array.from({length: 24}, (_, i) => i + ':00');

        const saatlikGradient = saatlikCtx.createLinearGradient(0, 0, 0, 200);
        saatlikGradient.addColorStop(0, 'rgba(139, 92, 246, 0.6)');
        saatlikGradient.addColorStop(1, 'rgba(139, 92, 246, 0.1)');

        new Chart(saatlikCtx, {
            type: 'line',
            data: {
                labels: saatLabels,
                datasets: [{
                    data: {!! json_encode($saatlikVeri) !!},
                    borderColor: '#8b5cf6',
                    backgroundColor: saatlikGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#8b5cf6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 16
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(209, 213, 219, 0.2)' },
                        ticks: { color: '#6b7280', precision: 0 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280' }
                    }
                },
                animation: { duration: 1000, easing: 'easeOutQuart' }
            }
        });

        // 5. Modern Aylık Grafik
        const modernCtx = document.getElementById('modernMonthlyChart').getContext('2d');

        const toplamGradient = modernCtx.createLinearGradient(0, 0, 0, 300);
        toplamGradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
        toplamGradient.addColorStop(1, 'rgba(59, 130, 246, 0.1)');

        const tamamlananGradient = modernCtx.createLinearGradient(0, 0, 0, 300);
        tamamlananGradient.addColorStop(0, 'rgba(16, 185, 129, 0.8)');
        tamamlananGradient.addColorStop(1, 'rgba(16, 185, 129, 0.1)');

        const bekleyenGradient = modernCtx.createLinearGradient(0, 0, 0, 300);
        bekleyenGradient.addColorStop(0, 'rgba(245, 158, 11, 0.8)');
        bekleyenGradient.addColorStop(1, 'rgba(245, 158, 11, 0.1)');

        new Chart(modernCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($aylikVeriler['aylar']) !!},
                datasets: [{
                    label: 'Toplam İş',
                    data: {!! json_encode($aylikVeriler['toplam']) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: toplamGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }, {
                    label: 'Tamamlanan',
                    data: {!! json_encode($aylikVeriler['tamamlanan']) !!},
                    borderColor: '#10b981',
                    backgroundColor: tamamlananGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }, {
                    label: 'Bekleyen',
                    data: {!! json_encode($aylikVeriler['bekleyen']) !!},
                    borderColor: '#f59e0b',
                    backgroundColor: bekleyenGradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 16,
                        titleFont: { size: 14, weight: '600' },
                        bodyFont: { size: 13 },
                        displayColors: true,
                        boxPadding: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(209, 213, 219, 0.2)',
                            borderDash: [4, 4]
                        },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12, weight: '500' },
                            precision: 0
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutCubic'
                }
            }
        });

        // 6. Modern Puan Grafiği
        const ratingCtx = document.getElementById('modernRatingChart').getContext('2d');
        const ratingData = {!! json_encode(array_values($puanDagilimi['veriler'])) !!};

        new Chart(ratingCtx, {
            type: 'doughnut',
            data: {
                labels: ['1 Yıldız', '2 Yıldız', '3 Yıldız', '4 Yıldız', '5 Yıldız'],
                datasets: [{
                    data: ratingData,
                    backgroundColor: [
                        '#ef4444', '#f97316', '#facc15', '#22c55e', '#0ea5e9'
                    ],
                    borderWidth: 0,
                    hoverBorderWidth: 4,
                    hoverBorderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(59, 130, 246, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 16,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${context.parsed} değerlendirme (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1200
                }
            }
        });

        // 7. Kazanç Trendi Chart
        const kazancCtx = document.getElementById('kazancTrendiChart').getContext('2d');
        const kazancData = {!! json_encode($kazancTrendi) !!};

        const kazancGradient = kazancCtx.createLinearGradient(0, 0, 0, 300);
        kazancGradient.addColorStop(0, 'rgba(16, 185, 129, 0.6)');
        kazancGradient.addColorStop(1, 'rgba(16, 185, 129, 0.1)');

        new Chart(kazancCtx, {
            type: 'line',
            data: {
                labels: kazancData.map(k => k.ay),
                datasets: [{
                    label: 'Kazanç (₺)',
                    data: kazancData.map(k => k.kazanc),
                    borderColor: '#10b981',
                    backgroundColor: kazancGradient,
                    borderWidth: 4,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 8,
                    pointHoverRadius: 10,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)',
                        titleColor: '#f9fafb',
                        bodyColor: '#e5e7eb',
                        borderColor: 'rgba(16, 185, 129, 0.3)',
                        borderWidth: 1,
                        cornerRadius: 12,
                        padding: 16,
                        callbacks: {
                            label: function(context) {
                                return `Kazanç: ${context.parsed.y.toLocaleString('tr-TR')} ₺`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(209, 213, 219, 0.2)',
                            borderDash: [4, 4]
                        },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12, weight: '500' },
                            callback: function(value) {
                                return value.toLocaleString('tr-TR') + ' ₺';
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#6b7280',
                            font: { size: 12, weight: '500' }
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutCubic'
                }
            }
        });

        // Animasyonlu sayaç efekti
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.performance-content h3');

            counters.forEach(counter => {
                const target = parseFloat(counter.textContent);
                const duration = 1500;
                const increment = target / (duration / 16);
                let current = 0;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        if (counter.textContent.includes('%')) {
                            counter.textContent = Math.ceil(current) + '%';
                        } else if (counter.textContent.includes('/')) {
                            counter.textContent = current.toFixed(1) + '/5';
                        } else {
                            counter.textContent = Math.ceil(current);
                        }
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = counter.textContent;
                    }
                };

                setTimeout(updateCounter, 500);
            });

            // Card hover animation
            const cards = document.querySelectorAll('.modern-chart-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-3px)';
                    this.style.boxShadow = '0 12px 40px rgba(0, 0, 0, 0.12)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.08)';
                });
            });

            // Table row hover animation
            const tableRows = document.querySelectorAll('.modern-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.background = 'linear-gradient(90deg, rgba(59, 130, 246, 0.03) 0%, rgba(59, 130, 246, 0.01) 100%)';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                    this.style.background = '';
                });
            });
        });
    </script>
@endsection