<form action="{{ route('karyawan.store') }}" id="formcreateKaryawan" method="POST" enctype="multipart/form-data">
    @csrf
    <x-input-with-icon-label icon="ti ti-barcode" label="NIK" name="nik" />
    <x-input-with-icon-label icon="ti ti-credit-card" label="No. KTP" name="no_ktp" />
    <x-input-with-icon-label icon="ti ti-user" label="Nama Karyawan" name="nama_karyawan" />
    <div class="row">
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-map-pin" label="Tempat Lahir" name="tempat_lahir" />
        </div>
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal Lahir" datepicker="flatpickr-date" name="tanggal_lahir" />
        </div>
    </div>
    <x-textarea-label label="Alamat" name="alamat" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
            <option value="">Jenis Kelamin</option>
            <option value="L">Laki - Laki</option>
            <option value="P">Perempuan</option>
        </select>
    </div>
    <x-input-with-icon-label icon="ti ti-phone" label="No. HP" name="no_hp" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Status Perkawinan" name="kode_status_kawin" :data="$status_kawin" key="kode_status_kawin" textShow="status_kawin"
                kode="true" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Pendidikan
                    Terakhir</label>
                <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-select">
                    <option value="">Pendidikan Terakhir</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMP</option>
                    <option value="SMK">SMK</option>
                    <option value="D1">D1</option>
                    <option value="D2">D2</option>
                    <option value="D3">D3</option>
                    <option value="D4">D4</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>
        </div>
    </div>
    <x-select-label label="Kantor Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang" />
    <x-select-label label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" upperCase="true" />
    </div>
    <x-select-label label="Jabatan" name="kode_jabatan" :data="$jabatan" key="kode_jabatan" textShow="nama_jabatan" upperCase="true" />
    <x-input-with-icon-label icon="ti ti-calendar" datepicker="flatpickr-date" label="Tanggal Masuk" name="tanggal_masuk" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Status Karyawan</label>
        <select name="status_karyawan" id="pendidikan_terakhir" class="form-select">
            <option value="">Status Karyawan</option>
            <option value="K">Kontrak</option>
            <option value="T">Tetap</option>
        </select>
    </div>
    <x-input-file name="foto" label="Foto" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/js/pages/karyawan.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

<script>
    $(function() {

        $(".flatpickr-date").flatpickr();
        $('#nik').mask('00.00.000');
    });
</script>
