@extends('avukat.layout.avukat_master')

@section('title')
    <title>Adliye Seç</title>
@endsection

@section('cssler')
    <style>
        /* Genel container için stil */
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

        /* Kartlar için modern tasarım */
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        /* Fotoğraf alanı */
        .image-container {
            position: relative;
            overflow: hidden;
            height: 160px;
            background-color: #f3f4f6;
        }
        .card-img-top {
            object-fit: cover;
            height: 100%;
            width: 100%;
            transition: transform 0.5s ease;
        }
        .image-container::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.4));
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        .image-container:hover .card-img-top {
            transform: scale(1.05);
        }
        .image-container:hover::after {
            opacity: 0.8;
        }

        /* Görsel olmayan alan */
        .no-image {
            background: linear-gradient(135deg, #e2e8f0, #f1f5f9);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 160px;
            position: relative;
            overflow: hidden;
        }

        .no-image .text-muted {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 500;
            z-index: 1;
        }

        /* Kart içeriği */
        .card-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #ffffff;
        }
        .card-title2 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }


        /* Katip listesi */
        .katip-list {
            font-size: 0.9rem;
            color: #555;
        }
        .katip-list .active-katipler {
            font-size: 0.85rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }
        .katip-list .katip-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #0b501c;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 13px;
            margin: 0 4px 4px 0;
            transition: background 0.3s ease;
            font-weight: 500;
        }
        .katip-list .katip-chip .dot {
            width: 8px;
            height: 8px;
            background: #0f872e;
            border-radius: 50%;
        }
        .katip-list .katip-chip:hover {
            background: #d1fae5;
        }

        /* Buton tasarımı */
        .btn-adliye-sec {
            background: linear-gradient(135deg, #4A90E2, #357ABD);
            color: white;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }
        .btn-adliye-sec:hover {
            background: linear-gradient(90deg, #357ABD, #2A6395);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            color: white;
        }
        .btn-adliye-sec iconify-icon {
            transition: transform 0.3s ease;
        }
        .btn-adliye-sec:hover iconify-icon {
            transform: translateX(4px);
        }

        /* Diğer metinler */
        .text-muted {
            font-size: 0.85rem;
            color: #9ca3af;
        }

        /* Responsive ayarlar */
        @media (max-width: 768px) {
            .card-title {
                font-size: 1.1rem;
            }
            .btn-adliye-sec {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            .katip-list .katip-chip {
                font-size: 0.75rem;
                padding: 3px 8px;
            }
            .image-container,
            .no-image {
                height: 140px;
            }
        }
    </style>
@endsection

@section('main')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-10">
        <h6 class="section-header">Adliye Seçim</h6>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="row gx-3 gy-4">
                @foreach($adliyeler as $adliye)
                    <div class="col-xxl-2 col-sm-6">
                        <div class="card border h-100">
                            @if($adliye->resimyol)
                                <div class="image-container">
                                    <img src="{{ asset($adliye->resimyol) }}"
                                         class="card-img-top"
                                         alt="{{ $adliye->ad }}">
                                </div>
                            @else
                                <div class="no-image">
                                    <small class="text-muted">Görsel Yok</small>
                                </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title mb-0">{{ $adliye->ad }}</h6>
                                <div class="katip-list">
                                    @if($adliye->katipler->isEmpty())
                                        <small class="text-muted">Hiç aktif katip yok</small>
                                    @else
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($adliye->katipler as $katip)
                                                <span class="katip-chip">
                                                    <span class="dot"></span>
                                                    {{ $katip->username }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-auto text-center">
                                    <a href="{{ route('avukat.isler.listele', $adliye->id) }}"
                                       class="btn btn-adliye-sec w-100">
                                        Adliye Seç
                                        <iconify-icon icon="iconamoon:arrow-right-2" class="text-xl"></iconify-icon>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
