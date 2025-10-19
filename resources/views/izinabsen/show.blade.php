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
