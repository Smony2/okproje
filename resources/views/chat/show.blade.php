@extends('layouts.app')

@section('title', 'Sohbet')

@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sohbet</h5>
                <a href="{{ route('chat.index') }}" class="btn btn-sm btn-light">← Geri</a>
            </div>
            <div class="card-body" id="chat-box" style="height: 400px; overflow-y: scroll;">
                @foreach($messages as $message)
                    <div class="mb-2">
                        <div class="p-2 rounded
                        {{ $message->sender_id === auth()->id() && $message->sender_type === $authType ? 'bg-primary text-white text-end ms-auto w-75' : 'bg-light text-dark w-75' }}">
                            <small>
                                {{ $message->created_at->format('H:i') }}
                            </small><br>
                            {{ $message->content }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <form id="message-form" method="POST" action="{{ route('chat.message.store', $conversation->id) }}">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="content" id="content" class="form-control" placeholder="Mesajınızı yazın..." required autocomplete="off">
                        <button type="submit" class="btn btn-primary">Gönder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        const pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
            encrypted: true
        });

        const channel = pusher.subscribe('conversation.{{ $conversation->id }}');
        channel.bind('new-message', function(data) {
            const chatBox = document.getElementById('chat-box');
            const div = document.createElement('div');
            div.classList.add('mb-2');
            div.innerHTML = `
            <div class="p-2 rounded bg-light text-dark w-75">
                <small>${data.created_at}</small><br>
                ${data.content}
            </div>
        `;
            chatBox.appendChild(div);
            chatBox.scrollTop = chatBox.scrollHeight;
        });

        document.getElementById('message-form').addEventListener('submit', function () {
            setTimeout(() => {
                document.getElementById('content').value = '';
            }, 100);
        });

        window.onload = function () {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
@endsection
