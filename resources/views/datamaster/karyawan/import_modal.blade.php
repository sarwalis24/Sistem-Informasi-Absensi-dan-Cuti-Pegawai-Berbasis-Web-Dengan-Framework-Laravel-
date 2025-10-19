<div class="row">
    <div class="col-12">
        <form action="{{ route('karyawan.import_proses') }}" method="POST" enctype="multipart/form-data" id="frmImport">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input type="file" name="file" class="form-control" id="file" accept=".xlsx, .xls">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Kolom</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>nik</td>
                                    <td>Wajib diisi, harus unik</td>
                                </tr>
                                <tr>
                                    <td>no_ktp</td>
                                    <td>Wajib diisi</td>
                                </tr>
                                <tr>
                                    <td>nama_karyawan</td>
                                    <td>Wajib diisi</td>
                                </tr>
                                <tr>
                                    <td>tempat_lahir</td>
                                    <td>Wajib diisi</td>
                                </tr>
                                <tr>
                                    <td>tanggal_lahir</td>
                                    <td>Wajib diisi, format YYYY-MM-DD</td>
                                </tr>
                                <tr>
                                    <td>alamat</td>
                                    <td>Wajib diisi</td>
                                </tr>
                                <tr>
                                    <td>no_hp</td>
                                    <td>Wajib diisi</td>
                                </tr>
                                <tr>
                                    <td>jenis_kelamin</td>
                                    <td>Wajib diisi, L atau P</td>
                                </tr>
                                <tr>
                                    <td>kode_status_kawin</td>
                                    <td>Wajib diisi, harus sesuai dengan kode status kawin yang ada</td>
                                </tr>
                                <tr>
                                    <td>pendidikan_terakhir</td>
                                    <td>Wajib diisi</td>
                                </tr>
                                <tr>
                                    <td>kode_cabang</td>
                                    <td>Wajib diisi, harus sesuai dengan kode cabang yang ada</td>
                                </tr>
                                <tr>
                                    <td>kode_dept</td>
                                    <td>Wajib diisi, harus sesuai dengan kode departemen yang ada</td>
                                </tr>
                                <tr>
                                    <td>kode_jabatan</td>
                                    <td>Wajib diisi, harus sesuai dengan kode jabatan yang ada</td>
                                </tr>
                                <tr>
                                    <td>tanggal_masuk</td>
                                    <td>Wajib diisi, format YYYY-MM-DD</td>
                                </tr>
                                <tr>
                                    <td>status_karyawan</td>
                                    <td>Wajib diisi</td>
                                </tr>
                                <tr>
                                    <td>status_aktif_karyawan</td>
                                    <td>Opsional, 1 = Aktif, 0 = Non Aktif</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="form-group">
                        <a href="{{ route('karyawan.download_template') }}" class="btn btn-success">
                            <i class="ti ti-download me-2"></i>Download Template
                        </a>
                        <button class="btn btn-primary" id="btnImport" type="submit">
                            <i class="ti ti-file-import me-2"></i>Import
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    $(document).on('submit', '#frmImport', function(e) {
        e.preventDefault();

        const form = this;
        const formData = new FormData(form);
        const importBtn = document.getElementById('btnImport');
        importBtn.disabled = true;

        // Tampilkan loading
        Swal.fire({
            title: 'Memproses data...',
            html: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div><p class="mt-3">Data sedang diproses, mohon tunggu...</p>',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                // Swal.showLoading();
            }
        });

        // Kirim request
        $.ajax({
            url: form.action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    // Tampilkan error dalam format yang lebih baik
                    let errorMessage = response.message;
                    if (response.errors) {
                        errorMessage += '<br><br><ul class="text-start">';
                        Object.keys(response.errors).forEach(key => {
                            errorMessage += `<li>${response.errors[key]}</li>`;
                        });
                        errorMessage += '</ul>';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMessage,
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat mengupload/import data';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                importBtn.disabled = false;
            }
        });
    });
</script>
