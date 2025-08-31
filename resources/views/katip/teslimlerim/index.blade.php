@extends('katip.layout.katip_master')

@section('title')
    <title>Teslimatlarım | Katip Paneli</title>
@endsection

@section('main')
    <div class="section">


        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h6>Yaptığınız Teslimatlar</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>İşlem Tipi</th>
                            <th>Adliye</th>
                            <th>Açıklama</th>
                            <th>Dosya</th>
                            <th>Tarih</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($teslimler as $teslim)
                            <tr>
                                <td>{{ $teslim->isleri->islem_tipi ?? '-' }}</td>
                                <td>{{ optional($teslim->isleri->adliye)->ad ?? '-' }}</td>
                                <td>{{ Str::limit($teslim->aciklama, 100) ?? '-' }}</td>
                                <td>
                                    @if($teslim->dosya_yolu)
                                        <a href="{{ asset('storage/' . $teslim->dosya_yolu) }}" target="_blank" class="btn btn-sm btn-primary">İndir</a>
                                    @else
                                        <span class="text-muted">Dosya Yok</span>
                                    @endif
                                </td>
                                <td>{{ $teslim->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Henüz bir teslimat yapmadınız.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $teslimler->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
