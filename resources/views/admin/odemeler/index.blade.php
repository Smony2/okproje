@extends('admin.yonetim_master')

@section('title')
    <title>Avukat Ödemeleri | Yönetim Paneli</title>
@endsection

@section('cssler')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 5px;
        }
        .avatar-initial {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
            background: #4A90E2;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 5px;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.25rem 0.5rem;
        }
        .text-center {
            text-align: center;
        }
    </style>
@endsection

@section('main')
    <div class="page-container">
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="header-title mb-0">Avukat Ödemeleri</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>İşlem No</th>
                            <th>Avukat</th>
                            <th>Tutar</th>
                            <th>Tip</th>
                            <th>Tarih</th>
                            <th>Durum</th>

                            <th class="text-center">İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($odemeler as $odeme)
                            @php
                                $typeMap = [
                                    'deposit' => 'Jeton Yükleme',
                                    'withdrawal' => 'Çekim / Kesinti',
                                ];
                                $statusMap = [
                                    'pending'   => ['label' => 'Bekliyor', 'class' => 'bg-warning text-dark'],
                                    'completed' => ['label' => 'Tamamlandı', 'class' => 'bg-success'],
                                    'failed'    => ['label' => 'Başarısız', 'class' => 'bg-danger'],
                                ];

                                $typeText = $typeMap[$odeme->type] ?? $odeme->type;
                                $statusInfo = $statusMap[$odeme->status] ?? ['label' => ucfirst($odeme->status), 'class' => 'bg-secondary'];

                                // Avukat avatar ve username
                                $avukatUsername = optional($odeme->avukat)->username ?? '-';
                                $avukatAvatar = '';
                                if (optional($odeme->avukat)->avatar) {
                                    $avukatAvatar = '<img src="' . asset(optional($odeme->avukat->avatar)->path) . '" class="avatar" alt="Avukat Avatar">';
                                } else {
                                    $avukatAvatar = '<div class="avatar-initial">' . substr(optional($odeme->avukat)->name ?? 'A', 0, 1) . '</div>';
                                }
                            @endphp

                            <tr>
                                <td>#{{ $odeme->id }}</td>
                                <td> {{ $avukatUsername }}</td>
                                <td>{{ number_format($odeme->amount, 2, ',', '.') }}</td>
                                <td>{{ $typeText }}</td>
                                <td>{{ $odeme->created_at->format('d.m.Y H:i') }}</td>
                                <td><span class="badge {{ $statusInfo['class'] }}">{{ $statusInfo['label'] }}</span></td>

                                <td class="text-center">
                                    @if($odeme->status === 'pending')
                                        <form action="{{ route('admin.odeme.approve', $odeme->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Onayla</button>
                                        </form>
                                        <form action="{{ route('admin.odeme.reject', $odeme->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-danger btn-sm">Reddet</button>
                                        </form>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Kayıt bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
