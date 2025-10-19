@extends('layouts.mobile.app')
@section('content')
    <style>
        /* Tambahkan style untuk header dan content */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 70px;
            padding-top: 5px;
            position: relative;
            z-index: 1;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Ajukan Lembur</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 80px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('lembur.store') }}" method="POST" id="formLembur" autocomplete="off">
                    @csrf

                    <input type="text" class="feedback-input dari" name="dari" placeholder="Dari" id="datePicker" />
                    <input type="text" class="feedback-input sampai" name="sampai" placeholder="Sampai"
                        id="datePicker2" />
                    <textarea placeholder="Keterangan" class="feedback-input keterangan" name="keterangan" style="height: 100px"></textarea>
                    <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Buat Lembur</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        var lang = {
            title: 'Pilih Tanggal',
            cancel: 'Batal',
            confirm: 'Set',
            year: '',
            month: '',
            day: '',
            hour: '',
            min: '',
            sec: ''
        };
        new Rolldate({
            el: '#datePicker',
            format: 'YYYY-MM-DD hh:mm',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            time: true,
        });

        new Rolldate({
            el: '#datePicker2',
            format: 'YYYY-MM-DD hh:mm',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            time: true,

        });


        $("#formLembur").submit(function(e) {
            let dari = $('.dari').val();
            let sampai = $('.sampai').val();
            let kode_cuti = $('.kode_cuti').val();
            let jml_hari = $('.jml_hari').val();
            let keterangan = $('.keterangan').val();

            if (dari == "" && sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $('.dari').focus();
                    }
                });
                return false;
            } else if (sampai < dari) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Sesuai !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#sampai").focus();
                    }
                });
                return false;
            } else if (keterangan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Keterangan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $('.keterangan').focus();
                    }
                });
                return false;
            }
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white mr-2" role="status">
                </div>
                Sedang Mengirim..`);
        }
    </script>
@endpush
