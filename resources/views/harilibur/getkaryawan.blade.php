@foreach ($karyawan as $d)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ formatName2($d->nama_karyawan) }}</td>
        <td>{{ $d->nama_dept }}</td>
        <td>
            <a href="#" nik="{{ $d->nik }}" class="updateLibur">
                @if (empty($d->ceklibur))
                    <i class="ti ti-plus"></i>
                @else
                    <i class="ti ti-circle-minus text-danger"></i>
                @endif
            </a>
        </td>
    </tr>
@endforeach
