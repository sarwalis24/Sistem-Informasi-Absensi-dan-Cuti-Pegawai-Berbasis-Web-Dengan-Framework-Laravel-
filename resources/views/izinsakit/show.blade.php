<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>Kode Izin Sakit</th>
                <td class="text-end">{{ $izinsakit->kode_izin_sakit }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td class="text-end">{{ DateToIndo($izinsakit->tanggal) }}</td>
            </tr>
            <tr>
                <th>NIK</th>
                <td class="text-end">{{ $izinsakit->nik }}</td>
            </tr>
            <tr>
                <th>Nama Karyawan</th>
                <td class="text-end">{{ $izinsakit->nama_karyawan }}</td>
            </tr>
            <tr>
                <th>Jabatan</th>
                <td class="text-end">{{ $izinsakit->nama_jabatan }}</td>
            </tr>
            <tr>
                <th>Dept</th>
                <td class="text-end">{{ $izinsakit->nama_dept }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td class="text-end">{{ $izinsakit->nama_cabang }}</td>
            </tr>
            <tr>
                <th>Lama</th>
                <td class="text-end">
                    @php
                        $lama = hitungHari($izinsakit->dari, $izinsakit->sampai);
                    @endphp
                    {{ $lama }} Hari / {{ DateToIndo($izinsakit->dari) }} - {{ DateToIndo($izinsakit->sampai) }}
                </td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td class="text-end">{{ $izinsakit->keterangan }}</td>
            </tr>
        </table>

    </div>
</div>
