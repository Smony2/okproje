@extends('avukat.layout.avukat_master')

@section('title')
    <title>Verdiğim İşler | Avukat Paneli</title>
@endsection

@section('cssler')
    <style>
        .card {
            border-radius: 16px;
            padding-bottom: 50px;
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

        .status-btn {
            padding: 4px 12px;
            border-radius: 16px;
            font-weight: 500;
            font-size: 0.85rem;
            text-transform: capitalize;
            transition: background-color 0.3s ease, transform 0.2s ease;
            border: none;
            cursor: default;
            display: inline-block;
        }
        .status-btn.urgent {
            background-color: #f86767;
            color: #ffffff;
        }
        .status-btn.urgent:hover {
            background-color: #fecdca;
            transform: scale(1.03);
        }
        .status-btn.very-urgent {
            background-color: #e63131;
            color: #ffffff;
        }
        .status-btn.very-urgent:hover {
            background-color: #fca5a5;
            transform: scale(1.03);
        }
        .status-btn.completed {
            background-color: #3cbc3c;
            color: #ffffff;
        }
        .status-btn.completed:hover {
            background-color: #bbf7d0;
            transform: scale(1.03);
        }
        .status-btn.normal {
            background-color: #e5e7eb;
            color: #4b5563;
        }
        .status-btn.normal:hover {
            background-color: #d1d5db;
            transform: scale(1.03);
        }
        .status-btn.waiting {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-btn.waiting:hover {
            background-color: #fde68a;
            transform: scale(1.03);
        }
        .status-btn.ongoing {
            background-color: #dbeafe;
            color: #2563eb;
        }
        .status-btn.ongoing:hover {
            background-color: #bfdbfe;
            transform: scale(1.03);
        }
        .status-btn.cancelled {
            background-color: #bc1826;
            color: #ffffff;
        }
        .status-btn.cancelled:hover {
            background-color: #e5e7eb;
            transform: scale(1.03);
        }
        .custom-btn {
            background: #3b82f6;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 6px 16px;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: capitalize;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }
        .custom-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
            color: #ffffff;

        }
        .custom-btn i {
            font-size: 0.9rem;
        }
        .text-muted {
            color: #6b7280 !important;
            font-size: 0.9rem;
        }
        .header-title {
            font-size: 1.2rem;
            font-weight: 600;
        }
    </style>
@endsection

@section('main')
    <div class="row">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="header-title mb-0">İşleriniz</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                        <tr>
                            <th scope="col">İşlem No</th>
                            <th scope="col">Katip</th>
                            <th scope="col">Avukat</th>
                            <th scope="col">Adliye</th>
                            <th scope="col">İşlem Türü</th>
                            <th scope="col">Aciliyet</th>
                            <th scope="col" class="">Durum</th>
                            <th scope="col" class="">Son İşlem</th>
                            <th scope="col">Detay</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($islerim as $is)
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
                                <td>{{ optional($is->adliye)->ad ?? '-' }}</td>
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
                                            'reddedildi'         => 'status-btn cancelled',

                                        ];
                                        $durumClass = $durumMap[$is->durum] ?? 'status-btn';
                                    @endphp
                                    <span class="{{ $durumClass }}">
                                        {{ ucfirst($is->durum) }}
                                    </span>
                                </td>
                                <td>{{ $is->updated_at ? \Carbon\Carbon::parse($is->updated_at)->format('d.m.Y') : '-' }}</td>
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
                {{ $islerim->links('pagination.custom') }}

            </div>

        </div>
    </div>
@endsection
