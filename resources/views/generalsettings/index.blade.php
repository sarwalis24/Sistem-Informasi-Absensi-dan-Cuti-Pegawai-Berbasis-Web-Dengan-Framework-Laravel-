@extends('layouts.app')
@section('titlepage', 'General Settings')

@section('content')
@section('navigasi')
    <span>General Settings</span>
@endsection
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<style>
    .checkbox-wrapper-55 input[type="checkbox"] {
        visibility: hidden;
        display: none;
    }

    .checkbox-wrapper-55 *,
    .checkbox-wrapper-55 ::after,
    .checkbox-wrapper-55 ::before {
        box-sizing: border-box;
    }

    .checkbox-wrapper-55 .rocker {
        display: inline-block;
        position: relative;
        /*
      SIZE OF SWITCH
      ==============
      All sizes are in em - therefore
      changing the font-size here
      will change the size of the switch.
      See .rocker-small below as example.
      */
        font-size: 2em;
        font-weight: bold;
        text-align: center;
        text-transform: uppercase;
        color: #888;
        width: 7em;
        height: 4em;
        overflow: hidden;
        border-bottom: 0.5em solid #eee;
    }

    .checkbox-wrapper-55 .rocker-small {
        font-size: 0.75em;
    }

    .checkbox-wrapper-55 .rocker::before {
        content: "";
        position: absolute;
        top: 0.5em;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #999;
        border: 0.5em solid #eee;
        border-bottom: 0;
    }

    .checkbox-wrapper-55 .switch-left,
    .checkbox-wrapper-55 .switch-right {
        cursor: pointer;
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 2.5em;
        width: 3em;
        transition: 0.2s;
        user-select: none;
    }

    .checkbox-wrapper-55 .switch-left {
        height: 2.4em;
        width: 2.75em;
        left: 0.85em;
        bottom: 0.4em;
        background-color: #ddd;
        transform: rotate(15deg) skewX(15deg);
    }

    .checkbox-wrapper-55 .switch-right {
        right: 0.5em;
        bottom: 0;
        background-color: #bd5757;
        color: #fff;
    }

    .checkbox-wrapper-55 .switch-left::before,
    .checkbox-wrapper-55 .switch-right::before {
        content: "";
        position: absolute;
        width: 0.4em;
        height: 2.45em;
        bottom: -0.45em;
        background-color: #ccc;
        transform: skewY(-65deg);
    }

    .checkbox-wrapper-55 .switch-left::before {
        left: -0.4em;
    }

    .checkbox-wrapper-55 .switch-right::before {
        right: -0.375em;
        background-color: transparent;
        transform: skewY(65deg);
    }

    .checkbox-wrapper-55 input:checked+.switch-left {
        background-color: #0084d0;
        color: #fff;
        bottom: 0px;
        left: 0.5em;
        height: 2.5em;
        width: 3em;
        transform: rotate(0deg) skewX(0deg);
    }

    .checkbox-wrapper-55 input:checked+.switch-left::before {
        background-color: transparent;
        width: 3.0833em;
    }

    .checkbox-wrapper-55 input:checked+.switch-left+.switch-right {
        background-color: #ddd;
        color: #888;
        bottom: 0.4em;
        right: 0.8em;
        height: 2.4em;
        width: 2.75em;
        transform: rotate(-15deg) skewX(-15deg);
    }

    .checkbox-wrapper-55 input:checked+.switch-left+.switch-right::before {
        background-color: #ccc;
    }

    /* Keyboard Users */
    .checkbox-wrapper-55 input:focus+.switch-left {
        color: #333;
    }

    .checkbox-wrapper-55 input:checked:focus+.switch-left {
        color: #fff;
    }

    .checkbox-wrapper-55 input:focus+.switch-left+.switch-right {
        color: #fff;
    }

    .checkbox-wrapper-55 input:checked:focus+.switch-left+.switch-right {
        color: #333;
    }
</style>
<div class="row">
    <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('generalsetting.update', Crypt::encrypt($setting->id)) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <x-input-with-icon-label label="Nama Perusahaan" name="nama_perusahaan" icon="ti ti-home" :value="$setting->nama_perusahaan ?? ''" />
                    <x-textarea-label label="Alamat Perusahaan" name="alamat" icon="ti ti-map-pin" :value="$setting->alamat ?? ''" />
                    <x-input-with-icon-label label="Telepon" name="telepon" icon="ti ti-phone" :value="$setting->telepon ?? ''" />
                    <x-input-with-icon-label label="Total Jam Kerja dalam 1 Bulan" name="total_jam_bulan" icon="ti ti-clock" :value="$setting->total_jam_bulan ?? ''" />
                    <label for="" style="font-weight: 600" class="form-label">Denda</label>
                    <div class="checkbox-wrapper-55">
                        <label class="rocker rocker-small">
                            <input type="checkbox" name="denda" @checked($setting->denda ?? false)>
                            <span class="switch-left">Yes</span>
                            <span class="switch-right">No</span>
                        </label>
                    </div>
                    <label for="" style="font-weight: 600" class="form-label">Face Recognition</label>
                    <div class="checkbox-wrapper-55">
                        <label class="rocker rocker-small">
                            <input type="checkbox" name="face_recognition" @checked($setting->face_recognition ?? false)>
                            <span class="switch-left">Yes</span>
                            <span class="switch-right">No</span>
                        </label>
                    </div>
                    <div class="row">
                        <div class="col">
                            <x-input-with-icon-label label="Periode Laporan Dari" icon="ti ti-calendar" name="periode_laporan_dari"
                                :value="$setting->periode_laporan_dari ?? ''" />
                        </div>
                        <div class="col">
                            <x-input-with-icon-label label="Periode Laporan Sampai" icon="ti ti-calendar" name="periode_laporan_sampai"
                                :value="$setting->periode_laporan_sampai ?? ''" />
                        </div>
                    </div>
                    <label for="" style="font-weight: 600" class="form-label">Periode Laporan Lintas
                        Bulan</label>
                    <div class="checkbox-wrapper-55">
                        <label class="rocker rocker-small">
                            <input type="checkbox" name="periode_laporan_next_bulan" @checked($setting->periode_laporan_next_bulan ?? false)>
                            <span class="switch-left">Yes</span>
                            <span class="switch-right">No</span>
                        </label>
                    </div>

                    <label for="" style="font-weight: 600" class="form-label">Multi Lokasi</label>
                    <div class="checkbox-wrapper-55">
                        <label class="rocker rocker-small">
                            <input type="checkbox" name="multi_lokasi" @checked($setting->multi_lokasi ?? false)>
                            <span class="switch-left">Yes</span>
                            <span class="switch-right">No</span>
                        </label>
                    </div>

                    <label for="" style="font-weight: 600" class="form-label">Notifikasi WA</label>
                    <div class="checkbox-wrapper-55">
                        <label class="rocker rocker-small">
                            <input type="checkbox" name="notifikasi_wa" @checked($setting->notifikasi_wa ?? false)>
                            <span class="switch-left">Yes</span>
                            <span class="switch-right">No</span>
                        </label>
                    </div>
                    <label for="" style="font-weight: 600" class="form-label">Batasi Jam Presensi</label>
                    <div class="checkbox-wrapper-55">
                        <label class="rocker rocker-small">
                            <input type="checkbox" name="batasi_absen" @checked($setting->batasi_absen ?? false)>
                            <span class="switch-left">Yes</span>
                            <span class="switch-right">No</span>
                        </label>
                    </div>
                    <x-input-with-icon-label label="Batas Jam Presensi Masuk (Dalam Jam) Sebelum Jam Masuk" name="batas_jam_absen" icon="ti ti-clock"
                        :value="$setting->batas_jam_absen ?? ''" />
                    <small class="text-muted">Wajib Diisi Jika Batasi Jam Presensi Diaktifkan</small>
                    <x-input-with-icon-label label="Batas Jam Presensi Pulang (Dalam Jam) Sebelum Jam Pulang" name="batas_jam_absen_pulang"
                        icon="ti ti-clock" :value="$setting->batas_jam_absen_pulang ?? ''" />
                    <div class="form-group">
                        <small class="text-muted">Wajib Diisi Jika Batasi Jam Presensi Diaktifkan</small>
                    </div>
                    <label for="" style="font-weight: 600" class="form-label">Batasi Hari Izin</label>
                    <div class="checkbox-wrapper-55">
                        <label class="rocker rocker-small">
                            <input type="checkbox" name="batasi_hari_izin" @checked($setting->batasi_hari_izin ?? false)>
                            <span class="switch-left">Yes</span>
                            <span class="switch-right">No</span>
                        </label>
                    </div>
                    <x-input-with-icon-label label="Batas Hari Izin (Dalam Hari)" name="jml_hari_izin_max" icon="ti ti-clock" :value="$setting->jml_hari_izin_max ?? ''" />
                    <x-input-with-icon-label label="Batas Presensi Lintas Hari" name="batas_presensi_lintashari" icon="ti ti-clock"
                        :value="$setting->batas_presensi_lintashari ?? ''" />
                    <x-input-with-icon-label label="Cloud Id" name="cloud_id" icon="ti ti-cloud" :value="$setting->cloud_id ?? ''" />
                    <x-input-with-icon-label label="API Key" name="api_key" icon="ti ti-key" :value="$setting->api_key ?? ''" />
                    <x-input-with-icon-label label="Domain Email (contoh: adamadifa.site)" name="domain_email" icon="ti ti-mail"
                        :value="$setting->domain_email ?? ''" />
                    <x-input-with-icon-label label="Domain WA Gateway (contoh: https://wa.adamadifa.site)" name="domain_wa_gateway"
                        icon="ti ti-message" :value="$setting->domain_wa_gateway ?? ''" />
                    <x-input-with-icon-label label="WA API Key" name="wa_api_key" icon="ti ti-brand-whatsapp" :value="$setting->wa_api_key ?? ''" />
                    <div class="form-group mb-3">
                        <label for="logo" style="font-weight: 600" class="form-label">Logo Perusahaan</label>
                        <input type="file" class="form-control" name="logo" id="logo">
                        <div class="mt-2 text-center">
                            @if ($setting->logo && Storage::exists('public/logo/' . $setting->logo))
                                <img src="{{ asset('storage/logo/' . $setting->logo) }}" alt="Logo Perusahaan" style="max-width: 200px;">
                            @else
                                <img src="https://placehold.co/200x200?text=Logo+Perusahaan&font=roboto" alt="Logo Default"
                                    style="max-width: 200px;">
                            @endif
                        </div>
                    </div>

                    <!-- PWA Icon Generator Section -->


                    <button class="btn btn-primary w-100" id="btnSimpan">
                        <i class="ti ti-refresh me-1"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="pwa_icon" style="font-weight: 600" class="form-label">
                        Upload Icon Master (1080x1080px)
                    </label>
                    <input type="file" class="form-control" name="pwa_icon" id="pwa_icon" accept="image/*">
                    <small class="text-muted">
                        Upload gambar dengan ukuran 1080x1080px atau lebih besar.
                        Sistem akan otomatis generate berbagai ukuran untuk PWA.
                    </small>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-success w-100" id="btnGenerateIcons">
                            <i class="ti ti-device-mobile me-1"></i> Generate PWA Icons
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning w-100" id="btnPreviewIcons">
                            <i class="ti ti-eye me-1"></i> Preview Icons
                        </button>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div id="progressContainer" style="display: none;">
                    <div class="progress mb-2">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%">
                        </div>
                    </div>
                    <small class="text-muted" id="progressText">Menggenerate icons...</small>
                </div>

                <!-- Generated Icons Preview -->
                <div id="iconsPreview" class="mt-3" style="display: none;">
                    <h6>Generated Icons:</h6>
                    <div class="row" id="iconsGrid">
                        <!-- Icons will be loaded here -->
                    </div>
                </div>

                <!-- Current PWA Icons -->
                <div class="mt-3">
                    <h6>Current PWA Icons:</h6>
                    <div class="row" id="currentIconsGrid">
                        @php
                            $iconDir = public_path('assets/img/icons/pwa');
                            $currentIcons = [];
                            if (file_exists($iconDir)) {
                                $files = glob($iconDir . '/icon-*.png');
                                foreach ($files as $file) {
                                    $filename = basename($file);
                                    $size = str_replace(['icon-', '.png'], '', $filename);
                                    $currentIcons[] = [
                                        'size' => $size,
                                        'path' => 'assets/img/icons/pwa/' . $filename,
                                    ];
                                }
                            }
                        @endphp

                        @if (count($currentIcons) > 0)
                            @foreach ($currentIcons as $icon)
                                <div class="col-2 mb-2">
                                    <div class="text-center">
                                        <img src="{{ asset($icon['path']) }}" alt="Icon {{ $icon['size'] }}" class="img-thumbnail"
                                            style="width: 50px; height: 50px;">
                                        <small class="d-block">{{ $icon['size'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-1"></i>
                                    Belum ada icon PWA yang di-generate. Upload icon master untuk memulai.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('myscript')
<script>
    $(document).ready(function() {
        $('#batas_presensi_lintashari').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: 'H:i',
            time_24hr: true,
        });

        // PWA Icon Generator
        $('#btnGenerateIcons').click(function() {
            const fileInput = document.getElementById('pwa_icon');
            const file = fileInput.files[0];

            if (!file) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih file icon terlebih dahulu!'
                });
                return;
            }

            // Validate file size (max 10MB)
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ukuran file terlalu besar! Maksimal 10MB.'
                });
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'File harus berupa gambar!'
                });
                return;
            }

            generateIcons(file);
        });

        $('#btnPreviewIcons').click(function() {
            previewCurrentIcons();
        });

        function generateIcons(file) {
            const formData = new FormData();
            formData.append('icon', file);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Show progress
            $('#progressContainer').show();
            $('#btnGenerateIcons').prop('disabled', true);
            updateProgress(0, 'Memulai proses...');

            $.ajax({
                url: '{{ route('pwa.generate-icons') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            updateProgress(percentComplete, 'Uploading file...');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    updateProgress(100, 'Selesai!');

                    setTimeout(() => {
                        $('#progressContainer').hide();
                        $('#btnGenerateIcons').prop('disabled', false);

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: `Berhasil generate ${response.count} icon PWA!`
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    }, 1000);
                },
                error: function(xhr) {
                    $('#progressContainer').hide();
                    $('#btnGenerateIcons').prop('disabled', false);

                    let errorMessage = 'Terjadi kesalahan saat generate icons!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
        }

        function updateProgress(percent, text) {
            $('.progress-bar').css('width', percent + '%');
            $('#progressText').text(text);
        }

        function previewCurrentIcons() {
            $.ajax({
                url: '{{ route('pwa.preview-icons') }}',
                type: 'GET',
                success: function(response) {
                    if (response.length > 0) {
                        let html = '';
                        response.forEach(function(icon) {
                            html += `
                                <div class="col-2 mb-2">
                                    <div class="text-center">
                                        <img src="${icon.url}"
                                             alt="Icon ${icon.size}"
                                             class="img-thumbnail"
                                             style="width: 50px; height: 50px;">
                                        <small class="d-block">${icon.size}</small>
                                    </div>
                                </div>
                            `;
                        });

                        $('#iconsGrid').html(html);
                        $('#iconsPreview').show();

                        Swal.fire({
                            icon: 'info',
                            title: 'Preview Icons',
                            text: `Ditemukan ${response.length} icon PWA yang sudah di-generate.`
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Info',
                            text: 'Belum ada icon PWA yang di-generate.'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat preview icons!'
                    });
                }
            });
        }
    });
</script>
@endpush
