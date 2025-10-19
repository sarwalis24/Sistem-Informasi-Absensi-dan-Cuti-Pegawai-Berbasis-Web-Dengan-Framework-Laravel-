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
