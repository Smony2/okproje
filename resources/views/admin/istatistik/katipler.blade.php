@extends('admin.yonetim_master')

@section('title')
    <title>Katip İstatistikleri</title>
@endsection

@section('main')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
        <h6 class="fw-bold mb-0">Katip İstatistikleri</h6>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-semibold">Filtreler</div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Başlangıç</label>
                    <input type="date" name="date_from" value="{{ request('date_from', optional($from)->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bitiş</label>
                    <input type="date" name="date_to" value="{{ request('date_to', optional($to)->format('Y-m-d')) }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Adliye</label>
                    <select name="adliye_id" class="form-select">
                        <option value="">Tümü</option>
                        @foreach($adliyeler as $ad)
                            <option value="{{ $ad->id }}" @selected((string)request('adliye_id')===(string)$ad->id)>{{ $ad->ad }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Katip</label>
                    <select name="katip_id" class="form-select">
                        <option value="">Tümü</option>
                        @foreach($katipler as $k)
                            <option value="{{ $k->id }}" @selected((string)request('katip_id')===(string)$k->id)>{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Ara (ad, kullanıcı adı, e‑posta)</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Örn. Ahmet…">
                </div>
                <div class="col-md-6 d-flex align-items-end justify-content-end">
                    <button class="btn btn-primary">Uygula</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-semibold">Sonuçlar</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:40%">Katip</th>
                            <th style="width:20%">Tamamlanan İş</th>
                            <th style="width:25%">Son Tamamlama</th>
                            <th style="width:15%">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $row)
                            @php $k = $katipMap[$row->katip_id] ?? null; @endphp
                            <tr>
                                <td>
                                    @if($k)
                                        <strong>{{ $k->name }}</strong>
                                        <div class="text-muted small">{{ $k->username }}</div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $row->job_count }}</td>
                                <td>{{ $row->last_completed_at ? \Carbon\Carbon::parse($row->last_completed_at)->format('d.m.Y H:i') : '—' }}</td>
                                <td>
                                    @if($k)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.katipler.show', $k->id) }}">Detaya Git</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Kayıt bulunamadı.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($records->hasPages())
            <div class="card-footer">
                {{ $records->links() }}
            </div>
        @endif
    </div>
@endsection


