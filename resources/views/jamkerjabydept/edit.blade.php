<form action="{{ route('jamkerjabydept.update', Crypt::encrypt($jamkerjabydept->kode_jk_dept)) }}" method="POST" id="formSetJamkerja">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>Kode</th>
                    <td>{{ $jamkerjabydept->kode_jk_dept }}</td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td class="text-uppercase">{{ $jamkerjabydept->nama_cabang }}</td>
                </tr>
                <tr>
                    <th>Departemen</th>
                    <td class="text-uppercase">{{ $jamkerjabydept->nama_dept }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Hari</th>
                        <th>Jam Kerja</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $nama_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                    @endphp
                    @foreach ($nama_hari as $hari)
                        <tr>
                            <td class="text-capitalize" style="width: 10%">
                                <input type="hidden" name="hari[]" value="{{ $hari }}">
                                {{ $hari }}
                            </td>
                            <td>
                                <div class="form-group p-0" style="margin-bottom: 0px !important">
                                    <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                        <option value="">Pilih Jam Kerja</option>
                                        @foreach ($jamkerja as $d)
                                            @if (array_key_exists($hari, $detailjamkerjabydept) && $detailjamkerjabydept[$hari] == $d->kode_jam_kerja)
                                                <option value="{{ $d->kode_jam_kerja }}" selected>{{ $d->nama_jam_kerja }}
                                                    ({{ $d->jam_masuk }} -
                                                    {{ $d->jam_pulang }})
                                                </option>
                                            @else
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}
                                                    ({{ $d->jam_masuk }} -
                                                    {{ $d->jam_pulang }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Submit</button></button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#formSetJamkerja").submit(function(e) {
            // e.preventDefault();
            $(this).find("#btnSimpan").attr("disabled", true);
            $(this).find("#btnSimpan").html("<i class='fa fa-spin fa-spinner me-1'></i> Menyimpan...");
        });
    });
</script>
