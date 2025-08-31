@extends('avukat.layout.avukat_master')

@section('title')
    <title>Son İşlemler</title>
@endsection

@section('main')

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Son Yapılan İşlemler</h4>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Yapan Admin</th>
                                        <th>İşlem Türü</th>
                                        <th>Model</th>
                                        <th>Tarih</th>
                                        <th>İşlem</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($logs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>{{ optional($log->causer)->name ?? '-' }}</td>
                                            <td>{{ $log->event }}</td>
                                            <td>{{ class_basename($log->subject_type) }}</td>
                                            <td>{{ $log->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                <a href="{{ route('avukat.activity-log.show', $log->id) }}"
                                                   class="btn btn-sm btn-primary">
                                                    İncele
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Henüz işlem kaydı bulunamadı.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $logs->links() }} <!-- Sayfalama -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
