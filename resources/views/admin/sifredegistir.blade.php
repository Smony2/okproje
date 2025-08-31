@extends('admin.yonetim_master')

@section('title')
    <title>Şifre Ayarları</title>
@endsection

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card m-b-20">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Şifre Ayarı</h4>
                        <p class="text-muted m-b-30">Şifre bilgilerinizi buradan güncelleyebilirsiniz.</p>

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

                        <form action="{{ route('admin.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <label for="current_password">Mevcut Şifre:</label><br>
                            <input type="password" class="form-control" name="current_password" id="current_password" required><br><br>

                            <label for="new_password">Yeni Şifre:</label><br>
                            <input type="password" class="form-control" name="new_password" id="new_password" required><br><br>

                            <label for="new_password_confirmation">Yeni Şifre Tekrar:</label><br>
                            <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required><br><br>

                            <button class="btn btn-primary" type="submit">Şifreyi Değiştir</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
