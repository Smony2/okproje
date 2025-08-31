@extends('admin.yonetim_master')

@section('title')
    <title>Tüm İşler</title>
@endsection
@section('main')

    <div class="section">


        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h6>Tüm İşler</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>İşlem Türü</th>
                            <th>Avukat</th>
                            <th>Katip</th>
                            <th>Adliye</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($isler as $is)
                            <tr>
                                <td>{{ $is->id }}</td>
                                <td>{{ $is->islem_tipi }}</td>
                                <td>{{ optional($is->avukat)->username }}</td>
                                <td>{{ optional($is->katip)->username }}</td>
                                <td>{{ optional($is->adliye)->ad }}</td>
                                <td>{{ ucfirst($is->durum) }}</td>

                                <td style="">
                                    <a href="{{ route('admin.isler.show', $is->id) }}"
                                       class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light" class="icon"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{ $isler->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
