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
            <div class="pageTitle">Izin Absen</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 30px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('izinabsen.store') }}" method="POST" id="formIzin" autocomplete="off">
                    @csrf

                    <input type="text" class="feedback-input dari" name="dari" placeholder="Dari" id="datePicker" />
                    <input type="text" class="feedback-input sampai" name="sampai" placeholder="Sampai" id="datePicker2" />
                    <input type="text" class="feedback-input jml_hari" name="jml_hari" placeholder="Jumlah Hari" disabled />
                    <textarea placeholder="Keterangan" class="feedback-input keterangan" name="keterangan" style="height: 100px"></textarea>
                    <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Buat Izin Absen</button>
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
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            confirm: function(date) {
                let jmlhari = hitungHari(date, $('#datePicker2').val());
                $('.jml_hari').val(jmlhari);
            }
        });

        new Rolldate({
            el: '#datePicker2',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            confirm: function(date) {
                let jmlhari = hitungHari($('#datePicker').val(), date);
                $('.jml_hari').val(jmlhari);
            }
        });

        const batasi_hari_izin = "{{ $general_setting->batasi_hari_izin }}";
        const jml_hari_izin_max = "{{ $general_setting->jml_hari_izin_max }}";

        function hitungHari(startDate, endDate) {

            if (startDate && endDate) {
                // let parts1 = startDate.split("-");
                // startDate = `${parts1[2]}-${parts1[1]}-${parts1[0]}`;

                // let parts2 = endDate.split("-");
                // endDate = `${parts2[2]}-${parts2[1]}-${parts2[0]}`;

                var start = new Date(startDate);
                var end = new Date(endDate);

                // Tambahkan 1 hari agar penghitungan inklusif
                var timeDifference = end - start + (1000 * 3600 * 24);
                var dayDifference = timeDifference / (1000 * 3600 * 24);

                return dayDifference;
            } else {
                return 0;
            }
        }

        $("#formIzin").submit(function(e) {
            let dari = $('.dari').val();
            let sampai = $('.sampai').val();
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
            } else if (jml_hari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jumlah Hari Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $('.jml_hari').focus();
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
            } else if (hitungHari(dari, sampai) > jml_hari_izin_max && batasi_hari_izin == 1) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Tidak Boleh Lebih Dari ' + jml_hari_izin_max + ' Hari !',
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
