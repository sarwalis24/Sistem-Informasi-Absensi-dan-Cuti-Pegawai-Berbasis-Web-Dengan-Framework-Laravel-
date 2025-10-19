<form action="{{ route('jabatan.update', ['kode_jabatan' => Crypt::encrypt($jabatan->kode_jabatan)]) }}" method="POST" id="formJabatan">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Kode Jabatan" name="kode_jabatan" icon="ti ti-barcode" :value="$jabatan->kode_jabatan" />
    <x-input-with-icon label="Nama Jabatan" name="nama_jabatan" icon="ti ti-building" :value="$jabatan->nama_jabatan" />
    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Simpan Perubahan</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#formJabatan").submit(function(e) {
            let kode_jabatan = $(this).find("#kode_jabatan").val();
            let nama_jabatan = $(this).find("#nama_jabatan").val();
            if (!kode_jabatan) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Kode Jabatan harus diisi!',
                    didClose: () => {
                        $(this).find("#kode_jabatan").focus();
                    }
                });
            } else if (!nama_jabatan) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Nama Jabatan harus diisi!',
                    didClose: () => {
                        $(this).find("#nama_jabatan").focus();
                    }
                });
            } else {
                $("#btnSimpan").attr('disabled', true);
                $("#btnSimpan").html('<i class="ti ti-spinner me-1"></i> Menyimpan...');
            }
        })
    })
</script>
