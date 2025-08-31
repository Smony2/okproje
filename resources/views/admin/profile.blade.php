@extends('admin.yonetim_master')

@section('title')
<title>Profil Ayarları</title>
@endsection

@section('main')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card m-b-20">
                <div class="card-body">
                    <h6 class="mt-0 header-title">Profil Ayarları</h6>
                    <p class="text-muted m-b-30">Profil bilgilerinizi buradan güncelleyebilirsiniz.</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label>Ad Soyad</label>
                            <input type="text" class="form-control" name="name" value="{{ auth('admin')->user()->name }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>E-posta</label>
                            <input type="email" class="form-control" name="email" value="{{ auth('admin')->user()->email }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label>Profil Fotoğrafı</label>
                            <input type="file" class="form-control" name="avatar">
                            @if(auth('admin')->user()->avatar)
                                <img src="{{ asset(auth('admin')->user()->avatar) }}" alt="Profil Fotoğrafı" class="mt-2" style="max-width: 100px;">
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            Profili Güncelle
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
