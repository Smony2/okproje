{{-- resources/views/admin/adliyeler/index.blade.php --}}
@extends('admin.yonetim_master')

@section('title')
    <title>Adliyeler | Admin Paneli</title>
@endsection

@section('main')
    <div class="page-container mt-2">


        <div class="card">

            <div class="d-flex justify-content-between align-items-center mb-0 card-header">
                <h6>Adliyeler Listesi</h6>
                <a href="{{ route('admin.adliyeler.create') }}" class="btn btn-primary">Yeni Adliye Ekle</a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ad</th>
                            <th>İl</th>
                            <th>İlçe</th>
                            <th>Telefon</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($adliyeler as $adliye)
                            <tr>
                                <td>{{ $adliye->id }}</td>
                                <td>{{ $adliye->ad }}</td>
                                <td>{{ $adliye->il }}</td>
                                <td>{{ $adliye->ilce }}</td>
                                <td>{{ $adliye->telefon }}</td>
                                <td>
                                    <span class="badge {{ $adliye->aktif_mi ? 'bg-success' : 'bg-danger' }}">
                                        {{ $adliye->aktif_mi ? 'Aktif' : 'Pasif' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.adliyeler.edit', $adliye->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                                    <form action="{{ route('admin.adliyeler.destroy', $adliye->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
