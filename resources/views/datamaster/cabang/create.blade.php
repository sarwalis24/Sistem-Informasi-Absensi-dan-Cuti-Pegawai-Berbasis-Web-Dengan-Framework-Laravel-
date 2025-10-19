<form action="{{ route('cabang.store') }}" id="formcreateCabang" method="POST">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Kode Cabang" name="kode_cabang" />
    <x-input-with-icon icon="ti ti-file-text" label="Nama Cabang" name="nama_cabang" />
    <x-input-with-icon icon="ti ti-map-pin" label="Alamat Cabang" name="alamat_cabang" />
    <x-input-with-icon icon="ti ti-phone" label="Telepon Cabang" name="telepon_cabang" />
    <x-input-with-icon icon="ti ti-map-pin" label="Lokasi Cabang" name="lokasi_cabang" />
    <x-input-with-icon icon="ti ti-access-point" label="Radius Cabang" name="radius_cabang" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/cabang/create.js') }}"></script>
