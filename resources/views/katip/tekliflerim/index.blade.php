@extends('katip.layout.katip_master')

@section('title')
    <title>Tekliflerim | Katip Paneli</title>
@endsection

@section('main')
    <div class="section">


        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h6>Verdiğim Teklifler</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>İşlem Tipi</th>
                            <th>Adliye</th>
                            <th>Jeton</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($teklifler as $teklif)
                            <tr>
                                <td>{{ $teklif->isleri->islem_tipi ?? '-' }}</td>
                                <td>{{ optional($teklif->isleri->adliye)->ad ?? '-' }}</td>
                                <td>{{ $teklif->jeton }}</td>
                                <td>
                                    @php
                                        $statusMap = [
                                            'bekliyor' => ['text-warning', 'Bekliyor'],
                                            'kabul' => ['text-success', 'Kabul Edildi'],
                                            'reddedildi' => ['text-danger', 'Reddedildi'],
                                        ];
                                        [$class, $label] = $statusMap[$teklif->durum] ?? ['text-muted', ucfirst($teklif->durum)];
                                    @endphp
                                    <span class="{{ $class }}">{{ $label }}</span>
                                </td>
                                <td>{{ $teklif->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Henüz teklif vermediniz.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $teklifler->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
