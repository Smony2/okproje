@extends('avukat.layout.avukat_master')

@section('title')
    <title>Şifre Ayarları</title>
@endsection

@section('cssler')
    <style>
        /* Genel Stil */
        .container-fluid {
            padding: 0 24px;
        }

        /* Kart Stili */
        .card {
            background: linear-gradient(145deg, #ffffff, #f7f9fc);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .card-body {
            padding: 30px;
        }

        /* Başlık ve Açıklama */
        .header-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1a3c34;
            margin-bottom: 10px;
        }

        .text-muted {
            font-size: 0.95rem;
            color: #64748b;
            margin-bottom: 20px;
        }

        /* Form Elemanları */
        .form-label {
            font-size: 0.95rem;
            font-weight: 500;
            color: #1a3c34;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 15px;
            font-size: 0.95rem;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus {
            border-color: #5B86E5;
            box-shadow: 0 0 0 0.2rem rgba(91, 134, 229, 0.25);
            outline: none;
        }

        /* Hata Mesajı */
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fef2f2);
            border: none;
            border-radius: 10px;
            color: #dc2626;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .alert-danger::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #dc2626, #f87171);
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert-danger li {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        /* Buton */
        .btn-primary {
            background: linear-gradient(90deg, #5B86E5, #36D1DC);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 500;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: linear-gradient(90deg, #36D1DC, #5B86E5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        /* Responsive Tasarım */
        @media (max-width: 768px) {
            .card-body {
                padding: 20px;
            }

            .header-title {
                font-size: 1.3rem;
            }

            .form-control {
                font-size: 0.9rem;
                padding: 8px 12px;
            }

            .btn-primary {
                font-size: 0.9rem;
                padding: 10px 16px;
            }
        }
    </style>
@endsection

@section('main')
    <div class="container-fluid mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card m-b-20">
                    <div class="card-body">
                        <h4 class="header-title">Şifre Ayarları</h4>
                        <p class="text-muted">Şifre bilgilerinizi buradan güncelleyebilirsiniz.</p>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('avukat.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="current_password" class="form-label">Mevcut Şifre</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="current_password"
                                        name="current_password"
                                        required
                                    >
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Yeni Şifre</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        required
                                    >
                                </div>
                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Yeni Şifre (Tekrar)</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    Şifreyi Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
