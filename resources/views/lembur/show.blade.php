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
                    @php
                        $lama = hitungJam($lembur->lembur_in, $lembur->lembur_out);
                    @endphp
                    {{ round($lama, 2) }} Jam
                </td>
            </tr>
            <tr>
                <th>Keterangan</th>
                <td class="text-end">{{ $lembur->keterangan }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row mt-2">
    <div class="col">

        @if (!empty($lembur->foto_lembur_in))
            <span class="badge bg-success mb-2">Mulai Lembur
                {{ date('d-m-Y H:i', strtotime($lembur->lembur_in)) }}</span>
            @if (Storage::disk('public')->exists('/uploads/lembur/' . $lembur->foto_lembur_in))
                <img src="{{ url('/storage/uploads/lembur/' . $lembur->foto_lembur_in) }}"
                    class="card-img rounded thumbnail" alt="">
            @else
                <i class="ti ti-hourglass text-warning" style="font-size: 10rem;"></i>
            @endif
        @else
            <span class="badge bg-danger mb-2">Belum Memulai Lembur</span>
            <i class="ti ti-hourglass text-warning" style="font-size: 10rem;"></i>
        @endif
    </div>
    <div class="col">
        @if (!empty($lembur->foto_lembur_out))
            <span class="badge bg-success mb-2">Selesai Lembur
                {{ date('d-m-Y H:i', strtotime($lembur->lembur_out)) }}</span>
            @if (Storage::disk('public')->exists('/uploads/lembur/' . $lembur->foto_lembur_out))
                <img src="{{ url('/storage/uploads/lembur/' . $lembur->foto_lembur_out) }}"
                    class="card-img rounded thumbnail" alt="">
            @else
                <i class="ti ti-hourglass text-warning" style="font-size: 10rem;"></i>
            @endif
        @else
            <span class="badge bg-danger mb-2">Belum Mengakhiri Lembur</span>
            <i class="ti ti-hourglass text-warning" style="font-size: 10rem;"></i>
        @endif
    </div>
</div>
