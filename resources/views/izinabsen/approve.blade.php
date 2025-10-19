<form action="{{ route('izinabsen.storeapprove', Crypt::encrypt($izinabsen->kode_izin)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Izin</th>
                    <td class="text-end">{{ $izinabsen->kode_izin }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izinabsen->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izinabsen->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izinabsen->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izinabsen->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Dept</th>
                    <td class="text-end">{{ $izinabsen->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $izinabsen->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>Lama</th>
                    <td class="text-end">
                        @php
                            $lama = hitungHari($izinabsen->dari, $izinabsen->sampai);
                        @endphp
                        {{ $lama }} Hari / {{ DateToIndo($izinabsen->dari) }} - {{ DateToIndo($izinabsen->sampai) }}
                    </td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izinabsen->keterangan }}</td>
                </tr>
            </table>

        </div>
    </div>
    <div class="row mt-2 mb-2">
        <div class="col">
            <x-textarea label="Catatan" name="catatan" />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" name="approve" type="submit" value="approve"><i class="ti ti-thumb-up me-1"></i> Approve </button>
        </div>
        <div class="col">
            <button class="btn btn-danger w-100" name="tolak" type="submit" value="tolak"><i class="ti ti-thumb-down me-1"></i> Tolak </button>
        </div>
    </div>

</form>

<script>
    $(document).on('click', '[name="approve"]', function() {
        $('#formApproveizin').submit();
        $(this).prop('readonly', true);
        $('button[name="tolak"]').prop('disabled', true);
        $(this).html("<i class='fa fa-spin fa-spinner me-1'></i> Processing...");
    })
</script>
