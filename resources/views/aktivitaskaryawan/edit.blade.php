@extends('layouts.app')

@section('title', 'Edit Aktivitas Karyawan')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-edit me-2"></i>Edit Aktivitas Karyawan
                        </h5>
                        <a href="{{ route('aktivitaskaryawan.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('aktivitaskaryawan.update', $aktivitaskaryawan) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nik" class="form-label">Karyawan <span class="text-danger">*</span></label>
                                        <select name="nik" id="nik" class="form-select @error('nik') is-invalid @enderror" required>
                                            <option value="">Pilih Karyawan</option>
                                            @foreach ($karyawans as $karyawan)
                                                <option value="{{ $karyawan->nik }}"
                                                    {{ old('nik', $aktivitaskaryawan->nik) == $karyawan->nik ? 'selected' : '' }}>
                                                    {{ $karyawan->nama_karyawan }} ({{ $karyawan->nik }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="lokasi" class="form-label">Lokasi</label>
                                        <input type="text" name="lokasi" id="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                            value="{{ old('lokasi', $aktivitaskaryawan->lokasi) }}" placeholder="Masukkan lokasi aktivitas">
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="aktivitas" class="form-label">Aktivitas <span class="text-danger">*</span></label>
                                <textarea name="aktivitas" id="aktivitas" rows="5" class="form-control @error('aktivitas') is-invalid @enderror"
                                    placeholder="Deskripsikan aktivitas yang dilakukan..." required>{{ old('aktivitas', $aktivitaskaryawan->aktivitas) }}</textarea>
                                @error('aktivitas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Aktivitas</label>
                                <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror"
                                    accept="image/*" onchange="previewImage(this)">
                                <div class="form-text">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</div>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Current Image -->
                            @if ($aktivitaskaryawan->foto)
                                <div class="mb-3">
                                    <label class="form-label">Foto Saat Ini:</label>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/uploads/aktivitas/' . $aktivitaskaryawan->foto) }}" alt="Foto Aktivitas"
                                            class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                        <p class="text-muted mt-2">Foto akan diganti jika Anda memilih file baru</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Image Preview -->
                            <div id="imagePreview" class="mb-3" style="display: none;">
                                <label class="form-label">Preview Foto Baru:</label>
                                <div class="text-center">
                                    <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('aktivitaskaryawan.index') }}" class="btn btn-secondary">
                                    <i class="ti ti-x me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-2"></i>Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endpush
