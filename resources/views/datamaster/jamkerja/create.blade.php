<form action="{{ route('jamkerja.store') }}" id="formcreateJamKerja" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Jam Kerja" name="kode_jam_kerja" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Jam Kerja" name="nama_jam_kerja" />
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-clock" label="Jam Masuk" name="jam_masuk" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-clock" label="Jam Pulang" name="jam_pulang" />
        </div>
    </div>
    <div class="form-group mb-3">
        <select name="istirahat" id="istirahat" class="form-select">
            <option value="">Istirahat</option>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>
    <div class="row" id="sectionIstirahat">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-clock" label="Jam Awal Istirahat" name="jam_awal_istirahat" />
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
            <x-input-with-icon icon="ti ti-clock" label="Jam Akhir Istirahat" name="jam_akhir_istirahat" />
        </div>
    </div>
    <x-input-with-icon icon="ti ti-file-text" label="Total Jam" name="total_jam" />
    <x-input-with-icon icon="ti ti-file-text" label="Keterangan" name="keterangan" />
    <div class="form-group mb-3">
        <select name="lintashari" id="lintashari" class="form-select">
            <option value="">Lintas Hari</option>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>
    <div class="row">
        <div class="col">
            <button type="submit" class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Simpan</button>
        </div>
    </div>
</form>
<script src="{{ asset('assets/js/pages/jamkerja.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
<script>
    $(document).ready(function() {
        // function toogleIstirahat() {
        //     if ($('#istirahat').val() == 1) {
        //         $('#sectionIstirahat').show();
        //     } else {
        //         $('#sectionIstirahat').hide();
        //     }
        // }
        // toogleIstirahat();

        // $('#istirahat').on('change', function() {
        //     toogleIstirahat();
        // });

        $("#jam_masuk,#jam_pulang,#jam_awal_istirahat,#jam_akhir_istirahat").mask("00:00");
    });
</script>
