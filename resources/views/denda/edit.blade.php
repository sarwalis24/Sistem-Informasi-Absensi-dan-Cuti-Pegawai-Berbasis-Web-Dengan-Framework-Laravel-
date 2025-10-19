<form action="{{ route('denda.update', ['id' => Crypt::encrypt($denda->id)]) }}" method="POST" id="formDenda">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col">
            <x-input-with-icon-label label="Dari (Menit)" name="dari" icon="ti ti-clock" :value="$denda->dari" />
        </div>
        <div class="col">
            <x-input-with-icon-label label="Sampai (Menit)" name="sampai" icon="ti ti-clock" :value="$denda->sampai" />
        </div>
    </div>
    <x-input-with-icon-label label="Denda" name="denda" icon="ti ti-moneybag" align="right" money="true"
        value="{{ formatAngka($denda->denda) }}" />
    <button class="btn btn-primary w-100" id="btnSimpan" type="Submit">
        <i class="ti ti-send me-1"></i> Update
    </button>
</form>

<script>
    $(".money").maskMoney();
    const formDenda = $("#formDenda");
    formDenda.submit(function() {
        let dari = $(this).find('#dari').val();
        let sampai = $(this).find('#sampai').val();
        let denda = $(this).find("#denda").val();
        if (!dari) {
            swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Field Dari Harus Diisi',
                didClose: (e) => {
                    $(this).find("#dari").focus();
                },

            });
            return false;
        } else if (!sampai) {
            swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Field Sampai Harus Diisi',
                didClose: (e) => {
                    $(this).find("#sampai").focus();
                },

            });
            return false;
        } else if (!denda) {
            swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Field Denda Harus Diisi',
                didClose: (e) => {
                    $(this).find("#denda").focus();
                },

            });
            return false;
        } else {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").text("Loading...").prop('disabled', true);
        }
    });
</script>
