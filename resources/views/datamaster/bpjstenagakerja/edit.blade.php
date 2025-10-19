<form action="{{ route('bpjstenagakerja.update', Crypt::encrypt($bpjstenagakerja->kode_bpjs_tk)) }}" id="formcreateBpjsTenagaKerja" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2Nik" disabled>
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option {{ $bpjstenagakerja->nik == $d->nik ? 'selected' : '' }} value="{{ $d->nik }}">{{ $d->nik }} -
                    {{ $d->nama_karyawan }}
                </option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon icon="ti ti-moneybag" label="Bpjs Kesehatan" name="jumlah" money="true" align="right"
        value="{{ formatAngka($bpjstenagakerja->jumlah) }}" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Berlaku" name="tanggal_berlaku" datepicker="flatpickr-date"
        value="{{ $bpjstenagakerja->tanggal_berlaku }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" id="btnSimpan" type="submit">
            <i class="ti ti-send me-1"></i>
            Submit
        </button>
    </div>
</form>


<script>
    $(".money").maskMoney();
    $(".flatpickr-date").flatpickr();
    $("#formcreateBpjsTenagaKerja").submit(function(e) {
        const jumlah = $("input[name=jumlah]").val();
        if (jumlah == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Jumlah Bpjs Kesehatan Harus Diisi!',
                showConfirmButton: true,
                didClose: () => {
                    $("#jumlah").focus();
                }
            });
            return false;
        } else if (tanggal_berlaku == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Tanggal Berlaku Harus Diisi!',
                showConfirmButton: true,
                didClose: () => {
                    $("#tanggal_berlaku").focus();
                }
            });
            return false;
        } else {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`Loading..`)
        }
    });
</script>
