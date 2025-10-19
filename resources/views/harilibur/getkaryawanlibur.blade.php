@foreach ($detailharilibur as $d)
    <tr>
        <td>{{ $d->nik }}</td>
        <td>{{ formatName2($d->nama_karyawan) }}</td>
        <td>{{ $d->kode_dept }}</td>
        <td>
            <a href="#" class="delete" nik="{{ $d->nik }}">
                <i class="ti ti-circle-minus text-danger"></i>
            </a>
        </td>
    </tr>
@endforeach
