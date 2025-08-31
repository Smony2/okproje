@extends('admin.yonetim_master')

@section('title')
    <title>Avukatlar | Admin Paneli</title>
@endsection

@section('main')
    <div class="row">
        <div class="col-12 mt-3">

            <div class="card radius-16">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Tüm Avukatlar</h6>
                    <a href="{{ route('admin.avukatlar.create') }}"
                       class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <iconify-icon icon="mdi:plus" width="16" height="16"></iconify-icon>
                        Yeni Avukat Ekle
                    </a>
                </div>


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table bordered-table mb-0">
                            <thead>
                            <tr>
                                <th>#ID</th>
                                <th>İsim Soyisim</th>
                                <th>Username</th>

                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Durum</th>
                                <th>Kayıt Tarihi</th>
                                <th>İşlemler</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($avukatlar as $avukat)
                                <tr>
                                    <td>{{ $avukat->id }}</td>
                                    <td>{{ $avukat->name }}</td>
                                    <td>{{ $avukat->username }}</td>

                                    <td>{{ $avukat->email }}</td>
                                    <td>{{ $avukat->phone }}</td>
                                    <td>
                                        @if($avukat->blokeli_mi==0)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Pasif</span>
                                        @endif
                                    </td>
                                    <td>{{ $avukat->created_at->format('d.m.Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.avukatlar.show', $avukat->id) }}"
                                           class="btn btn-sm btn-primary">Görüntüle</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $avukatlar->links('pagination.custom') }}
                </div>

            </div>

        </div>
    </div>
@endsection
