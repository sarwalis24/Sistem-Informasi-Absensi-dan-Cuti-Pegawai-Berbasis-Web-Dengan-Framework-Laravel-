<form action="{{ route('bpjskesehatan.store') }}" id="formcreateBpjsKesehatan" method="POST">
    @csrf
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2Nik">
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
            @endforeach
        </select>
    </div>
    <x-input-with-icon icon="ti ti-moneybag" label="Bpjs Kesehatan" name="jumlah" money="true" align="right" />
    <x-input-with-icon icon="ti ti-calendar" label="Tanggal Berlaku" name="tanggal_berlaku" datepicker="flatpickr-date" />
    <div class="form-group">
        <button class="btn btn-primary w-100" id="btnSimpan" type="submit">
            <i class="ti ti-send me-1"></i>
            Submit
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
    $(".flatpickr-date").flatpickr();
    $("#formcreateBpjsKesehatan").submit(function(e) {
        const nik = $("select[name=nik]").val();
        const jumlah = $("input[name=jumlah]").val();
        const tanggal_berlaku = $("input[name=tanggal_berlaku]").val();
        if (nik == "") {
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
        } else if (jumlah == "") {
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
