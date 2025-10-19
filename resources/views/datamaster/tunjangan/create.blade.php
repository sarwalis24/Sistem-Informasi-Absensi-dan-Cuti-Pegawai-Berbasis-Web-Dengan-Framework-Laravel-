<form action="{{ route('tunjangan.store') }}" id="formcreateTunjangan" method="POST">
    @csrf
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2Nik">
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
            @endforeach
        </select>
    </div>
    @foreach ($jenis_tunjangan as $d)
        <input type="hidden" name="kode_jenis_tunjangan[]" value="{{ $d->kode_jenis_tunjangan }}">
        <x-input-with-icon icon="ti ti-moneybag" label="{{ $d->jenis_tunjangan }}" name="jumlah[]" money="true" align="right" />
    @endforeach
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
    $("#formcreateTunjangan").submit(function(e) {
        // e.preventDefault();
        const nik = $("select[name=nik]").val();
        const jumlah = $("input[name='jumlah[]']");
        const tanggal_berlaku = $("input[name=tanggal_berlaku]").val();
        let cekJumlahkosong = false;
        jumlah.each(function() {
            if ($(this).val() == "") {
                cekJumlahkosong = true;
                return false;
            }
        });
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
        } else if (cekJumlahkosong) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Jumlah Tunjangan Harus Diisi Semua!',
                showConfirmButton: true,
                didClose: () => {
                    $("input[name='jumlah[]']").focus();
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
