<form action="{{ route('izindinas.storeapprove', Crypt::encrypt($izindinas->kode_izin_dinas)) }}" method="POST" id="formApproveizin">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode Izin</th>
                    <td class="text-end">{{ $izindinas->kode_izin_dinas }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($izindinas->dari) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $izindinas->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $izindinas->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $izindinas->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Dept</th>
                    <td class="text-end">{{ $izindinas->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $izindinas->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>Lama</th>
                    <td class="text-end">
                        @php
                            $lama = hitungHari($izindinas->dari, $izindinas->sampai);
                        @endphp
                        {{ $lama }} Hari / {{ DateToIndo($izindinas->dari) }} - {{ DateToIndo($izindinas->sampai) }}
                    </td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $izindinas->keterangan }}</td>
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
