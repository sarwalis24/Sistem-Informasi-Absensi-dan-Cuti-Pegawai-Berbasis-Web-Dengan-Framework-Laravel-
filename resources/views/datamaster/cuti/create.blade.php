<form action="#" id="formCuti" method="POST">
    @csrf
    <x-input-with-icon label="Kode Cuti" name="kode_cuti" icon="ti ti-barcode" />
    <x-input-with-icon label="Jenis Cuti" name="jenis_cuti" icon="ti ti-file-description" />
    <x-input-with-icon label="Jumlah Hari" name="jumlah_hari" icon="ti ti-file-description" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100"><i class="ti ti-send"></i>Submit</button>
    </div>
</form>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/cuti.js') }}"></script>
