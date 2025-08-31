@extends('admin.yonetim_master')


@section('title')
    <title>
        Avatar Listesi
    </title>
@endsection
@section('main')

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>Yeni Avatar Yükle</h6>
                    <a href="{{ route('admin.avatarlar.index') }}" class="btn btn-sm btn-secondary">Geri Dön</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.avatarlar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="path" class="form-label">Avatar Resmi</label>
                            <input type="file" name="path" class="form-control" required>
                            @error('path') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avukat_id" class="form-label">Avukata Ata (İsteğe Bağlı)</label>
                            <select name="avukat_id" class="form-control">
                                <option value="">-- Seçiniz --</option>
                                @foreach($avukatlar as $avukat)
                                    <option value="{{ $avukat->id }}">{{ $avukat->name }}</option>
                                @endforeach
                            </select>
                            @error('avukat_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Kaydet</button>
                    </form>

                </div>

            </div>
        </div>
    </div>

@endsection
