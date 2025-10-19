<form action="{{ route('gajipokok.update', Crypt::encrypt($gajipokok->kode_gaji)) }}" id="formcreateGajiPokok" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2NikEdit" disabled>
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option {{ $gajipokok->nik == $d->nik ? 'selected' : '' }} value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}
                </option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon icon="ti ti-moneybag" label="Gaji Pokok" name="jumlah" money="true" align="right"
        value="{{ formatAngka($gajipokok->jumlah) }}" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Berlaku" name="tanggal_berlaku" datepicker="flatpickr-date"
        value="{{ $gajipokok->tanggal_berlaku }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" id="btnSimpan" type="submit">
            <i class="ti ti-send me-1"></i>
            Submit
        </button>
    </div>
</form>


<script>
    $(".select2NikEdit").each(function() {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            dropdownParent: $this.parent()
        });
    });
    $(".money").maskMoney();
    $(".flatpickr-date").flatpickr();
    $("#formcreateGajiPokok").submit(function(e) {

        const jumlah = $("input[name=jumlah]").val();
        const tanggal_berlaku = $("input[name=tanggal_berlaku]").val();
        if (jumlah == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Gaji Pokok Harus Diisi!',
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
