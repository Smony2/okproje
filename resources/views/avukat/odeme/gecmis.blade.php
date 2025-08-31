@extends('avukat.layout.avukat_master')

@section('title')
    <title>Ödeme Geçmişi | Avukat Paneli</title>
@endsection



@section('main')

    <div class="page-container">
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="header-title mb-0">Ödeme Geçmişi</h6>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table bordered-table mb-0">
                        <thead>
                        <tr>
                            <th scope="col">İşlem No</th>
                            <th scope="col">Jeton</th>
                            <th scope="col">Type</th>

                            <th scope="col">Durum</th>
                            <th scope="col">Tarih</th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($transactions as $trx)
                            <tr>
                                <td>#{{ $trx->id }}</td>

                                <td>{{ number_format($trx->amount, 2, ',', '.') }}</td>
                                <td>
                                    @php
                                        $typeMap = [
                                            'deposit' => 'Jeton Yükleme',
                                            'withdrawal' => 'Çekim / Kesinti',
                                        ];
                                    @endphp
                                    {{ $typeMap[$trx->type] ?? $trx->type }}
                                </td>

                                <td>
                                    @php
                                        $statusMap = [
                                            'pending'   => ['label' => 'Bekliyor', 'class' => 'bg-warning text-dark'],
                                            'completed' => ['label' => 'Tamamlandı', 'class' => 'bg-success'],
                                            'failed'    => ['label' => 'Başarısız', 'class' => 'bg-danger'],
                                        ];

                                        $statusInfo = $statusMap[$trx->status] ?? ['label' => ucfirst($trx->status), 'class' => 'bg-secondary'];
                                    @endphp
                                    <span class="badge {{ $statusInfo['class'] }}">
        {{ $statusInfo['label'] }}
    </span>
                                </td>

                                <td>{{ $trx->created_at->format('d.m.Y H:i') }}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>



        </div>
    </div>

@endsection
