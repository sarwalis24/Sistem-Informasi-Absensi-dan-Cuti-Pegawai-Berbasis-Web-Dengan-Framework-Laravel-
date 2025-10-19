<form action="{{ route('penyesuaiangaji.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option {{ date('m') == $d['kode_bulan'] ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                            {{ $d['nama_bulan'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="tahun" id="tahun" class="form-select">
                    <option value="">Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option {{ date('Y') == $t ? 'selected' : '' }} value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="row">
       <div class="col">  
        <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButton">
            <i class="ti ti-send me-1"></i> Submit
        </button>
       </div>
    </div>
</form>