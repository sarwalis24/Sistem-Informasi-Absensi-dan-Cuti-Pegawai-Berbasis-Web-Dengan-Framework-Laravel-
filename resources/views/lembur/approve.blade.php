<form action="{{ route('lembur.storeapprove', Crypt::encrypt($lembur->id)) }}" method="POST" id="formApprovelembur">
    @csrf
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Tanggal</th>
                    <td class="text-end">{{ DateToIndo($lembur->tanggal) }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td class="text-end">{{ $lembur->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Karyawan</th>
                    <td class="text-end">{{ $lembur->nama_karyawan }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td class="text-end">{{ $lembur->nama_jabatan }}</td>
                </tr>
                <tr>
                    <th>Dept</th>
                    <td class="text-end">{{ $lembur->nama_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-end">{{ $lembur->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>Waktu Lembur</th>
                    <td class="text-end">
                        {{ date('d-m-Y H:i:s', strtotime($lembur->lembur_mulai)) }} -
                        {{ date('d-m-Y H:i:s', strtotime($lembur->lembur_selesai)) }}
                    </td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td class="text-end">{{ $lembur->keterangan }}</td>
                </tr>
            </table>

        </div>
    </div>

    <div class="row">
        <div class="col">
            <button class="btn btn-primary w-100" name="approve" type="submit" value="approve"><i
                    class="ti ti-thumb-up me-1"></i> Approve </button>
        </div>
        <div class="col">
            <button class="btn btn-danger w-100" name="tolak" type="submit" value="tolak"><i
                    class="ti ti-thumb-down me-1"></i> Tolak </button>
        </div>
    </div>

</form>

<script>
    $(document).on('click', '[name="approve"]', function() {
        $('#formApprovelembur').submit();
        $(this).prop('readonly', true);
        $('button[name="tolak"]').prop('disabled', true);
        $(this).html("<i class='fa fa-spin fa-spinner me-1'></i> Processing...");
    })
</script>
