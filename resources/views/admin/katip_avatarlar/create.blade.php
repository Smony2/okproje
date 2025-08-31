@extends('admin.yonetim_master')

@section('title')
    <title>Yeni Katip Avatarı</title>
@endsection

@section('main')
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Yeni Katip Avatarı Ekle</h6>
                    <a href="{{ route('admin.katip-avatarlar.index') }}" class="btn btn-sm btn-secondary">Geri Dön</a>

                </div>

                <div class="card-body">
                    <form action="{{ route('admin.katip-avatarlar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Avatar Resmi</label>
                            <input type="file" name="path" class="form-control" required>
                            @error('path') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Katibe Ata</label>
                            <select name="katip_id" class="form-select">
                                <option value="">-- Seçiniz --</option>
                                @foreach($katipler as $katip)
                                    <option value="{{ $katip->id }}">{{ $katip->name }}</option>
                                @endforeach
                            </select>
                            @error('katip_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
