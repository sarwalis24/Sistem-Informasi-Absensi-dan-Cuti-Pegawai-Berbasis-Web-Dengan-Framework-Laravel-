@extends('layouts.app')
@section('titlepage', 'Bersihkan Foto Presensi')

@section('content')
@section('navigasi')
    <span>Utilities > Bersihkan Foto</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Bersihkan Foto Presensi</h5>
            </div>
            <div class="card-body">
                <!-- Form Pilih Periode -->
                <div class="row">
                    <div class="col-12">
                        <h6 class="mb-3">Pilih Periode Tanggal Presensi</h6>
                        <form method="POST" action="{{ route('bersihkanfoto.destroy') }}" id="deleteForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        <input type="text" class="form-control flatpickr-date" id="tanggal_awal" name="tanggal_awal"
                                            placeholder="Pilih tanggal awal" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                        <input type="text" class="form-control flatpickr-date" id="tanggal_akhir" name="tanggal_akhir"
                                            placeholder="Pilih tanggal akhir" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-danger" id="btn-delete-photos">
                                            <i class="ti ti-trash me-2"></i>Hapus Foto Presensi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <strong>Peringatan:</strong> Tindakan menghapus FILE FOTO presensi tidak dapat dibatalkan. Data presensi tetap tersimpan,
                            hanya file fotonya yang dihapus. Pastikan Anda telah memilih periode tanggal yang tepat.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="konfirmasiText">Apakah Anda yakin ingin menghapus foto-foto presensi?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btnKonfirmasiHapus">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Initialize flatpickr for date inputs
        $('.flatpickr-date').flatpickr();

        // Delete photos button
        $('#btn-delete-photos').click(function() {
            var tanggalAwal = $('#tanggal_awal').val();
            var tanggalAkhir = $('#tanggal_akhir').val();

            if (!tanggalAwal || !tanggalAkhir) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Silakan pilih tanggal awal dan tanggal akhir terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $('#konfirmasiText').text('Apakah Anda yakin ingin menghapus FILE FOTO presensi dalam periode ' +
                tanggalAwal + ' sampai ' + tanggalAkhir + '? (Data presensi tetap tersimpan) Tindakan ini tidak dapat dibatalkan!'
            );
            $('#modalKonfirmasi').modal('show');

            $('#btnKonfirmasiHapus').off('click').on('click', function() {
                deletePhotos();
            });
        });

        function deletePhotos() {
            $('#modalKonfirmasi').modal('hide');

            var formData = {
                _token: '{{ csrf_token() }}',
                tanggal_awal: $('#tanggal_awal').val(),
                tanggal_akhir: $('#tanggal_akhir').val()
            };

            $.ajax({
                url: '{{ route('bersihkanfoto.destroy') }}',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response && response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.success,
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#tanggal_awal').val('');
                                $('#tanggal_akhir').val('');
                            }
                        });
                    } else if (response && response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.error,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan!',
                            text: 'Response tidak valid',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr) {
                    console.log('XHR Error:', xhr);
                    var response = xhr.responseJSON;
                    if (response && response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: response.error,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat menghapus foto. Status: ' + xhr.status,
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    });
</script>
@endpush
