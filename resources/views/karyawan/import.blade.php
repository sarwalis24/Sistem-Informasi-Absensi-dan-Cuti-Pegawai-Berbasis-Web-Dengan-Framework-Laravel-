@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Import Data Karyawan') }}</div>

                    <div class="card-body">
                        <form id="importForm" action="{{ route('karyawan.import_proses') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">Pilih File Excel</label>
                                <input type="file" class="form-control-file" id="file" name="file" accept=".xlsx,.xls">
                                <small class="form-text text-muted">Format file harus .xlsx atau .xls</small>
                            </div>
                            <button type="submit" class="btn btn-primary" id="importBtn">Import</button>
                            <a href="{{ asset('templates/import_karyawan.xlsx') }}" class="btn btn-success">Download Template</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).on('submit', '#importForm', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const importBtn = document.getElementById('importBtn');
                importBtn.disabled = true;

                let progress = 0;
                let uploadStarted = false;
                let uploadFinished = false;
                let uploadStartTime = null;

                Swal.fire({
                    title: 'Mengupload file...',
                    html: `
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 style="width: 0%"
                                 id="progressBar">0%</div>
                        </div>
                        <p class="mt-2" id="progressText">Sedang mengupload file...</p>
                    `,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();

                        setTimeout(() => {
                            uploadStarted = true;
                            uploadStartTime = Date.now();

                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', form.action, true);
                            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);

                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    progress = Math.round((e.loaded / e.total) * 100);
                                    document.getElementById('progressBar').style.width = progress + '%';
                                    document.getElementById('progressBar').textContent = progress + '%';
                                    document.getElementById('progressText').textContent = 'Sedang mengupload file... (' +
                                        progress + '%)';
                                }
                            });

                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === XMLHttpRequest.DONE) {
                                    importBtn.disabled = false;
                                    uploadFinished = true;
                                    document.getElementById('progressBar').style.width = '100%';
                                    document.getElementById('progressBar').textContent = '100%';
                                    document.getElementById('progressText').textContent = 'Upload selesai, memproses data...';

                                    let minDelay = 1500;
                                    let elapsed = Date.now() - uploadStartTime;
                                    let wait = elapsed < minDelay ? minDelay - elapsed : 0;

                                    setTimeout(() => {
                                        if (xhr.status === 200) {
                                            const data = JSON.parse(xhr.responseText);
                                            if (data.success) {
                                                Swal.fire({
                                                    icon: 'success',
                                                    title: 'Berhasil!',
                                                    text: data.message,
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                }).then(() => {
                                                    window.location.href = "{{ route('karyawan.index') }}";
                                                });
                                            } else {
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Gagal!',
                                                    text: data.message
                                                });
                                            }
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error!',
                                                text: 'Terjadi kesalahan saat mengupload/import data'
                                            });
                                        }
                                    }, wait);
                                }
                            };

                            xhr.send(formData);
                        }, 200);
                    }
                });
            });
        </script>
    @endpush
@endsection
