@extends('admin.yonetim_master')

@section('title')
    <title>Katip Avatar Listesi</title>
@endsection

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Katip Avatarlar</h6>
                    <a href="{{ route('admin.katip-avatarlar.create') }}"
                       class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <iconify-icon icon="mdi:plus" width="16" height="16"></iconify-icon>
                        Yeni Avatar Ekle
                    </a>
                </div>

                <div class="card-body">

                    {{-- ================= ATANMIŞ AVATARLAR ================= --}}
                    <h6 class="fw-bold mb-3">Atanmış Avatarlar</h6>
                    @if($atanmis->isEmpty())
                        <p class="text-muted">Henüz atanan avatar yok.</p>
                    @else
                        <div class="row">
                            @foreach($atanmis as $avatar)
                                <div class="col-md-2 mb-4">
                                    <div class="border rounded p-2 h-100 d-flex flex-column">
                                        <div class="text-center mb-2">
                                            <img src="{{ asset($avatar->path) }}" style="width:70%" alt="Avatar">
                                        </div>
                                        <h6 class="text-center flex-grow-1">
                                            {{ $avatar->katip->name }}
                                        </h6>
                                        <div class="d-flex justify-content-center gap-2 mb-20">
                                            <a href="{{ route('admin.katip-avatarlar.edit', $avatar->id) }}"
                                               class="btn btn-sm btn-info">Düzenle</a>
                                            <form action="{{ route('admin.katip-avatarlar.destroy', $avatar->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- ================= BOŞTA / ATANMAMIŞ AVATARLAR ================= --}}
                    <h6 class="fw-bold mt-20 mb-20 ">Atanmamış Avatarlar</h6>
                    @if($atanmamis->isEmpty())
                        <p class="text-muted">Boşta avatar bulunmuyor.</p>
                    @else
                        <div class="row">
                            @foreach($atanmamis as $avatar)
                                <div class="col-md-2 mb-10">
                                    <div class="border rounded p-2 h-100 d-flex flex-column">
                                        <div class="text-center mb-2">
                                            <img src="{{ asset($avatar->path) }}" style="width:70%" alt="Avatar">
                                        </div>
                                        <h6 class="text-center flex-grow-1 text-muted">Atanmamış</h6>
                                        <div class="d-flex justify-content-center gap-4 mb-20">
                                            <a href="{{ route('admin.katip-avatarlar.edit', $avatar->id) }}"
                                               class="btn btn-sm btn-info">Atama Yap</a>
                                            <form action="{{ route('admin.katip-avatarlar.destroy', $avatar->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Sil</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
