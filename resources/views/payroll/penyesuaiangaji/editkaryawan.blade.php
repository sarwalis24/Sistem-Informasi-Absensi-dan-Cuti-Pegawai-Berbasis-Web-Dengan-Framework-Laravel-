<form
    action="{{ route('penyesuaiangaji.updatekaryawan', [Crypt::encrypt($detailpenyesuaian->kode_penyesuaian_gaji), Crypt::encrypt($detailpenyesuaian->nik)]) }}"
    method="POST" id="formAddKaryawan">
    @csrf
    @method('PUT')
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2Nik" disabled>
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option {{ $d->nik == $detailpenyesuaian->nik ? 'selected' : '' }} value="{{ $d->nik }}">{{ $d->nik }} -
                    {{ $d->nama_karyawan }}
                </option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon icon="ti ti-plus" label="Penambah" name="penambah" money="true" align="right"
        value="{{ formatAngka($detailpenyesuaian->penambah) }}" />
    <x-input-with-icon icon="ti ti-minus" label="Pengurang" name="pengurang" money="true" align="right"
        value="{{ formatAngka($detailpenyesuaian->pengurang) }}" />
    <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control mb-3" placeholder="Keterangan"> {{ $detailpenyesuaian->keterangan }}</textarea>
    <div class="form-group">
        <button class=" btn btn-primary w-100" id="btnSimpan">
            <i class="ti ti-send me-1"></i> Submit
        </button>
    </div>
</form>
<script>
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
