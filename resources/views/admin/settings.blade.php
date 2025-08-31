@extends('admin.yonetim_master')

@section('title')
    <title>Site Ayarları</title>
@endsection

@section('main')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Site Ayarları</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.site-settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Logo Bölümü -->
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="p-3">
                                            <h6>Logo Ayarları</h6>
                                            <p class="text-muted mb-0">Önerilen boyutlar: 200x60px, Format: PNG (şeffaf arka plan)</p>
                                        </div>
                                        <div class="card-body">
                                            @if($settings && $settings->logoresimyol)
                                                <div class="text-center mb-3">
                                                    <img src="{{ asset('uploads/' . $settings->logoresimyol) }}" alt="Logo" style="max-height: 100px;">
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="logo">Logo Seç</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="logo" name="logo">
                                                    <label class="custom-file-label" for="logo">Dosya seçin</label>
                                                    @error('logo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Favicon Bölümü -->
                                <div class="col-md-6">
                                    <div class="card border">
                                        <div class="p-3">
                                            <h6>Favicon Ayarları</h6>
                                            <p class="text-muted mb-0">Önerilen boyutlar: 16x16px veya 32x32px, Format: ICO veya PNG</p>
                                        </div>
                                        <div class="card-body">
                                            @if($settings && $settings->faviconyol)
                                                <div class="text-center mb-3">
                                                    <img src="{{ asset('uploads/' . $settings->faviconyol) }}" alt="Favicon" style="max-height: 32px;">
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="favicon">Favicon Seç</label>
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input @error('favicon') is-invalid @enderror" id="favicon" name="favicon">
                                                    <label class="custom-file-label" for="favicon">Dosya seçin</label>
                                                    @error('favicon')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Ayarları Kaydet</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsler')
<script>
    // Dosya seçildiğinde input label'ını güncelle
    document.querySelectorAll('.custom-file-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            var nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endsection
