<form action="{{ route('jenistunjangan.update', Crypt::encrypt($jenistunjangan->kode_jenis_tunjangan)) }}" method="POST" id="formcreateJenisTunjangan">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-barcode" label="Kode Jenis Tunjangan" name="kode_jenis_tunjangan"
        value="{{ $jenistunjangan->kode_jenis_tunjangan }}" />
    <x-input-with-icon icon="ti ti-file-description" label="Jenis Tunjangan" name="jenis_tunjangan" value="{{ $jenistunjangan->jenis_tunjangan }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Submit</button>
    </div>
</form>


<script>
    $("#kode_jenis_tunjangan").mask("AAAA");
    $("#formcreateJenisTunjangan").submit(function(e) {
        const kode_jenis_tunjangan = $(this).find('input[name="kode_jenis_tunjangan"]').val();
        const jenis_tunjangan = $(this).find('input[name="jenis_tunjangan"]').val();
        if (kode_jenis_tunjangan == "") {
            Swal.fire({
                title: "Oops!",
                text: 'Kode Jenis Tunjangan Harus Diisi !',
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $(this).find('input[name="kode_jenis_tunjangan"]').focus();
                }

            });
            return false;
        } else if (jenis_tunjangan == "") {
            Swal.fire({
                title: "Oops!",
                text: 'Jenis Tunjangan Harus Diisi !',
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $(this).find('input[name="jenis_tunjangan"]').focus();
                }

            });
            return false;
        } else {
            $("#btnSimpan").attr("disabled", true);
            $("#btnSimpan").html("<i class='fa fa-spin fa-spinner me-1'></i> loading...");
        }
    });
</script>
