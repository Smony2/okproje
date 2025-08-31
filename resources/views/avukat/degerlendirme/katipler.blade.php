@extends('avukat.layout.avukat_master')

@section('title')
    <title>Katip Değerlendirmelerim | Avukat Paneli</title>
@endsection
@section('cssler')
    <style>
        .comment-card {
            background: #f9f9f9;
            border-left: 4px solid #64bc66;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .comment-card:hover {
            background: #f1fdf1;
        }

        .comment-header {
            font-weight: bold;
            color: #2d572c;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .comment-stars {
            color: #ffc107;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .comment-text {
            font-style: italic;
            color: #555;
        }

        .comment-date {
            font-size: 0.85rem;
            color: #888;
            text-align: right;
        }
    </style>
@endsection
@section('main')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Katip Değerlendirmeleriniz</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="{{route('avukat.dashboard')}}" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
        </ul>
    </div>

    @if($isler->isEmpty())
        <div class="alert alert-warning text-center">Henüz değerlendirdiğiniz bir katip bulunmamaktadır.</div>
    @else
        <div class="row g-4">
            @foreach($isler as $is)
                <div class="col-md-6 col-lg-4">
                    <div class="card comment-card shadow-sm p-3 h-100">
                        <div class="comment-header">
                            <a href="{{ route('avukat.katip.profil', $is->katip->id) }}" class="text-decoration-none text-dark hover-text-primary">
                                {{ $is->katip->username }}
                            </a>
                        </div>

                        <div class="comment-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $is->avukatPuan->puan)
                                    ⭐
                                @else
                                    ☆
                                @endif
                            @endfor
                            ({{ $is->avukatPuan->puan }}/5)
                        </div>

                        @if($is->avukatPuan && $is->avukatPuan->yorum)
                            <p class="comment-text">"{{ $is->avukatPuan->yorum }}"</p>
                        @endif

                        <p class="comment-date">{{ $is->created_at->format('d.m.Y H:i') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
