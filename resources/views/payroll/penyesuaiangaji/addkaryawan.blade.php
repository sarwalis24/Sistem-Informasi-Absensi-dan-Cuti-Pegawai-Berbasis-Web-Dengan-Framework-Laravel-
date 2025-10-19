<form action="{{ route('penyesuaiangaji.storekaryawan', Crypt::encrypt($kode_penyesuaian_gaji)) }}" method="POST" id="formAddKaryawan">
    @csrf
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2Nik">
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon icon="ti ti-plus" label="Penambah" name="penambah" money="true" align="right" />
    <x-input-with-icon icon="ti ti-minus" label="Pengurang" name="pengurang" money="true" align="right" />
    <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control mb-3" placeholder="Keterangan"></textarea>
    <div class="form-group">
        <button class=" btn btn-primary w-100" id="btnSimpan">
            <i class="ti ti-send me-1"></i> Submit
        </button>
    </div>
</form>
<script>
    $(".select2Nik").each(function() {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            dropdownParent: $this.parent()
        });
    });

    $(".money").maskMoney();

    $("#formAddKaryawan").submit(function(e) {
        const nik = $(this).find("#nik").val();
        const penambah = $(this).find("#penambah").val();
        const pengurang = $(this).find("#pengurang").val();
        const keterangan = $(this).find("#keterangan").val();

        if (nik == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Karyawan Harus Dipilih!',
                showConfirmButton: true,
                didClose: () => {
                    $("#nik").focus();
                }
            });
            return false;
        } else if (penambah == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Penambah Harus Diisi!',
                showConfirmButton: true,
                didClose: () => {
                    $("#penambah").focus();
                }
            });
            return false;
        } else if (pengurang == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Pengurang Harus Diisi!',
                showConfirmButton: true,
                didClose: () => {
                    $("#pengurang").focus();
                }
            });
            return false;
        } else if (keterangan == '') {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Keterangan Harus Diisi!',
                showConfirmButton: true,
                didClose: () => {
                    $("#keterangan").focus();
                }
            });
            return false;
        } else {
            $("#btnSimpan").html('<i class="ti ti-spinner me-1"></i> Loading...');
            $("#btnSimpan").attr("disabled", true);
            return true;
        }
    });
</script>
