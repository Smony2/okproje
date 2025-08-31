@extends('admin.yonetim_master')

@section('title')
    <title>Avatar Güncelle</title>
@endsection

@section('main')
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Avatar Güncelle</h6>
                    <a href="{{ route('admin.avatarlar.index') }}" class="btn btn-sm btn-secondary">Geri Dön</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.avatarlar.update', $avatar->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">


                            <div class="mt-3">
                                <label class="form-label">Mevcut Resim:</label><br>
                                <img src="{{ asset($avatar->path) }}" class="rounded shadow-sm" width="100" alt="Avatar">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Avukata Ata (Atanabilir Avukatlar)</label>
                            <select name="avukat_id" class="form-select">
                                <option value="">
                                    {{ $avatar->avukat_id ? '-- Avatarı Kaldır --' : '-- Seçiniz --' }}
                                </option>
                                @foreach($avukatlar as $avukat)
                                    <option value="{{ $avukat->id }}" @selected($avatar->avukat_id == $avukat->id)>
                                        {{ $avukat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('avukat_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
