@extends('avukat.layout.avukat_master')

@section('title')
    <title>Avatar Seç</title>
@endsection

@section('cssler')
    <style>
        /* Genel container için stil */
        .container {
            max-width: 1200px;
        }

        /* Başlık için stil */
        .section-header {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 24px;
            position: relative;
            display: inline-block;
        }
        .section-header::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 50%;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            border-radius: 2px;
        }

        /* Avatar kartları */
        .avatar-card {
            position: relative;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 12px;
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .avatar-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .avatar-selected {
            border-color: #07521a;
            box-shadow: 0 0 12px rgba(37, 99, 235, 0.3);
            animation: glow 1s ease-in-out infinite alternate;
        }

        .avatar-selected::after {
            content: '';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            background: #07521a;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .avatar-selected iconify-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 24px;
            height: 24px;
            background: #07521a;
            color: white;
            border-radius: 50%;
            padding: 4px;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 12px rgba(37, 99, 235, 0.3);
            }
            to {
                box-shadow: 0 0 20px rgba(37, 99, 235, 0.5);
            }
        }

        .avatar-img {
            width: 100%;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .avatar-card:hover .avatar-img {
            transform: scale(1.03);
        }

        .radio-hide {
            display: none;
        }

        /* Buton tasarımı */
        .btn-save-avatar {
            background: linear-gradient(90deg, #2563eb, #60a5fa);
            color: white;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }
        .btn-save-avatar:hover {
            background: linear-gradient(90deg, #1d4ed8, #3b82f6);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            color: white;
        }
        .btn-save-avatar iconify-icon {
            transition: transform 0.3s ease;
        }
        .btn-save-avatar:hover iconify-icon {
            transform: translateX(4px);
        }

        /* Alert mesajı */
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.9rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Responsive ayarlar */
        @media (max-width: 768px) {
            .section-header {
                font-size: 1.5rem;
            }
            .avatar-img {
                height: 150px;
            }
            .avatar-card {
                padding: 8px;
            }
            .btn-save-avatar {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
        }
    </style>
@endsection

@section('main')
    <div class=" mt-4">
        <h4 class="section-header">Avatar Seçimi</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('avukat.avatar-sec.post') }}" method="POST">
            @csrf
            <div class="row">
                @foreach($avatars as $avatar)
                    @php
                        $isSelected = $avatar->avukat_id == auth('avukat')->id();
                    @endphp
                    <div class="col-md-2 col-sm-6 mt-10">
                        <input type="radio" name="avatar_id" id="avatar_{{ $avatar->id }}" value="{{ $avatar->id }}" class="radio-hide"
                            {{ $isSelected ? 'checked' : '' }}>
                        <label for="avatar_{{ $avatar->id }}" class="d-block avatar-card {{ $isSelected ? 'avatar-selected' : '' }}">
                            <img src="{{ asset($avatar->path) }}" alt="Avatar" class="avatar-img">
                            @if($isSelected)
                                <iconify-icon icon="mdi:check-circle"></iconify-icon>
                            @endif
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                <button type="submit" class="btn btn-save-avatar">
                    Avatarı Kaydet
                    <iconify-icon icon="iconamoon:arrow-right-2" class="text-xl"></iconify-icon>
                </button>
            </div>
        </form>
    </div>
@endsection

@section('jsler')
    <script>
        document.querySelectorAll('input[name="avatar_id"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.avatar-card').forEach((card) => {
                    card.classList.remove('avatar-selected');
                    const icon = card.querySelector('iconify-icon');
                    if (icon) icon.remove();
                });
                const label = document.querySelector(`label[for="${this.id}"]`);
                if (label) {
                    label.classList.add('avatar-selected');
                    const icon = document.createElement('iconify-icon');
                    icon.setAttribute('icon', 'mdi:check-circle');
                    label.appendChild(icon);
                }
            });
        });
    </script>
@endsection
