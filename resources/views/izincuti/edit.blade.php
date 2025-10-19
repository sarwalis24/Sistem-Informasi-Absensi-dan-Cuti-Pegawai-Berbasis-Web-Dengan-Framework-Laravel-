<form action="{{ route('izincuti.update', ['kode_izin_cuti' => Crypt::encrypt($izincuti->kode_izin_cuti)]) }}" method="POST" id="formIzin"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_izin_cuti" disabled="true" :value="$izincuti->kode_izin_cuti" />
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2Nik">
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option value="{{ $d->nik }}" @selected($izincuti->nik == $d->nik)>{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" :value="$izincuti->dari" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" :value="$izincuti->sampai" />
        </div>
    </div>
    <div class="form-group mb-3">
        <select name="kode_cuti" id="kode_cuti" class="form-select">
            <option value="">Jenis Cuti</option>
            @foreach ($jenis_cuti as $d)
                <option value="{{ $d->kode_cuti }}" @selected($izincuti->kode_cuti == $d->kode_cuti)>{{ $d->jenis_cuti }} </option>
            @endforeach
        </select>
    </div>


    <x-input-with-icon icon="ti ti-sun" label="Jumlah Hari" name="jml_hari" disabled="true" :value="$izincuti->jml_hari" />
    <x-textarea label="Keterangan" name="keterangan" :value="$izincuti->keterangan" />

    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Simpan</button>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formIzin');
        $(".flatpickr-date").flatpickr();
        const select2Nik = $('.select2Nik');

        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function hitungHari(startDate, endDate) {
            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);

                // Tambahkan 1 hari agar penghitungan inklusif
                var timeDifference = end - start + (1000 * 3600 * 24);
                var dayDifference = timeDifference / (1000 * 3600 * 24);

                return dayDifference;
            } else {
                return 0;
            }
        }

        $("#dari,#sampai").on("change", function() {
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            $("#jml_hari").val(hitungHari(dari, sampai));
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }
        form.find("#jml_hari").val(hitungHari(form.find("#dari").val(), form.find("#sampai").val()));
        form.submit(function(e) {
            const nik = form.find("#nik").val();
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            const keterangan = form.find("#keterangan").val();
            const kode_cuti = form.find("#kode_cuti").val();
            if (nik == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Karyawan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nik").focus();
                    },
                });
                return false;
            } else if (dari == '' || sampai == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#dari").focus();
                    }
                });
                return false;
            } else if (sampai < dari) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Sesuai !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#sampai").focus();
                    }
                });
                return false;
            } else if (kode_cuti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jenis Cuti Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cuti").focus();
                    }
                });
                return false;
            } else if (keterangan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Keterangan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    }
                });
                return false;
            } else {
                buttonDisabled();
            }
        });

    });
</script>
