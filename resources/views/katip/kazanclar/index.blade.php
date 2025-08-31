@extends('katip.layout.katip_master')

@section('title')
    <title>Kazançlarım</title>
@endsection

@section('main')
    <div class="card radius-16 mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Kazançlarım</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Açıklama</th>
                        <th>Tutar</th>
                        <th>Durum</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($kazanclar as $k)
                        <tr>
                            <td>{{ $k->created_at->format('d.m.Y H:i') }}</td>
                            <td>{{ $k->description ?? 'Kazanç' }}</td>
                            <td><strong class="text-success">+{{ number_format($k->amount, 2, ',', '.') }} ₺</strong></td>
                            <td>
                                    <span class="badge
                                        {{ $k->status === 'tamamlandi' ? 'bg-success' :
                                           ($k->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                        {{ ucfirst($k->status) }}
                                    </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Henüz kazanç yok.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $kazanclar->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
