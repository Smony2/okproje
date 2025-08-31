@extends('avukat.layout.avukat_master')

@section('title')
    <title>Yönetici Listesi</title>
@endsection

@section('main')

    <section class="section mt-4">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Finans Yöneticiler <a href="{{ route('avukat.yoneticiler.create') }}"
                                                      class="btn btn-primary waves-effect waves-light">
                                    <i style="margin-right: 7px" class="fa fa-plus"></i> Yeni Yönetici Ekle
                                </a>
                            </h4>
                        </div>
                        <div class="card-body">


                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>İsim</th>
                                        <th>Email</th>
                                        <th>Durum</th>
                                        <th>Roller</th>
                                        <th>İşlemler</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($admins as $admin)
                                        <tr>
                                            <td>{{ $admin->id }}</td>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ $admin->is_active ? 'Aktif' : 'Pasif' }}</td>
                                            <td>
                                                @if($admin->roles->isNotEmpty())
                                                    @foreach($admin->roles as $role)
                                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge badge-secondary">Rol Yok</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('avukat.yoneticiler.edit', $admin->id) }}"
                                                   class="btn btn-sm btn-warning">Düzenle</a>

                                                <form action="{{ route('avukat.yoneticiler.destroy', $admin->id) }}"
                                                      method="POST" style="display:inline-block;"
                                                      onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Henüz yönetici bulunmuyor.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection
