@extends('admin.yonetim_master')

@section('title')
    <title>Yasaklı Kelimeler | Admin Paneli</title>
@endsection

@section('main')
    <div class="row">
        <div class="col-12 mt-3">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card radius-16 mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Yeni Yasaklı Kelime Ekle</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.yasakli-kelimeler.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-8">
                            <input type="text" name="word" class="form-control" placeholder="Yasaklı kelime girin..." required>
                            <small class="text-muted">Not: Kelime küçük harfe çevrilecek ve tekil olarak kontrol edilecektir.</small>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <iconify-icon icon="mdi:plus" class="me-1"></iconify-icon>
                                Ekle
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card radius-16">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Yasaklı Kelimeler Listesi</h6>
                    <span class="badge bg-primary">{{ $words->total() }} kelime</span>
                </div>

                <div class="card-body">
                    @if($words->count() > 0)
                        <div class="table-responsive">
                            <table class="table bordered-table mb-0">
                                <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Kelime</th>
                                    <th>Durum</th>
                                    <th>Eklenme Tarihi</th>
                                    <th>İşlemler</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($words as $word)
                                    <tr>
                                        <td>{{ $word->id }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $word->word }}</span>
                                        </td>
                                        <td>
                                            @if($word->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Pasif</span>
                                            @endif
                                        </td>
                                        <td>{{ $word->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <!-- Toggle Aktif/Pasif -->
                                                <form action="{{ route('admin.yasakli-kelimeler.toggle', $word->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $word->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $word->is_active ? 'Pasif Yap' : 'Aktif Yap' }}">
                                                        <iconify-icon icon="{{ $word->is_active ? 'mdi:eye-off' : 'mdi:eye' }}"></iconify-icon>
                                                    </button>
                                                </form>

                                                <!-- Sil -->
                                                <form action="{{ route('admin.yasakli-kelimeler.destroy', $word->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu kelimeyi silmek istediğinizden emin misiniz?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                                        <iconify-icon icon="mdi:delete"></iconify-icon>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <iconify-icon icon="mdi:text-box-remove" style="font-size: 48px; opacity: 0.3;"></iconify-icon>
                            <p class="mt-3 text-muted">Henüz yasaklı kelime bulunmuyor.</p>
                        </div>
                    @endif
                </div>

                @if($words->hasPages())
                    <div class="card-footer">
                        {{ $words->links('pagination.custom') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection

