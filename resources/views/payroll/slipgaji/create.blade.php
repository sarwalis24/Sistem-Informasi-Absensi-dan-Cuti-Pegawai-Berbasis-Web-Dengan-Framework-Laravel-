<form action="{{ route('slipgaji.store') }}" method="POST" id="formSlipGaji">
    @csrf
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}">{{ $d['nama_bulan'] }}</option>
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
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="status" id="status" class="form-select">
                    <option value="">Status</option>
                    <option value="1">Publish</option>
                    <option value="0">Pending</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <button type="submit" name="submitButton" class="btn btn-primary w-100" id="submitButtonSlipGaji">
                    <i class="ti ti-send me-1"></i> Buat Slip Gaji
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formSlipGaji');
        form.submit(function(e) {
            let bulan = form.find('#bulan').val();
            let tahun = form.find('#tahun').val();
            let status = form.find('#status').val();
            if (bulan == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Bulan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#bulan").focus();
                    },
                });
                return false;
            } else if (tahun == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tahun Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#tahun").focus();
                    },
                });
                return false;
            } else if (status == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Status Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#status").focus();
                    },
                });
                return false;
            }

        });
    });
</script>
