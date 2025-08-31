@extends('avukat.layout.avukat_master')

@section('title')
    <title>Mesajlar | Avukat Paneli</title>
@endsection

@section('main')
    <div class="container mt-4">
        <h4 class="mb-4">Konuşmalarım</h4>

        <div class="card shadow-sm">
            <div class="card-body">
                @if($conversations->isEmpty())
                    <p class="text-muted text-center">Henüz bir konuşma bulunmamaktadır.</p>
                @else
                    <ul class="list-group">
                        @foreach($conversations as $conversation)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    {{ $conversation->katip->name ?? 'Kâtip' }} ile konuşma
                                </span>
                                <a href="{{ route('avukat.chat.show', $conversation->id) }}" class="btn btn-sm btn-primary">Görüntüle</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
