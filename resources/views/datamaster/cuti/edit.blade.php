<form action="{{ route('cuti.update', Crypt::encrypt($cuti->kode_cuti)) }}" id="formCuti" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Kode Cuti" name="kode_cuti" icon="ti ti-barcode" value="{{ $cuti->kode_cuti }}" disabled="true" />
    <x-input-with-icon label="Jenis Cuti" name="jenis_cuti" icon="ti ti-file-description" value="{{ $cuti->jenis_cuti }}" />
    <x-input-with-icon label="Jumlah Hari" name="jumlah_hari" icon="ti ti-file-description" value="{{ $cuti->jumlah_hari }}" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100"><i class="ti ti-send"></i>Submit</button>
    </div>
</form>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/cuti.js') }}"></script>
