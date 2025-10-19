<form action="{{ route('slipgaji.update', Crypt::encrypt($slipgaji->kode_slip_gaji)) }}" method="POST" id="formSlipGaji">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <select name="bulan" id="bulan" class="form-select">
                    <option value="">Bulan</option>
                    @foreach ($list_bulan as $d)
                        <option value="{{ $d['kode_bulan'] }}"
                            {{ $slipgaji->bulan == $d['kode_bulan'] ? 'selected' : '' }}>{{ $d['nama_bulan'] }}
                        </option>
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
                        <option value="{{ $t }}" {{ $slipgaji->tahun == $t ? 'selected' : '' }}>
                            {{ $t }}</option>
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
                    <option value="1" {{ $slipgaji->status == 1 ? 'selected' : '' }}>Publish</option>
                    <option value="0" {{ $slipgaji->status == 0 ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group mb-3">
                <button type="submit" name="submitButton" class="btn btn-primary w-100" id="btnSimpan">
                    <i class="ti ti-send me-1"></i> Update
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        const form = $('#formSlipGaji');

        function buttonDisable() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..
         `);
        }
        form.submit(function(e) {
            let bulan = form.find('#bulan').val();
            let tahun = form.find('#tahun').val();
            let status = form.find('#status').val();
            buttonDisable();
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
