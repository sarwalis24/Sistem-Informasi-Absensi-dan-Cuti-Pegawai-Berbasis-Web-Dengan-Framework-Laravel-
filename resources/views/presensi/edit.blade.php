<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>NIK</th>
                <td class="text-end">{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <th>Nama Karyawan</th>
                <td class="text-end">{{ $karyawan->nama_karyawan }}</td>
            </tr>
            <tr>
                <th>Dept</th>
                <td class="text-end">{{ $karyawan->kode_dept }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td class="text-end">{{ $karyawan->kode_cabang }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <form action="{{ route('presensi.update') }}" method="POST" id="formEditPresensi">
            @csrf

            <input type="hidden" value="{{ Crypt::encrypt($karyawan->nik) }}" name="nik">
            <input type="hidden" value="{{ $tanggal }}" name="tanggal">
            <div class="form-group">
                <select name="status" id="status" class="form-select">
                    <option value="">Status</option>
                    <option value="h" {{ $presensi != null && $presensi->status == 'h' ? 'selected' : '' }}>Hadir</option>
                    <option value="i" {{ $presensi != null && $presensi->status == 'i' ? 'selected' : '' }}>Izin</option>
                    <option value="s" {{ $presensi != null && $presensi->status == 's' ? 'selected' : '' }}>Sakit</option>
                    <option value="c" {{ $presensi != null && $presensi->status == 'c' ? 'selected' : '' }}>Cuti</option>
                    <option value="a" {{ $presensi != null && $presensi->status == 'a' ? 'selected' : '' }}>Alpha</option>
                </select>
            </div>
            <div class="form-group">
                <select name="kode_jam_kerja" id="kode_jam_kerja" class="form-select">
                    <option value="">Jam Kerja</option>
                    @foreach ($jam_kerja as $d)
                        <option value="{{ $d->kode_jam_kerja }}"
                            {{ $presensi != null && $presensi->kode_jam_kerja == $d->kode_jam_kerja ? 'selected' : '' }}>
                            {{ $d->kode_jam_kerja }} - {{ $d->nama_jam_kerja }}
                            ({{ $d->jam_masuk }} - {{ $d->jam_pulang }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="row">
                <div class="col">
                    <x-input-with-icon icon="ti ti-clock" label="Jam Absen Masuk" name="jam_in" datepicker="flatpickr-date"
                        value="{{ $presensi != null ? $presensi->jam_in : '' }}" />
                </div>
                <div class="col">
                    <x-input-with-icon icon="ti ti-clock" label="Jam Absen Pulang" name="jam_out" datepicker="flatpickr-date"
                        value="{{ $presensi != null ? $presensi->jam_out : '' }}" />
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Submit</button>
            </div>
        </form>
    </div>
</div>
<script>
    $("#jam_in,#jam_out").mask("0000-00-00 00:00");
    $("#jam_in,#jam_out").flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        allowInput: true,
    })
    $("#status").change(function() {
        if ($(this).val() != 'h') {
            $("#jam_in,#jam_out").prop('disabled', true);
        } else {
            $("#jam_in,#jam_out").prop('disabled', false);
        }
    });
    $("#formEditPresensi").submit(function(e) {
        let status = $(this).find("#status").val();
        let kode_jam_kerja = $(this).find("#kode_jam_kerja").val();
        let jam_in = $(this).find("#jam_in").val();
        let jam_out = $(this).find("#jam_out").val();
        if (status == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Status Harus Diisi!',
                didClose: () => {
                    $(this).find("#status").focus();
                }
            });
            return false;
        } else if (kode_jam_kerja == "") {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Jam Kerja Harus Diisi!',
                didClose: () => {
                    $(this).find("#kode_jam_kerja").focus();
                }
            });
            return false;
        }
    });
</script>
