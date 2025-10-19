@extends('layouts.mobile.app')
@section('content')
    <style>
        /* Tambahkan style untuk header dan content */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 70px;
            padding-top: 5px;
            position: relative;
            z-index: 1;
        }

        /* Form Styles */
        .feedback-input {
            color: #333;
            font-family: Helvetica, Arial, sans-serif;
            font-weight: 500;
            font-size: 18px;
            border-radius: 5px;
            line-height: 22px;
            background-color: #fbfbfb;
            padding: 13px 13px 13px 54px;
            margin-bottom: 10px;
            width: 100%;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            -ms-box-sizing: border-box;
            box-sizing: border-box;
            border: 3px solid rgba(0, 0, 0, 0);
        }

        .feedback-input:focus {
            background: #fff;
            box-shadow: 0;
            border: 3px solid #3498db;
            color: #3498db;
            outline: none;
            padding: 13px 13px 13px 54px;
        }

        .focused {
            color: #30aed6;
            border: #30aed6 solid 3px;
        }

        /* Textarea */
        textarea {
            width: 100%;
            height: 150px;
            line-height: 150%;
            resize: vertical;
        }

        /* Button */
        .btn-primary {
            font-family: 'Montserrat', Arial, Helvetica, sans-serif;
            width: 100%;
            background: #3498db;
            border-radius: 5px;
            border: 0;
            cursor: pointer;
            color: white;
            font-size: 24px;
            padding-top: 10px;
            padding-bottom: 10px;
            transition: all 0.3s;
            margin-top: -4px;
            font-weight: 700;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        /* Image Preview */
        .image-preview {
            margin-top: 15px;
            text-align: center;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Current Image */
        .current-image {
            margin-bottom: 15px;
            text-align: center;
        }

        .current-image img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .current-image-label {
            color: #666;
            font-size: 12px;
            margin-bottom: 8px;
        }

        /* Error Messages */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('aktivitaskaryawan.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Edit Aktivitas</div>
            <div class="right"></div>
        </div>
    </div>

    <div id="content-section">
        <div class="row" style="margin-top: 80px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('aktivitaskaryawan.update', $aktivitaskaryawan) }}" method="POST" enctype="multipart/form-data"
                    id="formAktivitas">
                    @csrf
                    @method('PUT')

                    <!-- Hidden NIK field for karyawan -->
                    @if (auth()->user()->hasRole('karyawan'))
                        <input type="hidden" name="nik" value="{{ $karyawan->nik }}">
                    @endif

                    <input type="text" class="feedback-input lokasi" name="lokasi" placeholder="Lokasi (Opsional)"
                        value="{{ old('lokasi', $aktivitaskaryawan->lokasi) }}" />
                    @error('lokasi')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <textarea placeholder="Deskripsikan aktivitas yang dilakukan..." class="feedback-input aktivitas" name="aktivitas" style="height: 120px">{{ old('aktivitas', $aktivitaskaryawan->aktivitas) }}</textarea>
                    @error('aktivitas')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <input type="file" class="feedback-input foto" name="foto" accept="image/*" onchange="previewImage(this)" />
                    <small style="color: #666; font-size: 12px; margin-top: -10px; display: block; margin-bottom: 10px;">
                        Format: JPG, PNG, GIF. Maksimal 2MB
                    </small>
                    @error('foto')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <!-- Current Image -->
                    @if ($aktivitaskaryawan->foto)
                        <div class="current-image">
                            <div class="current-image-label">Foto Saat Ini:</div>
                            <img src="{{ asset('storage/uploads/aktivitas/' . $aktivitaskaryawan->foto) }}" alt="Foto Aktivitas">
                            <div style="color: #666; font-size: 12px; margin-top: 8px;">
                                Foto akan diganti jika Anda memilih file baru
                            </div>
                        </div>
                    @endif

                    <!-- Image Preview -->
                    <div id="imagePreview" class="image-preview" style="display: none;">
                        <div style="color: #666; font-size: 12px; margin-bottom: 8px;">Preview Foto Baru:</div>
                        <img id="previewImg" src="" alt="Preview">
                    </div>

                    <button type="submit" class="btn btn-primary" id="btnSimpan">
                        <i class="ti ti-check me-1"></i>Update Aktivitas
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            // Form validation
            $('#formAktivitas').on('submit', function(e) {
                var aktivitas = $('textarea[name="aktivitas"]').val().trim();

                if (aktivitas === '') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Silakan isi deskripsi aktivitas terlebih dahulu',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            });

            // Image preview function
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

            // Make function global
            window.previewImage = previewImage;

            // Auto-resize textarea
            $('textarea[name="aktivitas"]').on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
@endpush
