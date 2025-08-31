@extends('avukat.layout.avukat_master')

@section('title')
    <title>İşlem Detayı</title>
@endsection

@section('main')

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>İşlem Detayı
                                <a href="{{ route('avukat.activity-log.index') }}"
                                   class="btn btn-ekle waves-effect waves-light" style="float: right;">
                                    <i class="fas fa-arrow-left"></i> Geri Dön
                                </a>
                            </h4>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ $log->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Yapan Admin</th>
                                        <td>{{ optional($log->causer)->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>İşlem Türü</th>
                                        <td>{{ $log->event }}</td>
                                    </tr>
                                    <tr>
                                        <th>Model</th>
                                        <td>{{ class_basename($log->subject_type) }}</td>
                                    </tr>
                                    <tr>
                                        <th>İşlem Yapılan Kayıt ID</th>
                                        <td>{{ $log->subject_id }}</td>
                                    </tr>
                                    <tr>
                                        <th>İşlem Tarihi</th>
                                        <td>{{ $log->created_at->format('d.m.Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Değişiklikler</th>
                                        <td>
                                            <pre>{{ json_encode($log->properties->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
